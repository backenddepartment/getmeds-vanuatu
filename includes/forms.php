<?php
/**
 * One form engine for every form in the content guide: the enquiry form on
 * /contact, the side-effect report, the complaints form and the quotation form
 * on /healthcare-professionals.
 *
 * A page defines a schema, calls gform_run() BEFORE any output (it may start a
 * session), then gform_render() where the form goes. Submissions are checked
 * (CSRF, honeypot, minimum fill time, required fields), a reference is issued,
 * and the record goes to data/enquiries.log (and email once configured) through
 * enquiry_deliver(). Prescription uploads reuse the order handler's checks:
 * the file's bytes decide its type, and files land in data/uploads, which
 * .htaccess never serves.
 *
 * Schema: name => [
 *   'label'    => string,
 *   'type'     => text|email|tel|date|select|radio|textarea|file|consent,
 *   'required' => bool,
 *   'help'     => string   helper text shown under the label
 *   'options'  => [value => label]   select and radio
 *   'html'     => string   consent: trusted label markup (may hold a link)
 * ]
 */
require_once INC . '/enquiry-handler.php';
require_once INC . '/order-handler.php';
require_once INC . '/sheets.php';
require_once INC . '/mailer.php';

/** What each form is called in its emails ("We have received your ..."). */
const GFORM_MAIL_NAMES = [
    'enquiry'          => 'Medicine enquiry',
    'doctor-enquiry'   => 'Doctor enquiry',
    'pharmacy-enquiry' => 'Pharmacy enquiry',
    'quotation'        => 'Quotation request',
    'general-enquiry'  => 'Enquiry',
    'complaint'        => 'Complaint',
    'side-effect'      => 'Side effect report',
];

const GFORM_MIN_SECONDS = 3;

/**
 * Handle a POST for this form. Returns the state gform_render() needs:
 *   ['sent' => bool, 'ref' => string, 'errors' => [], 'values' => [], 'started' => int]
 * $prefill: initial values for a first view (for example the enquiry type
 * taken from ?type= in the URL).
 */
function gform_run(string $kind, array $schema, array $prefill = []): array
{
    $state = ['sent' => false, 'ref' => '', 'errors' => [], 'values' => $prefill, 'started' => time()];
    if (is_static_build()) {
        return $state;
    }
    csrf_token();

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || ($_POST['_form'] ?? '') !== $kind) {
        return $state;
    }

    $values = [];
    foreach ($schema as $name => $def) {
        if (($def['type'] ?? '') === 'file') {
            continue;
        }
        $raw = $_POST[$name] ?? '';
        $values[$name] = is_string($raw) ? trim(mb_substr($raw, 0, 4000)) : '';
    }
    $state['values']  = $values;
    $state['started'] = (int) ($_POST['started'] ?? time());

    if (!csrf_valid($_POST['_token'] ?? null)) {
        $state['errors']['_form'] = 'Your session timed out. Please send the form again.';
        return $state;
    }

    $errors = [];
    foreach ($schema as $name => $def) {
        $type = $def['type'] ?? 'text';
        if ($type === 'file') {
            continue;
        }
        $v = $values[$name] ?? '';
        if (!empty($def['required']) && $v === '') {
            $errors[$name] = $type === 'consent'
                ? 'Please tick this box so we can reply to you.'
                : 'Please fill in "' . rtrim((string) $def['label'], ' *') . '".';
            continue;
        }
        if ($v !== '' && $type === 'email' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $errors[$name] = 'That email address does not look complete.';
        }
        if ($v !== '' && in_array($type, ['select', 'radio'], true) && !isset($def['options'][$v])) {
            $errors[$name] = 'Please choose one of the options.';
        }
        if ($v !== '' && $type === 'tel' && !preg_match('/\d{5,}/', preg_replace('/\D+/', '', $v) ?? '')) {
            $errors[$name] = 'Please give a phone number we can call, with the country code.';
        }
    }

    $files = [];
    foreach ($schema as $name => $def) {
        if (($def['type'] ?? '') === 'file') {
            $files = order_validate_uploads($errors);
            if (isset($errors['prescription']) && $name !== 'prescription') {
                $errors[$name] = $errors['prescription'];
                unset($errors['prescription']);
            }
        }
    }

    // Bots fill the hidden field or submit instantly. Pretend success, keep nothing.
    $isBot = (string) ($_POST['website'] ?? '') !== ''
          || (time() - $state['started']) < GFORM_MIN_SECONDS;

    if ($errors) {
        $state['errors'] = $errors;
        return $state;
    }

    $state['ref']  = enquiry_reference();
    $state['sent'] = true;
    if ($isBot) {
        return $state;
    }

    $record = [];
    foreach ($schema as $name => $def) {
        if (($def['type'] ?? '') === 'file') {
            continue;
        }
        $v = $values[$name] ?? '';
        if (in_array($def['type'] ?? '', ['select', 'radio'], true) && isset($def['options'][$v])) {
            $v = $def['options'][$v];
        }
        if (($def['type'] ?? '') === 'consent') {
            $v = $v !== '' ? 'Yes' : '';
        }
        $record[$name] = $v;
    }
    $stored = order_store_uploads($files, $state['ref']);
    foreach ($stored as $i => $f) {
        $record['file' . ($i + 1)] = $f['path'] . ' (' . $f['orig'] . ')';
    }
    enquiry_deliver($kind, $state['ref'], $record);

    // The same row to the Google Sheet, headed by each field's label.
    $columns = ['Source' => 'Website form'];
    foreach ($schema as $name => $def) {
        if (($def['type'] ?? '') !== 'file' && array_key_exists($name, $record)) {
            $columns[rtrim((string) ($def['label'] ?? $name), ' *')] = $record[$name];
        }
    }
    $columns['Page'] = (string) strtok((string) ($_SERVER['REQUEST_URI'] ?? '/'), '?');
    $attach = [];
    foreach ($stored as $f) {
        $bytes = @file_get_contents(APP_ROOT . '/' . $f['path']);
        if ($bytes !== false) {
            $mime = function_exists('mime_content_type') ? (string) @mime_content_type(APP_ROOT . '/' . $f['path']) : '';
            $attach[] = ['name' => $f['orig'] !== '' ? $f['orig'] : basename($f['path']), 'mime' => $mime ?: 'application/octet-stream', 'data' => $bytes];
        }
    }
    sheets_append($kind, $state['ref'], $columns, $attach);

    // The emails (mailer.php, through Brevo): the team's copy with the files,
    // and a confirmation to the person when they gave an email address.
    $what = GFORM_MAIL_NAMES[$kind] ?? 'Enquiry';
    $who  = (string) ($record['name'] ?? $record['contact'] ?? '');
    $mail = (string) ($record['email'] ?? '');
    $rows = [];
    foreach ($columns as $label => $value) {
        $rows[] = [$label, $value];
    }
    if ($attach) {
        $rows[] = ['Files', implode(', ', array_column($attach, 'name'))];
    }
    mailer_notify_team($state['ref'], $what, $rows, $attach, $mail !== '' ? ['email' => $mail, 'name' => $who] : []);
    if ($mail !== '') {
        mailer_confirm_customer($mail, $who, $state['ref'], $what);
    }

    return $state;
}

