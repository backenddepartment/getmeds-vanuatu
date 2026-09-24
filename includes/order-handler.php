<?php
/**
 * Order handling, including the prescription upload.
 *
 * Why this exists alongside enquiry-handler.php: an enquiry is a question, and
 * it was a two-step form that asked about the medicine before it asked who you
 * are. An order is a transaction. It is one screen, and the upload sits in the
 * middle of it, because "where do I put my prescription" was the question this
 * site could not answer.
 *
 * Decisions worth knowing before editing:
 *
 * - ONE step. The old two-step flow was gentler, but it hid the upload behind a
 *   page load, and nobody scrolling fast ever found it.
 *
 * - The upload is optional. Somebody holding a paper script on an island with a
 *   bad camera must still be able to order; a pharmacist rings them. Optional,
 *   but explained in one line rather than buried.
 *
 * - Files are validated by real content type, not by the name the browser sent,
 *   and they are stored under data/ with generated names. .htaccess refuses
 *   /data/ over HTTP, so an uploaded prescription is never served back out.
 *
 * - Spam is a honeypot plus a minimum completion time. No CAPTCHA: a patient on
 *   chemotherapy does not owe anyone a puzzle.
 */

const ORDER_MIN_SECONDS = 4;

/** Extension => the content types finfo may legitimately report for it. */
function order_allowed_types(): array
{
    return [
        'jpg'  => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png'  => ['image/png'],
        'webp' => ['image/webp'],
        // iPhones photograph in HEIC. Older finfo databases do not know the
        // type and fall back to octet-stream, so the extension carries it.
        'heic' => ['image/heic', 'image/heif', 'application/octet-stream'],
        'heif' => ['image/heic', 'image/heif', 'application/octet-stream'],
        'pdf'  => ['application/pdf'],
    ];
}

function order_schema(): array
{
    return [
        'need' => [
            'label' => 'What do you need?', 'type' => 'radio', 'required' => true,
            'options' => [
                'cancer' => 'A cancer medicine',
                'side'   => 'A medicine for side effects',
                'other'  => 'Another specialty medicine',
                'unsure' => 'Not sure — it is on the prescription',
            ],
        ],
        'medicine' => [
            'label' => 'Medicine name', 'type' => 'textarea', 'required' => true,
            'hint'  => 'Copy what the prescription says, one per line. If you cannot read it, '
                     . 'write what you can and upload the photo below.',
        ],
        'quantity' => [
            'label' => 'How much do you need?', 'type' => 'text', 'required' => false,
            'hint'  => 'A rough answer is fine: one cycle, a month, two boxes.',
        ],
        'name'  => ['label' => 'Your name', 'type' => 'text', 'required' => true],
        'phone' => [
            'label' => 'Phone number', 'type' => 'tel', 'required' => true,
            'hint'  => 'A pharmacist will call you on this. It is faster than typing.',
        ],
        'email' => [
            'label' => 'Email address', 'type' => 'email', 'required' => false,
            'hint'  => 'Optional.',
        ],
        'place' => [
            'label' => 'Where are you?', 'type' => 'select', 'required' => true,
            'default' => 'Vanuatu',
            'options' => [
                'Vanuatu'         => 'Vanuatu',
                'Fiji'            => 'Fiji',
                'Solomon Islands' => 'Solomon Islands',
                'New Caledonia'   => 'New Caledonia',
                'Elsewhere'       => 'Somewhere else',
            ],
        ],
    ];
}

function ord_str($v): string
{
    return is_string($v) ? trim($v) : '';
}

