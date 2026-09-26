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
    foreach (order_store_uploads($files, $state['ref']) as $i => $f) {
        $record['file' . ($i + 1)] = $f['path'] . ' (' . $f['orig'] . ')';
    }
    enquiry_deliver($kind, $state['ref'], $record);

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
        <textarea id="<?= e($fid) ?>" name="<?= e($name) ?>" rows="5"<?= $reqA ?><?= $aria ?>><?= e($val) ?></textarea>
        <?php elseif ($type === 'select'): ?>
        <select id="<?= e($fid) ?>" name="<?= e($name) ?>"<?= $reqA ?><?= $aria ?>>
          <option value="">Choose one</option>
          <?php foreach ($def['options'] as $ov => $ol): ?>
          <option value="<?= e((string) $ov) ?>"<?= $val === (string) $ov ? ' selected' : '' ?>><?= e($ol) ?></option>
          <?php endforeach; ?>
        </select>
        <?php elseif ($type === 'file'): ?>
        <input type="file" id="<?= e($fid) ?>" name="prescription[]" multiple accept=".jpg,.jpeg,.png,.pdf,.heic,.heif,.webp,image/*,application/pdf" data-thumbs="<?= e($fid) ?>-t"<?= $aria ?>>
        <div class="g-thumbs" id="<?= e($fid) ?>-t" aria-live="polite"></div>
        <?php else: ?>
        <input type="<?= e($type) ?>" id="<?= e($fid) ?>" name="<?= e($name) ?>" value="<?= e($val) ?>"<?= $reqA ?><?= $aria ?><?= $type === 'tel' ? ' autocomplete="tel" inputmode="tel"' : '' ?><?= $type === 'email' ? ' autocomplete="email"' : '' ?>>
        <?php endif; ?>
        <?php endif; ?>
        <?php if ($err !== ''): ?><span class="g-field__err" id="<?= e($fid) ?>-e"><?= e($err) ?></span><?php endif; ?>
      </div>
      <?php endforeach; ?>

      <div><button class="g-btn g-btn--primary" type="submit"><?= e($o['submit'] ?? 'Send') ?></button></div>
    </form>
    <?php
}