/**
 * Render the form, or the thank-you message once sent.
 * $o: submit (label), success (message), id (form id), intro (trusted HTML
 *     shown above the fields), warn (bool: warn before leaving a filled form)
 */
function gform_render(string $kind, array $schema, array $state, array $o = []): void
{
    $id = $o['id'] ?? 'form-' . $kind;
    if ($state['sent']) {
        ?>
        <div class="g-success" id="<?= e($id) ?>" role="status" tabindex="-1">
          <p><strong><?= e($o['success'] ?? 'Thank you. We have received your message.') ?></strong></p>
          <p class="g-mt-sm">Your reference is <strong><?= e($state['ref']) ?></strong>. Quote it if you call us on <a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a>.</p>
        </div>
        <?php
        return;
    }
    if (is_static_build()) {
        ?>
        <div class="g-box g-box--info" id="<?= e($id) ?>">
          <?= gi('info') ?>
          <div><p>This form is not available on this copy of the website. Please call <a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a>, email <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>, or message us on <a href="<?= e(whatsapp_url()) ?>">WhatsApp</a>.</p></div>
        </div>
        <?php
        return;
    }
    $errors = $state['errors'];
    $hasFile = false;
    foreach ($schema as $def) {
        if (($def['type'] ?? '') === 'file') { $hasFile = true; }
    }
    ?>
    <form class="g-form" id="<?= e($id) ?>" method="post" action="#<?= e($id) ?>" novalidate<?= $hasFile ? ' enctype="multipart/form-data"' : '' ?><?= !empty($o['warn']) ? ' data-warn' : '' ?>>
      <?= csrf_field() ?>
      <input type="hidden" name="_form" value="<?= e($kind) ?>">
      <input type="hidden" name="started" value="<?= (int) $state['started'] ?>">
      <div class="g-hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

      <?php if (!empty($o['intro'])): ?><p><?= $o['intro'] ?></p><?php endif; ?>

      <?php if ($errors): ?>
      <div class="g-errors" role="alert">
        <p><strong>Please check the form.</strong></p>
        <ul>
          <?php foreach ($errors as $field => $msg): ?>
          <li><?php if ($field !== '_form'): ?><a href="#f-<?= e($kind . '-' . $field) ?>"><?= e($msg) ?></a><?php else: ?><?= e($msg) ?><?php endif; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <?php foreach ($schema as $name => $def):
          $type  = $def['type'] ?? 'text';
          $fid   = 'f-' . $kind . '-' . $name;
          $val   = (string) ($state['values'][$name] ?? '');
          $req   = !empty($def['required']);
          $err   = $errors[$name] ?? '';
          $help  = (string) ($def['help'] ?? '');
          $descr = ($help !== '' ? $fid . '-h' : '') . ($err !== '' ? ' ' . $fid . '-e' : '');
          $aria  = trim($descr) !== '' ? ' aria-describedby="' . e(trim($descr)) . '"' : '';
          $reqA  = $req ? ' required aria-required="true"' : '';
          $label = e((string) $def['label']) . ($req ? ' <span class="g-req" aria-hidden="true">*</span>' : '');
          $ph    = isset($def['placeholder']) ? ' placeholder="' . e((string) $def['placeholder']) . '"' : '';
      ?>
      <div class="g-field<?= $err !== '' ? ' g-field--error' : '' ?>">
        <?php if ($type === 'radio'): ?>
        <fieldset>
          <legend id="<?= e($fid) ?>"><?= $label ?></legend>
          <?php if ($help !== ''): ?><span class="g-help" id="<?= e($fid) ?>-h"><?= e($help) ?></span><?php endif; ?>
          <div class="g-radios">
            <?php foreach ($def['options'] as $ov => $ol): ?>
            <label class="g-choice"><input type="radio" name="<?= e($name) ?>" value="<?= e((string) $ov) ?>"<?= $val === (string) $ov ? ' checked' : '' ?><?= $reqA ?>> <?= e($ol) ?></label>
            <?php endforeach; ?>
          </div>
        </fieldset>
        <?php elseif ($type === 'consent'): ?>
        <label class="g-choice" for="<?= e($fid) ?>">
          <input type="checkbox" id="<?= e($fid) ?>" name="<?= e($name) ?>" value="1"<?= $val !== '' ? ' checked' : '' ?><?= $reqA ?><?= $aria ?>>
          <span><?= $def['html'] ?? e((string) $def['label']) ?><?= $req ? ' <span class="g-req" aria-hidden="true">*</span>' : '' ?></span>
        </label>
        <?php else: ?>
        <label for="<?= e($fid) ?>"><?= $label ?></label>
        <?php if ($help !== ''): ?><span class="g-help" id="<?= e($fid) ?>-h"><?= e($help) ?></span><?php endif; ?>
        <?php if ($type === 'textarea'): ?>
        <textarea id="<?= e($fid) ?>" name="<?= e($name) ?>" rows="5"<?= $reqA ?><?= $aria ?><?= $ph ?>><?= e($val) ?></textarea>
        <?php elseif ($type === 'select'): ?>
        <select id="<?= e($fid) ?>" name="<?= e($name) ?>"<?= $reqA ?><?= $aria ?>>
          <option value="">Choose one</option>
          <?php foreach ($def['options'] as $ov => $ol): ?>
          <option value="<?= e((string) $ov) ?>"<?= $val === (string) $ov ? ' selected' : '' ?>><?= e($ol) ?></option>
          <?php endforeach; ?>
        </select>
        <?php elseif ($type === 'file'): ?>
        <div class="g-upzone">
          <input type="file" id="<?= e($fid) ?>" name="prescription[]" multiple accept=".jpg,.jpeg,.png,.pdf,.heic,.heif,.webp,image/*,application/pdf" data-thumbs="<?= e($fid) ?>-t"<?= $aria ?>>
          <span class="g-upzone__art" aria-hidden="true">
            <svg viewBox="0 0 100 76" width="100" height="76" fill="none">
              <rect x="32" y="6" width="34" height="46" rx="4" fill="#DCEEF8" transform="rotate(6 49 29)"/>
              <g class="g-upz-doc">
                <rect x="24" y="10" width="36" height="48" rx="4" fill="#fff" stroke="#0B2A5B" stroke-width="2"/>
                <text x="30" y="27" font-size="11" font-weight="700" fill="#1E9BD7">Rx</text>
                <path d="M31 35h22M31 41h22M31 47h13" stroke="#B9C7D3" stroke-width="2" stroke-linecap="round"/>
              </g>
              <circle class="g-upz-ring" cx="68" cy="53" r="16" stroke="#1E9BD7" stroke-width="1.5" stroke-dasharray="3 5"/>
              <circle cx="68" cy="53" r="12" fill="#1E9BD7"/>
              <g class="g-upz-arrow" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <path d="M68 59v-11"/>
                <path d="m63 52.5 5-5 5 5"/>
              </g>
            </svg>
          </span>
          <span class="g-upzone__txt"><strong>Choose a file</strong> or drag it here</span>
        </div>
        <div class="g-thumbs" id="<?= e($fid) ?>-t" aria-live="polite"></div>
        <?php else: ?>
        <input type="<?= e($type) ?>" id="<?= e($fid) ?>" name="<?= e($name) ?>" value="<?= e($val) ?>"<?= $reqA ?><?= $aria ?><?= $ph ?><?= $type === 'tel' ? ' autocomplete="tel" inputmode="tel"' : '' ?><?= $type === 'email' ? ' autocomplete="email"' : '' ?>>
        <?php endif; ?>
        <?php endif; ?>
        <?php if ($err !== ''): ?><span class="g-field__err" id="<?= e($fid) ?>-e"><?= e($err) ?></span><?php endif; ?>
      </div>
      <?php endforeach; ?>

      <div><button class="g-btn g-btn--primary" type="submit"><?= e($o['submit'] ?? 'Send') ?></button></div>
    </form>
    <?php
}