/** Runs the order form. Returns the state the page should render. */
function order_run(): array
{
    $schema = order_schema();

    $state = [
        'schema'  => $schema,
        'errors'  => [],
        'values'  => [],
        'files'   => [],
        'done'    => false,
        'ref'     => null,
        'started' => time(),
    ];

    // Let a category link pre-select the first question.
    if (isset($_GET['need']) && isset($schema['need']['options'][ord_str($_GET['need'])])) {
        $state['values']['need'] = ord_str($_GET['need']);
    }
    if (isset($_GET['medicine'])) {
        $state['values']['medicine'] = ord_str($_GET['medicine']);
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return $state;
    }

    // A POST larger than post_max_size arrives with $_POST and $_FILES emptied
    // and no warning the visitor can see. Say what actually happened.
    if (!$_POST && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        $state['errors']['_form'] = 'Those files were too large for the server to accept, so '
            . 'nothing was sent. Try again with smaller photos, or send them on WhatsApp.';
        return $state;
    }

    if (!csrf_valid($_POST['_token'] ?? null)) {
        $state['errors']['_form'] = 'Your form session expired and nothing was sent. '
            . 'Check the details below and send it again.';
        $state['values'] = order_collect($schema);
        return $state;
    }

    $state['values']  = order_collect($schema);
    $state['started'] = (int) ($_POST['started'] ?? time());
    $state['errors']  = order_validate($schema, $state['values']);

    // Validate the uploads even when other fields failed, so one send surfaces
    // every problem rather than trickling them out one reload at a time.
    $files = order_validate_uploads($state['errors']);

    if ($state['errors']) {
        return $state;
    }

    $isBot = ord_str($_POST['website'] ?? '') !== ''
          || (time() - $state['started']) < ORDER_MIN_SECONDS;

    $state['ref'] = order_reference();

    if (!$isBot) {
        $state['files'] = order_store_uploads($files, $state['ref']);
        order_deliver($state['ref'], $state['values'], $state['files']);
    }

    $state['done'] = true;
    return $state;
}

function order_collect(array $schema): array
{
    $out = [];
    foreach ($schema as $name => $def) {
        $out[$name] = ord_str($_POST[$name] ?? '');
    }
    return $out;
}

function order_validate(array $schema, array $values): array
{
    $errors = [];

    foreach ($schema as $name => $def) {
        $v = $values[$name] ?? '';

        if (!empty($def['required']) && $v === '') {
            $errors[$name] = order_required_message($name, $def);
            continue;
        }
        if ($v === '') {
            continue;
        }
        if (($def['type'] === 'radio' || $def['type'] === 'select')
            && !isset($def['options'][$v])) {
            $errors[$name] = 'Choose one of the options listed.';
        }
        if ($name === 'email' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $errors[$name] = 'That email address is missing something. Check it, or leave it '
                           . 'blank and we will phone you instead.';
        }
        if ($name === 'phone' && strlen((string) preg_replace('/\D+/', '', $v)) < 5) {
            $errors[$name] = 'Add a phone number we can reach you on, with the country code '
                           . 'if you are outside Vanuatu.';
        }
        if (mb_strlen($v) > 4000) {
            $errors[$name] = 'That is longer than this form can send. Shorten it, or call us.';
        }
    }

    return $errors;
}

function order_required_message(string $name, array $def): string
{
    switch ($name) {
        case 'need':     return 'Choose one, so this reaches the right pharmacist.';
        case 'medicine': return 'Type the medicine name from your prescription. Anything you '
                              . 'can read is enough to start.';
        case 'name':     return 'Add your name, so a pharmacist knows who they are calling.';
        case 'phone':    return 'Add a phone number. This is how a pharmacist will reach you.';
        case 'place':    return 'Choose where you are, so we can work out delivery.';
        default:         return 'This one is needed: ' . lcfirst($def['label']) . '.';
    }
}

/**
 * Checks the uploaded prescriptions. Returns the ones worth keeping and adds a
 * plain-language error for anything rejected. Nothing is written to disk here.
 */
