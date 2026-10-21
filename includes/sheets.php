<?php
/**
 * Sends a form submission to the Google Sheet.
 *
 * The sheet is fed by a Google Apps Script web app (docs/google-sheets.gs),
 * not by the Sheets API, so the site holds no Google credentials: it posts
 * JSON to the script's /exec address with a shared secret, and the script
 * appends a row to the tab named in the post (creating the tab, and any new
 * column, the first time it is needed). Attachments go along as base64; the
 * script saves them to a Drive folder and puts their links in the row.
 *
 * Settings, environment first, then data/secrets.php:
 *   SHEETS_WEBHOOK_URL    / 'sheets_webhook_url'     the web app's /exec address
 *   SHEETS_WEBHOOK_SECRET / 'sheets_webhook_secret'  must match SECRET in the script
 * Without a URL nothing is sent and sheets_append() answers false.
 */

/* Which tab each form's rows go to: one tab per inquiry type (the four people
   of the Send Inquiry menu), whether it came from the Contact page or a
   medicine page ('shop-' + its userType). Anything not listed uses its kind. */
const SHEETS_TABS = [
    'enquiry'          => 'Patient or Caregiver',
    'shop-patient'     => 'Patient or Caregiver',
    'doctor-enquiry'   => 'Doctor or Healthcare Professional',
    'shop-doctor'      => 'Doctor or Healthcare Professional',
    'pharmacy-enquiry' => 'Pharmacy',
    'shop-pharmacy'    => 'Pharmacy',
    'quotation'        => 'Hospital or Institution',
    'shop-hospital'    => 'Hospital or Institution',
    'general-enquiry'  => 'Something Else',
    'complaint'        => 'Complaints',
    'side-effect'      => 'Side Effect Reports',
];

const SHEETS_MAX_FILES_BYTES = 20 * 1024 * 1024;

function sheets_setting(string $env, string $key): string
{
    $v = getenv($env);
    if (is_string($v) && trim($v) !== '') {
        return trim($v);
    }
    static $secrets = null;
    if ($secrets === null) {
        $file    = APP_ROOT . '/data/secrets.php';
        $secrets = is_file($file) ? (require $file) : [];
        if (!is_array($secrets)) {
            $secrets = [];
        }
    }
    return trim((string) ($secrets[$key] ?? ''));
}

function sheets_enabled(): bool
{
    return sheets_setting('SHEETS_WEBHOOK_URL', 'sheets_webhook_url') !== '';
}

/**
 * Append one submission. $fields is [column heading => value], in order.
 * $files is a list of ['name' => string, 'mime' => string, 'data' => raw bytes].
 * Returns true once the script confirms the row was written.
 */
function sheets_append(string $kind, string $ref, array $fields, array $files = []): bool
{
    $url = sheets_setting('SHEETS_WEBHOOK_URL', 'sheets_webhook_url');
    if ($url === '' || !function_exists('curl_init')) {
        return false;
    }

    $sendFiles = [];
    $total     = 0;
    foreach ($files as $f) {
        $total += strlen($f['data']);
        if ($total > SHEETS_MAX_FILES_BYTES) {
            break;   // the row still goes; the files stay on the server or in the email
        }
        $sendFiles[] = ['name' => $f['name'], 'mime' => $f['mime'], 'data' => base64_encode($f['data'])];
    }

    $body = json_encode([
        'secret'   => sheets_setting('SHEETS_WEBHOOK_SECRET', 'sheets_webhook_secret'),
        'sheet'    => SHEETS_TABS[$kind] ?? $kind,
        'ref'      => $ref,
        'received' => date('Y-m-d H:i:s'),
        'fields'   => (object) array_map('strval', $fields),
        'files'    => $sendFiles,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    // Apps Script answers the POST with a redirect to the result; following it
    // as a GET (curl's default for a 302) is how its web apps are meant to be read.
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 3,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT        => 40,
    ]);
    $resp   = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $err    = curl_error($ch);
    curl_close($ch);

    $out = is_string($resp) ? json_decode($resp, true) : null;
    if ($status !== 200 || !is_array($out) || empty($out['ok'])) {
        // Never the person's details in the log: only what went wrong.
        error_log('Getmeds sheets ' . $ref . ': ' . $status . ' '
                . ($err !== '' ? $err : mb_substr((string) ($out['error'] ?? $resp), 0, 200)));
        return false;
    }
    return true;
}
