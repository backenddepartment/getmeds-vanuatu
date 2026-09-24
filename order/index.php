<?php
/**
 * /order — the page this site existed without.
 *
 * One screen: what you need, the medicine, the prescription photo, who to call.
 * No preamble, no reassurance essay above the fold. The form is the first thing
 * under the heading, because somebody who arrived here already decided.
 *
 * On a static build there is no PHP to receive an upload, so the form is not
 * rendered at all and the three channels that genuinely work are offered
 * instead. Rendering a dead form and eating a prescription would be worse than
 * anything else this site could do.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/icons.php';

$static = is_static_build();

if (!$static) {
    require_once INC . '/enquiry-handler.php';   // enquiry_field() renders the controls
    require_once INC . '/order-handler.php';
    ensure_session();
    $state = order_run();
} else {
    $state = ['done' => false, 'errors' => [], 'values' => [], 'schema' => []];
}

$page = [
    'title' => 'Order a medicine',
    'desc'  => 'Send your prescription to Getmeds Vanuatu and a pharmacist will call you back '
             . 'with the price and how long it will take.',
    'ref'   => 'GV-ORDER',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<?php if (!empty($state['done'])): /* ---------- confirmation ---------- */ ?>

<?php page_open('Order received', 'A pharmacist will call you. Keep the reference below.'); ?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="notice notice--safety" style="max-width:34rem">
    <p class="notice__head"><?= icon('check') ?>Your reference is
      <span class="num"><?= e((string) $state['ref']) ?></span></p>
    <p>Write it down. Quote it if you call us.</p>
  </div>

  <?php if (!empty($state['files'])): ?>
  <p class="small quiet" style="margin-top:var(--s-4)">
    <?= count($state['files']) ?> prescription
    file<?= count($state['files']) === 1 ? '' : 's' ?> received.
  </p>
  <?php endif; ?>

  <h2>What happens next</h2>
  <ol class="clauses">
    <li>A pharmacist reads your order and checks the prescription.</li>
    <li>They call you with the price and how long it will take. Usually the same working day.</li>
    <li>Nothing is ordered and nothing is charged until you say yes on that call.</li>
  </ol>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/')) ?>">Back to the home page</a>
    <a class="next-link" href="<?= e(url('/how-it-works')) ?>">How ordering works</a>
  </div>
</div>

<?php else: /* ---------- the form, or the static fallback ---------- */ ?>

<?php page_open(
    'Order a medicine',
    'Send us the prescription. A pharmacist calls you back with the price and the wait.'
); ?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">

<?php if ($static): ?>

  <div class="notice" style="max-width:34rem">
    <p class="notice__head"><?= icon('alert') ?>Ordering on this copy of the site is by message</p>
    <p>
      Send the medicine name and a photo of your prescription to whichever of these you
      already use. A pharmacist answers the same way on all three.
    </p>
  </div>

  <ul class="channels" style="margin-top:var(--s-5);max-width:34rem">
    <li>
      <a class="channels__row" href="<?= e(whatsapp_url('Hello, I would like to order a medicine. I will send a photo of my prescription.')) ?>"
         target="_blank" rel="noopener">
        <?= icon('phone') ?>
        <span>
          <span class="channels__name">WhatsApp us the prescription</span>
          <span class="channels__val num"><?= e(cfg('phone')) ?></span>
          <span class="channels__sub">Attach the photo in the chat. Fastest way to order.</span>
        </span>
      </a>
    </li>
    <li>
      <a class="channels__row" href="tel:<?= e(cfg('phone_href')) ?>">
        <?= icon('phone') ?>
        <span>
          <span class="channels__name">Call the pharmacy</span>
          <span class="channels__val num"><?= e(cfg('phone')) ?></span>
          <span class="channels__sub"><?= e(cfg('hours_long')) ?></span>
        </span>
      </a>
    </li>
    <li>
      <a class="channels__row" href="mailto:<?= e(cfg('email')) ?>?subject=<?= rawurlencode('Medicine order') ?>">
        <?= icon('mail') ?>
        <span>
          <span class="channels__name">Email the prescription</span>
          <span class="channels__val"><?= e(cfg('email')) ?></span>
          <span class="channels__sub">Attach a photo or PDF. Answered within one working day.</span>
        </span>
      </a>
    </li>
  </ul>

<?php else: ?>

  <?php if (!empty($state['errors']['_form'])): ?>
  <div class="notice" style="max-width:34rem">
    <p class="notice__head"><?= icon('alert') ?>Nothing was sent</p>
    <p><?= e($state['errors']['_form']) ?></p>
  </div>
  <?php endif; ?>

  <form class="form" method="post" action="<?= e(url('/order')) ?>" enctype="multipart/form-data" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="started" value="<?= (int) $state['started'] ?>">

    <?php /* Honeypot. Hidden from people, irresistible to bots. */ ?>
    <div class="u-hidden" aria-hidden="true">
      <label for="f-website">Website</label>
      <input type="text" id="f-website" name="website" tabindex="-1" autocomplete="off">
    </div>

    <?php
    foreach (['need', 'medicine', 'quantity'] as $f) {
        enquiry_field($f, $state['schema'][$f], $state);
    }
    ?>

    <?php /* The upload. The reason this page exists, so it gets a label that
             says what to do rather than what the control is. */ ?>
    <div class="field<?= isset($state['errors']['prescription']) ? ' is-error' : '' ?>">
      <label for="f-prescription">Upload your prescription
        <span class="field__optional">(optional)</span></label>
      <span class="field__hint" id="h-prescription">
        A photo taken on your phone is fine. JPG, PNG, HEIC or PDF, up to
        <?= (int) cfg('upload_max_mb') ?> MB each, <?= (int) cfg('upload_max_files') ?> files at most.
        No prescription to hand? Send it later — a pharmacist will ask on the call.
      </span>
      <input type="file" id="f-prescription" name="prescription[]" multiple
             accept="image/jpeg,image/png,image/webp,image/heic,image/heif,.heic,.heif,application/pdf"
             aria-describedby="h-prescription<?= isset($state['errors']['prescription']) ? ' e-prescription' : '' ?>">
      <?php if (isset($state['errors']['prescription'])): ?>
      <p class="field__error" id="e-prescription"><?= icon('alert') ?><span><?= e($state['errors']['prescription']) ?></span></p>
      <?php endif; ?>
    </div>

    <?php
    foreach (['name', 'phone', 'email', 'place'] as $f) {
        enquiry_field($f, $state['schema'][$f], $state);
    }
    ?>

    <button class="btn btn--primary btn--block" type="submit">Send my order</button>

    <p class="small quiet" style="margin-top:var(--s-4)">
      Nothing is ordered and nothing is charged by sending this. A pharmacist reads it, calls
      you with the price, and you decide then.
      <a href="<?= e(url('/policies#privacy')) ?>">What we do with it</a>.
    </p>
  </form>

<?php endif; ?>

</div>

<?php endif; ?>

<?php include INC . '/footer.php'; ?>
