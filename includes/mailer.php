<?php
/**
 * Email through Brevo's SMTP relay, for every inquiry form.
 *
 * Each inquiry sends two emails, beside its row in the Google Sheet
 * (sheets.php):
 *   - to the team (cfg('enquiry_recipient')): every field, the files
 *     attached, Reply going straight to the person who asked;
 *   - to that person: a short "we have received it" with their reference.
 *
 * Settings come from environment variables, or from data/secrets.php (never
 * from config.php, which is committed):
 *   BREVO_SMTP_LOGIN   / 'brevo_smtp_login'    the SMTP login Brevo shows
 *   BREVO_SMTP_KEY     / 'brevo_smtp_key'      an SMTP key (not an API key)
 *   BREVO_SENDER_EMAIL / 'brevo_sender_email'  a sender verified in Brevo;
 *                                              '' uses cfg('email')
 *   BREVO_SENDER_NAME  / 'brevo_sender_name'   '' uses cfg('site_name')
 * Without a login and key nothing is sent and mailer_send() answers false;
 * the forms still save to the sheet and the logs, so nothing is lost.
 *
 * A small SMTP client of its own (STARTTLS on port 587, AUTH LOGIN), so the
 * site needs no mail library. Failures go to the PHP error log with the
 * server's reply, never the key or the person's details.
 */

const MAILER_HOST    = 'smtp-relay.brevo.com';
const MAILER_PORT    = 587;
const MAILER_TIMEOUT = 20;

function mailer_setting(string $env, string $key): string
{
    $v = getenv($env);
    if (is_string($v) && trim($v) !== '') {
        return trim($v);
    }
    static $secrets = null;
    if ($secrets === null) {
        $file = APP_ROOT . '/data/secrets.php';
        $secrets = is_file($file) ? (array) (include $file) : [];
    }
    return trim((string) ($secrets[$key] ?? ''));
}

function mailer_enabled(): bool
{
    return mailer_setting('BREVO_SMTP_LOGIN', 'brevo_smtp_login') !== ''
        && mailer_setting('BREVO_SMTP_KEY', 'brevo_smtp_key') !== '';
}

/** One header-safe line: no CR or LF, so nothing can add a header. */
function mailer_line(string $s): string
{
    return trim(str_replace(["\r", "\n"], ' ', $s));
}

/** A display name and address for a header, the name encoded for UTF-8. */
function mailer_address(string $email, string $name = ''): string
{
    $email = mailer_line($email);
    $name  = mailer_line($name);
    return $name === '' ? '<' . $email . '>' : '=?UTF-8?B?' . base64_encode($name) . '?= <' . $email . '>';
}

/**
 * Send one email. $m:
 *   to          ['email' => .., 'name' => ..]
 *   subject     string
 *   text, html  the two bodies
 *   reply_to    ['email' => .., 'name' => ..] (optional)
 *   attachments [['name' => .., 'mime' => .., 'data' => raw bytes], ..] (optional)
 * Answers true once Brevo has accepted it.
 */
