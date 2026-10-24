<?php
/**
 * Receives a Send Inquiry form from a medicine's page and emails it.
 * Included by product-range/index.php for POST /product-range/inquiry.
 *
 * assets/js/shop-inquiry.js posts JSON, with files as base64. Everything it
 * already checked is checked again here, because anyone can post anything:
 *   1. bot trap: the hidden "website" field must be empty
 *   2. the session's CSRF token, and one inquiry per 20 seconds
 *   3. every field, by profile (Patient / Caregiver needs the patient's ID
 *      and the prescription; Doctor, Pharmacy and Hospital need neither)
 *   4. the files: JPG, PNG or PDF by their content, not their name; each
 *      under 4 MB, 5 MB and 6 files in all
 *   5. a row in the Google Sheet (sheets.php), and, once Brevo SMTP is set
 *      up (mailer.php), the team's email with the files and Reply going to
 *      the person. It counts as sent if the sheet or the email worked.
 * Always answers JSON: {ok} or {ok: false, title, message}.
 */

require_once INC . '/sheets.php';
require_once INC . '/mailer.php';

const INQ_MAX_FILE  = 4 * 1024 * 1024;   // each file. Keep in step with shop-inquiry.js.
const INQ_MAX_TOTAL = 5 * 1024 * 1024;
const INQ_MAX_FILES = 6;
const INQ_MAX_BODY  = 9 * 1024 * 1024;   // 5 MB of files is about 6.7 MB as base64
const INQ_GAP_SECS  = 20;

const INQ_PROFILES = [
    'patient'  => 'Patient / Caregiver',
    'doctor'   => 'Doctor / Healthcare Professional',
    'pharmacy' => 'Pharmacy Owner / Retail Pharmacy',
    'hospital' => 'Hospital / Institution',
];
const INQ_TYPES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'application/pdf' => 'pdf'];
const INQ_RELATIONSHIPS = ['', 'Self (patient)', 'Family member', 'Caregiver', 'Guardian', 'Healthcare professional', 'Other'];

/** Answer and stop. */
function inq_reply(int $status, array $body): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function inq_fail(string $message, string $title = 'Please Check Your Form', int $status = 422): void
{
    inq_reply($status, ['ok' => false, 'title' => $title, 'message' => $message]);
}

/** A trimmed one-line string, '' for anything that is not a string. */
function inq_str($v, int $max): string
{
    if (!is_string($v)) {
        return '';
    }
    $v = trim((string) preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $v));
    return mb_substr($v, 0, $max);
}


if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    inq_fail('Use the Send Inquiry form on a medicine\'s page.', 'Not Allowed', 405);
}

$contactLine = 'Please try again, or call us on ' . cfg('phone') . '.';

$len = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
if ($len > INQ_MAX_BODY) {
    inq_fail('Your attachments are too large. The total must stay under ' . (INQ_MAX_TOTAL / 1048576) . ' MB.', 'Too Many Attachments', 413);
}
$in = json_decode((string) file_get_contents('php://input', false, null, 0, INQ_MAX_BODY + 1), true);
if (!is_array($in)) {
    inq_fail('Your inquiry did not arrive complete. ' . $contactLine, 'Inquiry Not Sent', 400);
}

// 1. Bot trap: answer as if it worked, and send nothing.
if (inq_str($in['website'] ?? '', 200) !== '') {
    inq_reply(200, ['ok' => true]);
}

// 2. Same session as the page, and not too fast.
if (!csrf_valid(is_string($in['_token'] ?? null) ? $in['_token'] : null)) {
    inq_fail('This page has been open for a long time. Refresh it and send your inquiry again.', 'Please Refresh the Page', 403);
}
if (isset($_SESSION['inq_last']) && time() - (int) $_SESSION['inq_last'] < INQ_GAP_SECS) {
    inq_fail('You have just sent an inquiry. Please wait a few seconds before sending another.', 'Please Wait', 429);
}

