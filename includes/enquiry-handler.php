<?php
/**
 * Enquiry and quote handling.
 *
 * Design notes worth knowing before editing:
 *
 * - Progressive disclosure is done on the SERVER, as two ordinary page loads.
 *   JavaScript is not required to complete this form, and there is no client-side
 *   step machine to get out of sync. Two small page loads beat one fragile one on
 *   a metered 3G connection.
 *
 * - At most five fields are visible at once, and the first step asks about the
 *   medicine rather than about the person. Somebody who is not ready to hand over
 *   their phone number can still see what the form wants.
 *
 * - Safe defaults are pre-selected: country is Vanuatu, contact method is phone.
 *
 * - Spam is caught with a honeypot field and a minimum completion time, not a
 *   CAPTCHA. Asking a patient on chemotherapy to solve a puzzle is not acceptable.
 *
 * - Until 'enquiry_mail_enabled' is true in config.php, submissions are appended
 *   to data/enquiries.log and the confirmation is still shown with a real
 *   reference. Nothing is silently dropped.
 */

const ENQ_MIN_SECONDS = 3;

/** The two form variants. */
function enquiry_schema(string $kind): array
{
    if ($kind === 'quote') {
        return [
            'label'    => 'quote request',
            'step1' => [
                'who'      => ['label' => 'Who is this for?', 'type' => 'radio', 'required' => true,
                    'options' => [
                        'hospital'   => 'A hospital or clinic',
                        'ministry'   => 'A ministry or government body',
                        'ngo'        => 'An NGO or aid programme',
                        'named'      => 'One named patient',
                    ]],
                'medicine' => ['label' => 'Medicine or medicines', 'type' => 'text', 'required' => true,
                    'hint' => 'Molecule, presentation and strength. One per line is fine.',
                    'multiline' => true],
                'quantity' => ['label' => 'Quantity or number of cycles', 'type' => 'text', 'required' => false,
                    'hint' => 'An estimate is fine. It changes the price, so a rough figure helps.'],
                'needed_by' => ['label' => 'Needed by', 'type' => 'text', 'required' => false,
                    'hint' => 'A date, or the treatment start date you are planning around.'],
            ],
        ];
    }

    return [
        'label'    => 'enquiry',
        'step1' => [
            'who'      => ['label' => 'Who are you?', 'type' => 'radio', 'required' => true,
                'options' => [
                    'patient'  => 'A patient',
                    'family'   => 'A family member or carer',
                    'provider' => 'A doctor, nurse or pharmacist',
                    'other'    => 'Someone else',
                ]],
            'medicine' => ['label' => 'Medicine name', 'type' => 'text', 'required' => true,
                'hint' => 'As written on the prescription. If you are not sure you have read it '
                        . 'correctly, put what you can see.'],
            'need'     => ['label' => 'What do you need to know?', 'type' => 'radio', 'required' => true,
                'options' => [
                    'available' => 'Whether you can supply it',
                    'price'     => 'What it will cost',
                    'order'     => 'How to order it',
                    'other'     => 'Something else',
                ]],
            'notes'    => ['label' => 'Anything else we should know', 'type' => 'textarea', 'required' => false,
                'hint' => 'Optional. Do not send your full medical history — a pharmacist will '
                        . 'ask what they need on the phone.'],
        ],
    ];
}

/** Step 2 is the same for both variants: how to reach you. */
function enquiry_step2_schema(): array
{
    return [
        'name'    => ['label' => 'Your name', 'type' => 'text', 'required' => true],
        'phone'   => ['label' => 'Phone number', 'type' => 'tel', 'required' => true,
            'hint' => 'A pharmacist will almost always ring you. It is faster than typing.'],
        'email'   => ['label' => 'Email address', 'type' => 'email', 'required' => false,
            'hint' => 'Optional, unless you would rather we wrote to you.'],
        'contact' => ['label' => 'Best way to reach you', 'type' => 'radio', 'required' => true,
            'default' => 'phone',
            'options' => ['phone' => 'Phone call', 'email' => 'Email']],
        'place'   => ['label' => 'Where are you?', 'type' => 'select', 'required' => true,
            'default' => 'Vanuatu',
            'options' => [
                'Vanuatu'          => 'Vanuatu',
                'Fiji'             => 'Fiji',
                'Solomon Islands'  => 'Solomon Islands',
                'New Caledonia'    => 'New Caledonia',
                'Elsewhere'        => 'Somewhere else',
            ]],
    ];
}