function order_validate_uploads(array &$errors): array
{
    $field = $_FILES['prescription'] ?? null;
    if (!$field || !is_array($field['name'] ?? null)) {
        return [];
    }

    $maxMb    = (int) cfg('upload_max_mb', 8);
    $maxBytes = $maxMb * 1024 * 1024;
    $maxFiles = (int) cfg('upload_max_files', 5);
    $allowed  = order_allowed_types();
    $keep     = [];
    $problems = [];

    $count = count($field['name']);
    for ($i = 0; $i < $count; $i++) {
        $err = (int) ($field['error'][$i] ?? UPLOAD_ERR_NO_FILE);
        if ($err === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        $orig = (string) ($field['name'][$i] ?? '');
        $show = $orig === '' ? 'One file' : '"' . $orig . '"';

        if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
            $problems[] = $show . ' is too large. The limit is ' . $maxMb . ' MB.';
            continue;
        }
        if ($err !== UPLOAD_ERR_OK) {
            $problems[] = $show . ' did not finish uploading. Try it again.';
            continue;
        }

        $tmp = (string) ($field['tmp_name'][$i] ?? '');
        if (!is_uploaded_file($tmp)) {
            $problems[] = $show . ' could not be read.';
            continue;
        }
        if (filesize($tmp) > $maxBytes) {
            $problems[] = $show . ' is too large. The limit is ' . $maxMb . ' MB.';
            continue;
        }

        $ext = strtolower((string) pathinfo($orig, PATHINFO_EXTENSION));
        if (!isset($allowed[$ext])) {
            $problems[] = $show . ' is not a kind of file we can open. Send a photo '
                        . '(JPG, PNG, HEIC) or a PDF.';
            continue;
        }

        // Trust the bytes, not the filename.
        $mime = 'application/octet-stream';
        if (function_exists('finfo_open') && ($fi = finfo_open(FILEINFO_MIME_TYPE))) {
            $mime = (string) finfo_file($fi, $tmp);
            finfo_close($fi);
        }
        if (!in_array($mime, $allowed[$ext], true)) {
            $problems[] = $show . ' is not the kind of file its name says it is, so it was '
                        . 'not accepted. Send a photo or a PDF.';
            continue;
        }

        $keep[] = ['tmp' => $tmp, 'ext' => $ext, 'orig' => $orig, 'size' => filesize($tmp)];
    }

    if (count($keep) > $maxFiles) {
        $keep = array_slice($keep, 0, $maxFiles);
        $problems[] = 'Only the first ' . $maxFiles . ' files were kept. Send the rest on '
                    . 'WhatsApp if a pharmacist needs them.';
    }
    if ($problems) {
        $errors['prescription'] = implode(' ', $problems);
    }

    return $keep;
}

/** Moves validated uploads into data/uploads. Returns what was stored. */
function order_store_uploads(array $files, string $ref): array
{
    if (!$files) {
        return [];
    }

    $month = date('Y-m');
    $dir   = APP_ROOT . '/data/uploads/' . $month;
    if (!is_dir($dir) && !@mkdir($dir, 0700, true) && !is_dir($dir)) {
        return [];
    }

    $safeRef = preg_replace('/[^A-Za-z0-9-]/', '', $ref);
    $stored  = [];
    $i       = 0;

    foreach ($files as $f) {
        $i++;
        $name   = $safeRef . '-' . $i . '-' . bin2hex(random_bytes(4)) . '.' . $f['ext'];
        $target = $dir . '/' . $name;

        if (@move_uploaded_file($f['tmp'], $target)) {
            @chmod($target, 0600);
            $stored[] = [
                'path' => 'data/uploads/' . $month . '/' . $name,
                'orig' => $f['orig'],
                'size' => $f['size'],
            ];
        }
    }

    return $stored;
}

/** A short reference the customer can quote on the phone. */
function order_reference(): string
{
    return sprintf('%s-%s-%04d', cfg('enquiry_ref_prefix', 'GV'), date('Y'), random_int(0, 9999));
}

function order_deliver(string $ref, array $values, array $files): void
{
    $lines = ['Reference: ' . $ref, 'Type: order', 'Received: ' . date('c'), ''];
    foreach ($values as $k => $v) {
        if ($v !== '') {
            $lines[] = str_pad($k . ':', 12) . str_replace(["\r\n", "\n"], ' / ', $v);
        }
    }
    if ($files) {
        $lines[] = '';
        $lines[] = 'Prescription files:';
        foreach ($files as $f) {
            $lines[] = '  ' . $f['path'] . '  (' . $f['orig'] . ', '
                     . round($f['size'] / 1024) . ' KB)';
        }
    } else {
        $lines[] = '';
        $lines[] = 'Prescription files: none uploaded, ask on the call.';
    }
    $body = implode("\n", $lines) . "\n";

    if (cfg('enquiry_mail_enabled')) {
        $subject = sprintf('[%s] Order - Getmeds Vanuatu website', $ref);
        $headers = 'From: Getmeds Vanuatu website <no-reply@' . order_host() . ">\r\n"
                 . "Content-Type: text/plain; charset=UTF-8\r\n";
        @mail((string) cfg('enquiry_recipient'), $subject, $body, $headers);
    }

    $dir = APP_ROOT . '/data';
    if (is_dir($dir) && is_writable($dir)) {
        @file_put_contents($dir . '/orders.log', $body . "----\n", FILE_APPEND | LOCK_EX);
    }
}

function order_host(): string
{
    $h = (string) ($_SERVER['HTTP_HOST'] ?? 'getmeds.vu');
    return preg_replace('/[^A-Za-z0-9.\-]/', '', $h) ?: 'getmeds.vu';
}