// 3. Fields.
$products = shop_products();
$slug     = inq_str($in['product'] ?? '', 200);
if (!isset($products[$slug])) {
    inq_fail('That product is not in our list. ' . $contactLine, 'Inquiry Not Sent');
}
$product  = $products[$slug];
$userType = inq_str($in['userType'] ?? '', 20);
if (!isset(INQ_PROFILES[$userType])) {
    inq_fail('Please choose who you are from the list above the form.');
}
$patient = $userType === 'patient';

$name  = inq_str($in['name'] ?? '', 120);
$email = inq_str($in['email'] ?? '', 160);
$phone = inq_str($in['phone'] ?? '', 30);
$age   = inq_str($in['age'] ?? '', 3);

if ($name === '') {
    inq_fail($patient ? "Please enter the patient's full name." : 'Please enter your full name.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    inq_fail('Please enter a valid email address.');
}
if (!preg_match('/^\+?[0-9 ()-]{6,20}$/', $phone) || strlen((string) preg_replace('/\D/', '', $phone)) < 6) {
    inq_fail('Please enter a valid phone number.');
}
if ($age !== '' && (!ctype_digit($age) || (int) $age < 1 || (int) $age > 100)) {
    inq_fail('Please choose an age from the list.');
}

$rows = [
    ['Product', $product['name'] . ' (' . $product['category'] . ')'],
    ['Profile', INQ_PROFILES[$userType]],
];
$files = [];

if ($patient) {
    $address = inq_str($in['address'] ?? '', 300);
    $contact = inq_str($in['contactName'] ?? '', 120);
    $rel     = inq_str($in['relationship'] ?? '', 40);
    if ($age === '') {
        inq_fail("Please select the patient's age.");
    }
    if ($address === '') {
        inq_fail('Please enter the delivery address.');
    }
    if ($contact === '') {
        inq_fail("Please provide the contact person's full name.");
    }
    if (!in_array($rel, INQ_RELATIONSHIPS, true)) {
        $rel = 'Other';
    }
    if (($in['terms'] ?? false) !== true || ($in['privacy'] ?? false) !== true) {
        inq_fail('Please tick both consent boxes before sending.');
    }

    // 4. Files, by what they contain rather than what they are called.
    $list = is_array($in['files'] ?? null) ? array_values($in['files']) : [];
    if (count($list) > INQ_MAX_FILES) {
        inq_fail('Please attach no more than ' . INQ_MAX_FILES . ' files in total (ID and prescription together).', 'Too Many Attachments');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $total = 0;
    $have  = ['id' => 0, 'prescription' => 0];
    foreach ($list as $f) {
        $kind = is_array($f) ? inq_str($f['kind'] ?? '', 20) : '';
        if (!isset($have[$kind])) {
            continue;
        }
        $shown = inq_str($f['name'] ?? 'file', 80) ?: 'file';
        $bytes = base64_decode(is_string($f['data'] ?? null) ? $f['data'] : '', true);
        if ($bytes === false || $bytes === '') {
            inq_fail($shown . ' could not be read. Remove it and attach it again.', 'Invalid File');
        }
        if (strlen($bytes) >= INQ_MAX_FILE) {
            inq_fail($shown . ' is too large: each file must be under ' . (INQ_MAX_FILE / 1048576) . ' MB.', 'Invalid File');
        }
        $mime = (string) $finfo->buffer($bytes);
        if (!isset(INQ_TYPES[$mime])) {
            inq_fail($shown . ' is not a JPG, PNG or PDF.', 'Invalid File');
        }
        $total += strlen($bytes);
        $have[$kind]++;
        // Our own name for the attachment: never the one the browser sent.
        $files[] = [
            'name'    => ($kind === 'id' ? 'patient-id-' : 'prescription-') . $have[$kind] . '.' . INQ_TYPES[$mime],
            'content' => base64_encode($bytes),
            'shown'   => $shown,
            'mime'    => $mime,
            'bytes'   => $bytes,
        ];
    }
    if ($total > INQ_MAX_TOTAL) {
        inq_fail('Your attachments add up to ' . number_format($total / 1048576, 1) . ' MB. The total must stay under ' . (INQ_MAX_TOTAL / 1048576) . ' MB.', 'Too Many Attachments');
    }
    if ($have['prescription'] === 0) {
        inq_fail('A valid prescription is required before your inquiry can be submitted.', 'Prescription Required');
    }
    if ($have['id'] === 0) {
        inq_fail('A valid ID of the patient is required before your inquiry can be submitted.', 'Valid ID Required');
    }

    array_push($rows,
        ['Patient name', $name],
        ['Patient age', $age],
        ['Email', $email],
        ['Phone', $phone],
        ['Delivery address', $address],
        ['Contact person', $contact],
        ['Relationship to patient', $rel !== '' ? $rel : 'Not given'],
        ['Consent', 'Confirmed accurate and valid; accepted the Privacy Policy'],
        ['Attachments', implode(', ', array_map(static function ($f) {
            return $f['name'] . ' (sent as "' . $f['shown'] . '")';
        }, $files))]
    );
} else {
    $message = is_string($in['message'] ?? null) ? mb_substr(trim($in['message']), 0, 3000) : '';
    if ($message === '') {
        inq_fail('Please tell us more about your requirements.');
    }
    array_push($rows,
        ['Name', $name],
        ['Age', $age !== '' ? $age : 'Not given'],
        ['Email', $email],
        ['Phone', $phone],
        ['Message', $message !== '' ? $message : 'None']
    );
}

// 5. Send: a row in the Google Sheet, and the emails through Brevo (mailer.php).
if (!mailer_enabled() && !sheets_enabled()) {
    error_log('Getmeds inquiry: neither the Google Sheet (sheets_webhook_url) nor Brevo SMTP (brevo_smtp_login, brevo_smtp_key) is configured.');
    inq_fail('Our inquiry form is not taking messages right now. Please call us on ' . cfg('phone') . ' or email ' . cfg('email') . '.', 'Inquiry Not Sent', 503);
}

$ref = cfg('enquiry_ref_prefix', 'GV') . '-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
$rows[] = ['Sent from', abs_url(shop_product_path($product))];

// In the sheet these share a tab with the Contact page's form for the same
// person, so fields both forms ask for go under the Contact form's heading.
$sameAs = [
    'patient'  => ['Contact person' => 'Full name', 'Patient name' => 'Patient name (if different)',
                   'Product' => 'Medicine name(s)', 'Phone' => 'Phone or WhatsApp number'],
    'doctor'   => ['Name' => 'Full name', 'Product' => 'Medicine name and strength',
                   'Phone' => 'Phone or WhatsApp number'],
    'pharmacy' => ['Name' => 'Contact person', 'Message' => 'Notes'],
    'hospital' => ['Name' => 'Contact person', 'Message' => 'Notes'],
][$userType];
$sheetRow = ['Source' => 'Medicine page (Send Inquiry)'];
foreach ($rows as [$label, $value]) {
    if ($label === 'Profile') {
        continue;   // the tab already says who it is
    }
    // The sheet links the files themselves under Patient ID and Prescription;
    // their names go beside them, clear of the Attachments links column.
    $label = ['Sent from' => 'Page', 'Attachments' => 'File names'][$label] ?? $label;
    $sheetRow[$sameAs[$label] ?? $label] = $value;
}
$attach = array_map(static function ($f) {
    return ['name' => $f['name'], 'mime' => $f['mime'], 'data' => $f['bytes']];
}, $files);
$sheetOk = sheets_append('shop-' . $userType, $ref, $sheetRow, $attach);

// The team's email, with the files and Reply going to the person (nothing
// is emailed to the person). Counts as sent if the sheet or the email worked.
$mailOk = mailer_notify_team('Medicine inquiry', $rows, $attach,
    ['email' => $email, 'name' => $name], $product['name'] . ' (' . INQ_PROFILES[$userType] . ')');
if (!$sheetOk && !$mailOk) {
    inq_fail('Your inquiry could not be sent just now. ' . $contactLine, 'Inquiry Not Sent', 502);
}

$_SESSION['inq_last'] = time();
inq_reply(200, ['ok' => true]);