function mailer_send(array $m): bool
{
    if (!mailer_enabled()) {
        return false;
    }
    $to = mailer_line((string) ($m['to']['email'] ?? ''));
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $from     = mailer_setting('BREVO_SENDER_EMAIL', 'brevo_sender_email') ?: (string) cfg('email');
    $fromName = mailer_setting('BREVO_SENDER_NAME', 'brevo_sender_name') ?: (string) cfg('site_name');

    // The message: text and HTML side by side, then any files.
    $alt   = 'alt_' . bin2hex(random_bytes(8));
    $mixed = 'mix_' . bin2hex(random_bytes(8));
    $files = $m['attachments'] ?? [];
    $headers = [
        'Date: ' . date('r'),
        'From: ' . mailer_address($from, $fromName),
        'To: ' . mailer_address($to, (string) ($m['to']['name'] ?? '')),
        'Subject: =?UTF-8?B?' . base64_encode(mailer_line((string) $m['subject'])) . '?=',
        'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . mailer_line(substr(strrchr($from, '@') ?: '@getmeds.vu', 1)) . '>',
        'MIME-Version: 1.0',
    ];
    $reply = mailer_line((string) ($m['reply_to']['email'] ?? ''));
    if ($reply !== '' && filter_var($reply, FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'Reply-To: ' . mailer_address($reply, (string) ($m['reply_to']['name'] ?? ''));
    }
    $body  = "--$alt\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n"
           . chunk_split(base64_encode((string) ($m['text'] ?? ''))) . "\r\n"
           . "--$alt\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n"
           . chunk_split(base64_encode((string) ($m['html'] ?? ''))) . "\r\n--$alt--\r\n";
    if ($files) {
        $headers[] = 'Content-Type: multipart/mixed; boundary="' . $mixed . '"';
        $parts = "--$mixed\r\nContent-Type: multipart/alternative; boundary=\"$alt\"\r\n\r\n" . $body . "\r\n";
        foreach ($files as $f) {
            $name = str_replace('"', '', mailer_line((string) ($f['name'] ?? 'file')));
            $parts .= "--$mixed\r\nContent-Type: " . mailer_line((string) ($f['mime'] ?? 'application/octet-stream')) . '; name="' . $name . "\"\r\n"
                    . "Content-Disposition: attachment; filename=\"$name\"\r\nContent-Transfer-Encoding: base64\r\n\r\n"
                    . chunk_split(base64_encode((string) ($f['data'] ?? ''))) . "\r\n";
        }
        $body = $parts . "--$mixed--\r\n";
    } else {
        $headers[] = 'Content-Type: multipart/alternative; boundary="' . $alt . '"';
    }
    $data = implode("\r\n", $headers) . "\r\n\r\n" . $body;
    // A line that is only "." would end the message early: double it.
    $data = preg_replace('/^\./m', '..', $data);

    return mailer_smtp($from, $to, $data);
}

/** Speak SMTP to Brevo: STARTTLS, log in, hand over one message. */
function mailer_smtp(string $from, string $to, string $data): bool
{
    $sock = @stream_socket_client('tcp://' . MAILER_HOST . ':' . MAILER_PORT, $errno, $errstr, MAILER_TIMEOUT);
    if (!$sock) {
        error_log('Getmeds mailer: cannot reach ' . MAILER_HOST . ': ' . $errstr);
        return false;
    }
    stream_set_timeout($sock, MAILER_TIMEOUT);
    $host = mailer_line((string) ($_SERVER['SERVER_NAME'] ?? 'localhost')) ?: 'localhost';

    // Each step: what to send (null: just read) and the reply code expected.
    $talk = static function (?string $line, string $want) use ($sock): bool {
        if ($line !== null) {
            fwrite($sock, $line . "\r\n");
        }
        $reply = '';
        while (($l = fgets($sock, 1024)) !== false) {
            $reply .= $l;
            if (strlen($l) < 4 || $l[3] === ' ') {
                break;
            }
        }
        if (strncmp($reply, $want, 3) !== 0) {
            // The command, minus anything secret, and what the server said.
            $what = $line === null ? 'greeting' : (strpos($line, 'AUTH') === 0 || $want === '235' || $want === '334' ? 'login' : strtok($line, ' :'));
            error_log('Getmeds mailer: ' . $what . ' answered ' . trim(mb_substr($reply, 0, 200)));
            return false;
        }
        return true;
    };

    $ok = $talk(null, '220')
       && $talk('EHLO ' . $host, '250')
       && $talk('STARTTLS', '220')
       && @stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)
       && $talk('EHLO ' . $host, '250')
       && $talk('AUTH LOGIN', '334')
       && $talk(base64_encode(mailer_setting('BREVO_SMTP_LOGIN', 'brevo_smtp_login')), '334')
       && $talk(base64_encode(mailer_setting('BREVO_SMTP_KEY', 'brevo_smtp_key')), '235')
       && $talk('MAIL FROM:<' . $from . '>', '250')
       && $talk('RCPT TO:<' . $to . '>', '250')
       && $talk('DATA', '354')
       && $talk($data . "\r\n.", '250');
    @fwrite($sock, "QUIT\r\n");
    fclose($sock);
    return $ok;
}