function enq_str($v): string
{
    return is_string($v) ? trim($v) : '';
}

/**
 * Runs the form. Returns the state the page should render.
 */
function enquiry_run(string $kind = 'enquiry'): array
{
    $schema  = enquiry_schema($kind);
    $step1   = $schema['step1'];
    $step2   = enquiry_step2_schema();

    $state = [
        'kind'   => $kind,
        'schema' => $schema,
        'step'   => 1,
        'errors' => [],
        'values' => [],
        'done'   => false,
        'ref'    => null,
    ];

    // Pre-fill from a medicine lookup elsewhere on the site.
    if (isset($_GET['medicine'])) {
        $state['values']['medicine'] = enq_str($_GET['medicine']);
    }
    if (isset($_GET['from']) && strpos((string) $_GET['from'], 'providers') === 0) {
        $state['values']['who'] = 'provider';
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        $state['started'] = time();
        return $state;
    }

    // ---- POST ---------------------------------------------------------------

    if (!csrf_valid($_POST['_token'] ?? null)) {
        $state['errors']['_form'] = 'Your form session expired. Nothing was sent. '
                                  . 'Please check the details below and send it again.';
        $state['step'] = (int) ($_POST['step'] ?? 1) === 2 ? 2 : 1;
        $state['values'] = enquiry_collect($step1, $step2);
        $state['started'] = time();
        return $state;
    }

    $state['values']  = enquiry_collect($step1, $step2);
    $state['started'] = (int) ($_POST['started'] ?? time());
    $step             = (int) ($_POST['step'] ?? 1);

    if ($step === 1) {
        $state['errors'] = enquiry_validate($step1, $state['values']);
        $state['step']   = $state['errors'] ? 1 : 2;
        return $state;
    }

    // Final step.
    $errors = enquiry_validate($step1, $state['values'])
            + enquiry_validate($step2, $state['values']);

    // If step one no longer validates, send them back to it rather than losing it.
    foreach (array_keys($step1) as $k) {
        if (isset($errors[$k])) {
            $state['step']   = 1;
            $state['errors'] = $errors;
            return $state;
        }
    }

    // An email address is required when email is the chosen contact method.
    if (($state['values']['contact'] ?? '') === 'email' && ($state['values']['email'] ?? '') === '') {
        $errors['email'] = 'Add an email address, or choose a phone call instead.';
    }

    if ($errors) {
        $state['step']   = 2;
        $state['errors'] = $errors;
        return $state;
    }

    // Silent spam checks. A real person who filled five fields took longer than this.
    $isBot = enq_str($_POST['website'] ?? '') !== ''
          || (time() - $state['started']) < ENQ_MIN_SECONDS;

    $state['ref'] = enquiry_reference();

    if (!$isBot) {
        enquiry_deliver($kind, $state['ref'], $state['values']);
    }

    $state['done'] = true;
    $state['step'] = 3;
    return $state;
}

function enquiry_collect(array $step1, array $step2): array
{
    $out = [];
    foreach (array_merge($step1, $step2) as $name => $def) {
        $out[$name] = enq_str($_POST[$name] ?? '');
    }
    return $out;
}

function enquiry_validate(array $fields, array $values): array
{
    $errors = [];

    foreach ($fields as $name => $def) {
        $v = $values[$name] ?? '';

        if (!empty($def['required']) && $v === '') {
            $errors[$name] = enquiry_required_message($name, $def);
            continue;
        }
        if ($v === '') {
            continue;
        }

        if ($def['type'] === 'radio' || $def['type'] === 'select') {
            if (!isset($def['options'][$v])) {
                $errors[$name] = 'Choose one of the options listed.';
            }
        }
        if ($name === 'email' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $errors[$name] = 'That email address is missing something. Check for a typo, '
                           . 'or leave it blank and we will phone you.';
        }
        if ($name === 'phone') {
            $digits = preg_replace('/\D+/', '', $v);
            if (strlen((string) $digits) < 5) {
                $errors[$name] = 'Add a phone number we can reach you on, including the '
                               . 'country code if you are outside Vanuatu.';
            }
        }
        if (mb_strlen($v) > 4000) {
            $errors[$name] = 'That is longer than this form can send. Shorten it, or call '
                           . 'the pharmacy and tell a pharmacist instead.';
        }
    }

    return $errors;
}

