<?php
/**
 * Email through Brevo's SMTP relay, for every inquiry form.
 *
 * Each inquiry sends one email, beside its row in the Google Sheet
 * (sheets.php): to the team (cfg('enquiry_recipient')), every field and the
 * files attached, Reply going straight to the person who asked. Nothing is
 * emailed to the person, to keep within the Brevo plan's sending limit.
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
 *   inline      [['cid' => .., 'name' => .., 'mime' => .., 'data' => raw bytes], ..]
 *               images the HTML shows as src="cid:..", carried in the email
 *               itself so they show without being hosted anywhere (optional)
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
    // The message, built inside out: text and HTML side by side; then the
    // images the HTML shows (cid: links), with it; then any files.
    [$type, $body] = mailer_multipart('alternative', [
        "Content-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . chunk_split(base64_encode((string) ($m['text'] ?? ''))),
        "Content-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . chunk_split(base64_encode((string) ($m['html'] ?? ''))),
    ]);
    if (!empty($m['inline'])) {
        $parts = ["Content-Type: $type\r\n\r\n" . $body];
        foreach ($m['inline'] as $img) {
            $name = str_replace('"', '', mailer_line((string) $img['name']));
            $parts[] = 'Content-Type: ' . mailer_line((string) $img['mime']) . '; name="' . $name . "\"\r\n"
                     . 'Content-ID: <' . mailer_line((string) $img['cid']) . ">\r\n"
                     . "Content-Disposition: inline; filename=\"$name\"\r\nContent-Transfer-Encoding: base64\r\n\r\n"
                     . chunk_split(base64_encode((string) $img['data']));
        }
        [$type, $body] = mailer_multipart('related', $parts);
    }
    if (!empty($m['attachments'])) {
        $parts = ["Content-Type: $type\r\n\r\n" . $body];
        foreach ($m['attachments'] as $f) {
            $name = str_replace('"', '', mailer_line((string) ($f['name'] ?? 'file')));
            $parts[] = 'Content-Type: ' . mailer_line((string) ($f['mime'] ?? 'application/octet-stream')) . '; name="' . $name . "\"\r\n"
                     . "Content-Disposition: attachment; filename=\"$name\"\r\nContent-Transfer-Encoding: base64\r\n\r\n"
                     . chunk_split(base64_encode((string) ($f['data'] ?? '')));
        }
        [$type, $body] = mailer_multipart('mixed', $parts);
    }
    $headers[] = 'Content-Type: ' . $type;
    $data = implode("\r\n", $headers) . "\r\n\r\n" . $body;
    // A line that is only "." would end the message early: double it.
    $data = preg_replace('/^\./m', '..', $data);

    return mailer_smtp($from, $to, $data);
}

/** Wrap MIME parts (each with its own headers) as one multipart: [Content-Type, body]. */
function mailer_multipart(string $subtype, array $parts): array
{
    $b = $subtype . '_' . bin2hex(random_bytes(8));
    $body = '';
    foreach ($parts as $p) {
        $body .= "--$b\r\n" . $p . "\r\n";
    }
    return ['multipart/' . $subtype . '; boundary="' . $b . '"', $body . "--$b--\r\n"];
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

/* The team's email, laid out like the Bishnoi Omniverse inquiry email: the
   logo and the inquiry's name top left beside a curved corner, "Dear
   Recipient", a card with a blue-to-green heading over the fields as a
   striped table, each long answer as its own "Message" block under it, then
   the tagline between two rules and a curved corner bottom right. The logo's
   own blue and green (#0199DC, #53A136); Poppins where the mail app loads it
   (Gmail falls back to Arial); medium and semibold weights only; no icons.
   The logo and corners travel inside the email (cid:), so they show without
   being hosted anywhere. Tables and inline styles only, for Gmail. */
const MAILER_BLUE  = '#0199DC';
const MAILER_GREEN = '#53A136';
const MAILER_FONT  = "'Poppins', Arial, Helvetica, sans-serif";
/** Long answers, shown as their own block under the table. */
const MAILER_LONG_LABELS = ['Message', 'Notes', 'How can we help?', 'What happened', 'What happened?', 'What would you like us to do'];

/** A field's value for the email: escaped, line breaks kept, links clickable. */
function mailer_value(string $v): string
{
    $out = nl2br(e($v));
    if (filter_var($v, FILTER_VALIDATE_EMAIL)) {
        return '<a href="mailto:' . e($v) . '" style="color:' . MAILER_BLUE . ';text-decoration:underline">' . $out . '</a>';
    }
    if (preg_match('~^https?://\S+$~', $v)) {
        return '<a href="' . e($v) . '" style="color:' . MAILER_BLUE . ';text-decoration:underline">' . $out . '</a>';
    }
    return $out;
}

/** The images every email carries: the logo and the two corners. */
function mailer_brand_images(): array
{
    $imgs = [];
    foreach ([
        'logo'          => '/assets/img/logo-getmeds-vanuatu.png',
        'corner-top'    => '/assets/img/email/swoosh-top.png',
        'corner-bottom' => '/assets/img/email/swoosh-bottom.png',
    ] as $cid => $path) {
        $bytes = @file_get_contents(APP_ROOT . $path);
        if ($bytes !== false) {
            $imgs[] = ['cid' => $cid . '@getmeds', 'name' => basename($path), 'mime' => 'image/png', 'data' => $bytes];
        }
    }
    return $imgs;
}

/**
 * The team's copy of an inquiry: every field, the files attached, Reply going
 * to the person. $what names it ("Quotation request"), $about adds a detail
 * to the subject (a medicine's name), $replyTo is ['email' => .., 'name' => ..].
 */
function mailer_notify_team(string $what, array $rows, array $files = [], array $replyTo = [], string $about = ''): bool
{
    $mail = mailer_team_email($what, $rows, $about);
    return mailer_send($mail + [
        'to'          => ['email' => (string) cfg('enquiry_recipient', cfg('email'))],
        'reply_to'    => $replyTo,
        'inline'      => mailer_brand_images(),
        'attachments' => $files,
    ]);
}

/** The team email's subject, HTML and text, built apart from sending. */
function mailer_team_email(string $what, array $rows, string $about = ''): array
{
    // In Port Vila's time, whatever the server's own clock is set to.
    $rows[] = ['Received', (new DateTime('now', new DateTimeZone('Pacific/Efate')))->format('j M Y, g:i A') . ' (Vanuatu time)'];
    $title  = $what . ($about !== '' ? ': ' . $about : '');
    $site   = (string) cfg('site_name');
    $f      = 'font-family:' . MAILER_FONT . ';';
    $line   = '#E5E8EA';

    $table = '';
    $long  = '';
    $text  = '';
    $i = 0;
    foreach ($rows as [$label, $value]) {
        $label = (string) $label;
        $value = trim((string) $value);
        if ($value === '') {
            continue;
        }
        $text .= $label . ': ' . $value . "\n";
        if (in_array($label, MAILER_LONG_LABELS, true)) {
            $long .= '<tr><td style="padding:28px 0 0">'
                   . '<div style="border-left:4px solid ' . MAILER_GREEN . ';padding:2px 0 2px 16px;' . $f . 'font-size:17px;font-weight:600;color:#111111">' . e(rtrim($label, '?')) . '</div>'
                   . '<div style="padding:12px 0 0 20px;' . $f . 'font-size:15px;font-weight:500;line-height:1.7;color:#3A4450">' . mailer_value($value) . '</div>'
                   . '</td></tr>';
            continue;
        }
        $bg = $i++ % 2 === 0 ? '#F4F6F5' : '#FFFFFF';
        $table .= '<tr>'
                . '<td valign="top" width="40%" style="background-color:' . $bg . ';border-top:1px solid ' . $line . ';border-right:1px solid ' . $line . ';padding:18px 28px;' . $f . 'font-size:15px;font-weight:500;line-height:1.5;color:' . MAILER_GREEN . '">' . e($label) . '</td>'
                . '<td valign="top" style="background-color:' . $bg . ';border-top:1px solid ' . $line . ';padding:18px 28px;' . $f . 'font-size:15px;font-weight:500;line-height:1.5;color:#111111">' . mailer_value($value) . '</td>'
                . '</tr>';
    }

    $html = '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
          . '<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet"></head>'
          . '<body style="margin:0;padding:0;background-color:#FFFFFF">'
          . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#FFFFFF"><tr><td align="center">'
          . '<table role="presentation" width="640" cellpadding="0" cellspacing="0" border="0" style="width:640px;max-width:100%">'
          // Logo and the inquiry's name, the corner beside them.
          . '<tr><td valign="top" style="padding:44px 0 0 40px">'
          . '<img src="cid:logo@getmeds" width="170" height="105" alt="' . e($site) . '" style="display:block;border:0;width:170px;height:105px">'
          . '<div style="padding-top:18px;' . $f . 'font-size:17px;font-weight:500;font-style:italic;color:' . MAILER_GREEN . '">' . e($what) . '</div>'
          . '</td><td valign="top" align="right" width="220" style="width:220px">'
          . '<img src="cid:corner-top@getmeds" width="220" height="236" alt="" style="display:block;border:0;width:220px;height:236px">'
          . '</td></tr>'
          . '<tr><td colspan="2" style="padding:0 40px">'
          . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">'
          . '<tr><td style="border-top:1px solid #E5E5E5;font-size:0;line-height:0;height:1px">&nbsp;</td></tr>'
          . '<tr><td style="padding:28px 0 0;' . $f . 'font-size:16px;font-weight:500;color:#1B2B3A">Dear Recipient,</td></tr>'
          . '<tr><td style="padding:14px 0 28px;' . $f . 'font-size:16px;font-weight:500;line-height:1.65;color:#3A4450">This ' . e(strtolower($what)) . ' was submitted through the ' . e($site) . ' website. Reply directly to this email to respond to the sender.</td></tr>'
          // The card: heading, then the fields.
          . '<tr><td><table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid ' . $line . ';border-radius:8px;border-collapse:separate;overflow:hidden">'
          . '<tr><td colspan="2" bgcolor="' . MAILER_BLUE . '" style="background-color:' . MAILER_BLUE . ';background-image:linear-gradient(135deg,' . MAILER_BLUE . ' 0%,' . MAILER_GREEN . ' 100%);border-radius:8px 8px 0 0;padding:28px 32px">'
          . '<div style="' . $f . 'font-size:24px;font-weight:600;line-height:1.25;color:#FFFFFF">' . e($site) . '</div>'
          . '<div style="padding-top:8px;' . $f . 'font-size:15px;font-weight:500;line-height:1.4;color:#FFFFFF">' . e($title) . '</div>'
          . '</td></tr>' . $table . '</table></td></tr>'
          . $long
          // The tagline between two rules.
          . '<tr><td style="padding:52px 0 0"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr>'
          . '<td width="28%" style="border-top:1px solid #1B2B3A;font-size:0;line-height:0">&nbsp;</td>'
          . '<td align="center" style="padding:0 16px;white-space:nowrap;' . $f . 'font-size:14px;font-weight:500;letter-spacing:1.5px;color:' . MAILER_GREEN . '">Cancer Medicines <span style="color:' . MAILER_BLUE . '">&bull;</span> Here in Vanuatu</td>'
          . '<td width="28%" style="border-top:1px solid #1B2B3A;font-size:0;line-height:0">&nbsp;</td>'
          . '</tr></table></td></tr>'
          . '</table></td></tr>'
          // The corner, bottom right.
          . '<tr><td colspan="2" align="right" style="padding-top:28px">'
          . '<img src="cid:corner-bottom@getmeds" width="260" height="117" alt="" style="display:block;border:0;width:260px;height:117px">'
          . '</td></tr>'
          . '</table></td></tr></table></body></html>';

    return [
        'subject' => $title,
        'text'    => $title . "\n\nThis " . strtolower($what) . ' was submitted through the ' . $site . " website. Reply directly to this email to respond to the sender.\n\n" . $text,
        'html'    => $html,
    ];
}