/** The fields as an HTML table and as plain text. $rows: [[label, value], ..] */
function mailer_table(array $rows): array
{
    $html = '<table cellpadding="8" style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px;width:100%;max-width:640px">';
    $text = '';
    foreach ($rows as [$label, $value]) {
        $value = (string) $value;
        if ($value === '') {
            continue;
        }
        $html .= '<tr><th align="left" valign="top" style="width:200px;border-bottom:1px solid #e2e8f0;background:#f6f8fa;color:#0B2A5B">' . e((string) $label) . '</th>'
               . '<td style="border-bottom:1px solid #e2e8f0;color:#1B2B3A">' . nl2br(e($value)) . '</td></tr>';
        $text .= $label . ': ' . $value . "\n";
    }
    return [$html . '</table>', $text];
}

/**
 * The team's copy of an inquiry: every field, the files attached, Reply going
 * to the person. $what names it ("Quotation request"), $about adds a detail
 * to the subject (a medicine's name), $replyTo is ['email' => .., 'name' => ..].
 */
function mailer_notify_team(string $ref, string $what, array $rows, array $files = [], array $replyTo = [], string $about = ''): bool
{
    [$table, $text] = mailer_table(array_merge($rows, [['Reference', $ref], ['Received', date('j M Y, H:i')]]));
    $title = $what . ($about !== '' ? ': ' . $about : '');
    $html = '<div style="font-family:Arial,sans-serif;color:#1B2B3A">'
          . '<h2 style="margin:0 0 4px;color:#1D9FDA;font-size:20px">' . e($title) . '</h2>'
          . '<p style="margin:0 0 16px;color:#5A6B7B">Reference ' . e($ref) . '. Reply to this email to answer the person directly.</p>'
          . $table . '</div>';
    return mailer_send([
        'to'          => ['email' => (string) cfg('enquiry_recipient', cfg('email'))],
        'subject'     => '[' . $ref . '] ' . $title,
        'text'        => $title . "\nReference: " . $ref . "\n\n" . $text,
        'html'        => $html,
        'reply_to'    => $replyTo,
        'attachments' => $files,
    ]);
}

/**
 * The person's confirmation: we have it, here is the reference, how to reach
 * us. Only what they sent us about (no copy of their details or files).
 */
function mailer_confirm_customer(string $email, string $name, string $ref, string $what, string $about = ''): bool
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $site  = (string) cfg('site_name');
    $phone = (string) cfg('phone');
    $hello = $name !== '' ? 'Dear ' . $name . ',' : 'Hello,';
    $subj  = 'We have received your ' . strtolower($what) . ' (' . $ref . ')';
    $line  = 'Thank you for contacting ' . $site . '. We have received your ' . strtolower($what)
           . ($about !== '' ? ' about ' . $about : '') . '. Our team will review it and get back to you.';
    $text = $hello . "\n\n" . $line . "\n\nYour reference: " . $ref
          . "\nPlease quote it if you call or reply.\n\nIf it is urgent, call us on " . $phone . ".\n\n" . $site;
    $html = '<div style="font-family:Arial,sans-serif;color:#1B2B3A;font-size:15px;line-height:1.6;max-width:560px">'
          . '<p style="margin:0 0 14px">' . e($hello) . '</p>'
          . '<p style="margin:0 0 18px">' . e($line) . '</p>'
          . '<p style="margin:0 0 18px;padding:14px 18px;border-radius:10px;background:#F2F8FC;color:#0B2A5B">Your reference: <strong>' . e($ref) . '</strong><br>'
          . '<span style="color:#5A6B7B;font-size:14px">Please quote it if you call or reply.</span></p>'
          . '<p style="margin:0 0 18px">If it is urgent, call us on <a href="' . e(tel_url()) . '" style="color:#1D9FDA">' . e($phone) . '</a>.</p>'
          . '<p style="margin:0;color:#5A6B7B">' . e($site) . '</p></div>';
    return mailer_send([
        'to'       => ['email' => $email, 'name' => $name],
        'subject'  => $subj,
        'text'     => $text,
        'html'     => $html,
        'reply_to' => ['email' => (string) cfg('enquiry_recipient', cfg('email'))],
    ]);
}