/** Errors say what to do, not that something is invalid. */
function enquiry_required_message(string $name, array $def): string
{
    switch ($name) {
        case 'medicine': return 'Type the medicine name from your prescription. Anything you '
                              . 'can read is enough to start.';
        case 'name':     return 'Add your name so a pharmacist knows who they are calling.';
        case 'phone':    return 'Add a phone number. This is how a pharmacist will reach you.';
        case 'who':      return 'Choose the one that fits best. It decides who reads this.';
        case 'need':     return 'Choose what you need to know.';
        case 'contact':  return 'Choose whether you would rather we called or emailed.';
        case 'place':    return 'Choose where you are, so we can work out delivery.';
        default:         return 'This one is needed: ' . lcfirst($def['label']) . '.';
    }
}

/**
 * A short reference the person can quote on the phone. Readable aloud, and
 * recorded in the log beside the enquiry.
 *
 * Note: without a database this is not guaranteed unique. It is a conversation
 * handle, not a primary key, and the log records the full submission beside it.
 */
function enquiry_reference(): string
{
    return sprintf('%s-%s-%04d', cfg('enquiry_ref_prefix', 'GV'), date('Y'), random_int(0, 9999));
}

function enquiry_deliver(string $kind, string $ref, array $values): void
{
    $lines = ['Reference: ' . $ref, 'Type: ' . $kind, 'Received: ' . date('c'), ''];
    foreach ($values as $k => $v) {
        if ($v !== '') {
            $lines[] = str_pad($k . ':', 12) . str_replace(["\r\n", "\n"], ' / ', $v);
        }
    }
    $body = implode("\n", $lines) . "\n";

    if (cfg('enquiry_mail_enabled')) {
        $subject = sprintf('[%s] %s %s', $ref, ucfirst($kind), '- Getmeds Vanuatu website');
        $headers = 'From: Getmeds Vanuatu website <no-reply@' . enquiry_host() . ">\r\n"
                 . "Content-Type: text/plain; charset=UTF-8\r\n";
        @mail((string) cfg('enquiry_recipient'), $subject, $body, $headers);
    }

    // Always write the log, whether or not mail is configured, so nothing is lost.
    $dir = APP_ROOT . '/data';
    if (is_dir($dir) && is_writable($dir)) {
        @file_put_contents($dir . '/enquiries.log', $body . "----\n", FILE_APPEND | LOCK_EX);
    }
}

function enquiry_host(): string
{
    $h = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return preg_replace('/[^A-Za-z0-9.\-]/', '', $h) ?: 'localhost';
}

// -------------------------------------------------------------------------
// Rendering
// -------------------------------------------------------------------------

/** The error summary at the top of the form. */
function enquiry_errors(array $state): void
{
    if (!$state['errors']) { return; }
    require_once INC . '/icons.php';
    ?>
    <div class="errors" role="alert" aria-labelledby="errors-head">
      <h2 id="errors-head">Nothing has been sent yet</h2>
      <?php if (isset($state['errors']['_form'])): ?>
        <p><?= e($state['errors']['_form']) ?></p>
      <?php endif; ?>
      <?php
      $field = array_filter($state['errors'], fn($k) => $k !== '_form', ARRAY_FILTER_USE_KEY);
      if ($field): ?>
      <p>Fix these <?= count($field) === 1 ? 'details' : 'details' ?> and send it again:</p>
      <ul>
        <?php foreach ($field as $k => $msg): ?>
        <li><a href="#f-<?= e($k) ?>"><?= e($msg) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
    <?php
}

