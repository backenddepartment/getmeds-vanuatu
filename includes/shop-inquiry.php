<?php
/**
 * Receives a Send Inquiry form from a medicine's page and emails it.
 * Included by medicines/shop/index.php for POST /medicines/shop/inquiry.
 *
 * assets/js/shop-inquiry.js posts JSON, with files as base64. Everything it
 * already checked is checked again here, because anyone can post anything:
 *   1. bot trap: the hidden "website" field must be empty
 *   2. the session's CSRF token, and one inquiry per 20 seconds
 *   3. every field, by profile (Patient / Caregiver needs the patient's ID
 *      and the prescription; Doctor, Pharmacy and Hospital need neither)
 *   4. the files: JPG, PNG or PDF by their content, not their name; each
 *      under 4 MB, 5 MB and 6 files in all
 *   5. a row in the Google Sheet (sheets.php), and, when a Brevo key is set,
 *      the email to cfg('enquiry_recipient') with Reply going to the person
 *      who asked. It counts as sent if either one worked.
 * Always answers JSON: {ok, ref} or {ok: false, title, message}.
 *
 * The Brevo key is read from the BREVO_API_KEY environment variable, or from
 * data/secrets.php ('brevo_api_key'), never from config.php, which is
 * committed. The sender address ('brevo_sender_email' in secrets.php, else
 * cfg('email')) must be a sender verified in the Brevo account.
 */

require_once INC . '/sheets.php';

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

function inq_secret(string $env, string $key): string
{
    $v = getenv($env);
    if (is_string($v) && trim($v) !== '') {
        return trim($v);
    }
    $file = APP_ROOT . '/data/secrets.php';
    if (is_file($file)) {
        $secrets = require $file;
        if (is_array($secrets) && !empty($secrets[$key])) {
            return trim((string) $secrets[$key]);
        }
    }
    return '';
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
    inq_reply(200, ['ok' => true, 'ref' => '']);
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

// 5. Send: a row in the Google Sheet, and the email when Brevo is set up.
$apiKey = inq_secret('BREVO_API_KEY', 'brevo_api_key');
if ($apiKey === '' && !sheets_enabled()) {
    error_log('Getmeds inquiry: neither the Google Sheet (sheets_webhook_url) nor Brevo (brevo_api_key) is configured.');
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
    $sheetRow[$label === 'Sent from' ? 'Page' : ($sameAs[$label] ?? $label)] = $value;
}
$sheetOk = sheets_append('shop-' . $userType, $ref, $sheetRow, array_map(static function ($f) {
    return ['name' => $f['name'], 'mime' => $f['mime'], 'data' => $f['bytes']];
}, $files));

if ($apiKey === '') {
    if (!$sheetOk) {
        inq_fail('Your inquiry could not be sent just now. ' . $contactLine, 'Inquiry Not Sent', 502);
    }
    $_SESSION['inq_last'] = time();
    inq_reply(200, ['ok' => true, 'ref' => $ref]);
}
$sender = inq_secret('BREVO_SENDER_EMAIL', 'brevo_sender_email') ?: (string) cfg('email');
$rows[] = ['Reference', $ref];

$html = '<h2 style="font-family:Arial,sans-serif">New inquiry: ' . e($product['name']) . '</h2>'
      . '<table cellpadding="8" style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px">';
$text = 'New inquiry: ' . $product['name'] . "\n\n";
foreach ($rows as [$label, $value]) {
    $html .= '<tr><th align="left" valign="top" style="border-bottom:1px solid #ddd;background:#f6f8fa">' . e($label) . '</th>'
           . '<td style="border-bottom:1px solid #ddd">' . nl2br(e($value)) . '</td></tr>';
    $text .= $label . ': ' . $value . "\n";
}
$html .= '</table>';

$mail = [
    'sender'      => ['name' => (string) cfg('site_name'), 'email' => $sender],
    'to'          => [['email' => (string) cfg('enquiry_recipient', cfg('email'))]],
    'replyTo'     => ['email' => $email, 'name' => $name],
    'subject'     => 'Inquiry ' . $ref . ': ' . $product['name'] . ' (' . INQ_PROFILES[$userType] . ')',
    'htmlContent' => $html,
    'textContent' => $text,
];
if ($files) {
    $mail['attachment'] = array_map(static function ($f) {
        return ['name' => $f['name'], 'content' => $f['content']];
    }, $files);
}

$ch = curl_init('https://api.brevo.com/v3/smtp/email');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT        => 45,
    CURLOPT_HTTPHEADER     => ['accept: application/json', 'content-type: application/json', 'api-key: ' . $apiKey],
    CURLOPT_POSTFIELDS     => json_encode($mail, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
]);
$resp   = curl_exec($ch);
$status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
$err    = curl_error($ch);
curl_close($ch);

if ($resp === false || $status < 200 || $status >= 300) {
    // The reason goes to the server log only: never the key, never the person's details.
    error_log('Getmeds inquiry ' . $ref . ': Brevo answered ' . $status . ' ' . ($err !== '' ? $err : mb_substr((string) $resp, 0, 300)));
    // Already in the sheet, so the inquiry is not lost: only fail if neither worked.
    if (!$sheetOk) {
        inq_fail('Your inquiry could not be sent just now. ' . $contactLine, 'Inquiry Not Sent', 502);
    }
}

$_SESSION['inq_last'] = time();
inq_reply(200, ['ok' => true, 'ref' => $ref]);