/** One field, with a persistent visible label and an inline error. */
function enquiry_field(string $name, array $def, array $state): void
{
    $v     = $state['values'][$name] ?? ($def['default'] ?? '');
    $err   = $state['errors'][$name] ?? null;
    $id    = 'f-' . $name;
    $hintId = 'h-' . $name;
    $errId  = 'e-' . $name;
    $describedBy = [];
    if (!empty($def['hint'])) { $describedBy[] = $hintId; }
    if ($err) { $describedBy[] = $errId; }
    $db = $describedBy ? ' aria-describedby="' . implode(' ', $describedBy) . '"' : '';
    $req = !empty($def['required']) ? ' required' : '';
    require_once INC . '/icons.php';
    ?>
    <div class="field<?= $err ? ' is-error' : '' ?>">

      <?php if ($def['type'] === 'radio'): ?>
        <fieldset class="choices" <?= $err ? 'aria-invalid="true"' : '' ?>>
          <legend class="field__label"><?= e($def['label']) ?><?php if (empty($def['required'])): ?> <span class="field__optional">(optional)</span><?php endif; ?></legend>
          <?php if (!empty($def['hint'])): ?><span class="field__hint" id="<?= $hintId ?>"><?= e($def['hint']) ?></span><?php endif; ?>
          <?php $first = true; foreach ($def['options'] as $ov => $ol): ?>
          <label class="choice">
            <input type="radio" name="<?= e($name) ?>" value="<?= e($ov) ?>"
                   <?= ((string) $v === (string) $ov) ? 'checked' : '' ?>
                   <?= $first ? 'id="' . $id . '"' : '' ?>>
            <span><?= e($ol) ?></span>
          </label>
          <?php $first = false; endforeach; ?>
        </fieldset>

      <?php elseif ($def['type'] === 'select'): ?>
        <label for="<?= $id ?>"><?= e($def['label']) ?></label>
        <?php if (!empty($def['hint'])): ?><span class="field__hint" id="<?= $hintId ?>"><?= e($def['hint']) ?></span><?php endif; ?>
        <select id="<?= $id ?>" name="<?= e($name) ?>"<?= $db . $req ?>>
          <?php foreach ($def['options'] as $ov => $ol): ?>
          <option value="<?= e($ov) ?>" <?= ((string) $v === (string) $ov) ? 'selected' : '' ?>><?= e($ol) ?></option>
          <?php endforeach; ?>
        </select>

      <?php elseif ($def['type'] === 'textarea' || !empty($def['multiline'])): ?>
        <label for="<?= $id ?>"><?= e($def['label']) ?><?php if (empty($def['required'])): ?> <span class="field__optional">(optional)</span><?php endif; ?></label>
        <?php if (!empty($def['hint'])): ?><span class="field__hint" id="<?= $hintId ?>"><?= e($def['hint']) ?></span><?php endif; ?>
        <textarea id="<?= $id ?>" name="<?= e($name) ?>"<?= $db . $req ?>><?= e((string) $v) ?></textarea>

      <?php else: ?>
        <label for="<?= $id ?>"><?= e($def['label']) ?><?php if (empty($def['required'])): ?> <span class="field__optional">(optional)</span><?php endif; ?></label>
        <?php if (!empty($def['hint'])): ?><span class="field__hint" id="<?= $hintId ?>"><?= e($def['hint']) ?></span><?php endif; ?>
        <input type="<?= e($def['type']) ?>" id="<?= $id ?>" name="<?= e($name) ?>"
               value="<?= e((string) $v) ?>"
               <?= $name === 'name' ? 'autocomplete="name"' : '' ?>
               <?= $name === 'phone' ? 'autocomplete="tel" inputmode="tel"' : '' ?>
               <?= $name === 'email' ? 'autocomplete="email" inputmode="email" spellcheck="false"' : '' ?>
               <?= $db . $req ?>>
      <?php endif; ?>

      <?php if ($err): ?>
      <p class="field__error" id="<?= $errId ?>"><?= icon('alert') ?><span><?= e($err) ?></span></p>
      <?php endif; ?>
    </div>
    <?php
}

/** Carries earlier answers across the step without showing them again. */
function enquiry_carry(array $fields, array $state): void
{
    foreach (array_keys($fields) as $name) {
        $v = $state['values'][$name] ?? '';
        if ($v !== '') {
            echo '<input type="hidden" name="' . e($name) . '" value="' . e((string) $v) . '">' . "\n";
        }
    }
}

function enquiry_progress(int $step): void
{
    ?>
    <div class="progress">
      <p class="progress__label">Step <span class="num"><?= $step ?></span> of <span class="num">2</span></p>
      <div class="progress__track">
        <span class="progress__seg <?= $step > 1 ? 'is-done' : 'is-now' ?>"></span>
        <span class="progress__seg <?= $step > 1 ? 'is-now' : '' ?>"></span>
      </div>
    </div>
    <?php
}
