<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/provider-gate.php';
require_once INC . '/enquiry-handler.php';

// A form on this page needs CSRF, so the session must start before any output.
ensure_session();

$state  = enquiry_run('quote');
$step1  = $state['schema']['step1'];
$step2  = enquiry_step2_schema();

$page = [
    'title'   => $state['done'] ? 'Your quote request has been sent' : 'Request a quote',
    'desc'    => 'Request pricing from Getmeds Vanuatu for a tender, ward stock or a named patient.',
    'ref'     => 'GV-PRV-4',
    'noindex' => true,
    // The gate's continue button, or this page's form, is the primary action.
    'own_primary' => true,
];

include INC . '/head.php';
include INC . '/header.php';
provider_gate();
?>

<?php if ($state['done']): ?>

  <div class="section shell" style="margin-top:0">
    <div class="receipt">
      <h1>Your quote request has been sent</h1>
      <p class="quiet" style="margin-bottom:var(--s-2)">Your reference</p>
      <strong class="receipt__ref"><?= e($state['ref']) ?></strong>
      <p>
        Quote this reference in any correspondence about this request. A pharmacist will come
        back with pricing, availability and a realistic lead time, usually within one working
        day.
      </p>
      <div class="notice" style="margin-bottom:0">
        <p class="notice__head">Urgent clinical need</p>
        <p>Call <?= phone_link() ?> rather than waiting on a written quote.</p>
      </div>
    </div>
  </div>

<?php else: ?>

  <?php page_open(
      'Request a quote',
      'For a tender, recurring ward stock, or one named patient. Two steps.'
  ); ?>

  <div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">

    <?php enquiry_errors($state); ?>
    <?php enquiry_progress($state['step']); ?>

    <form class="form" method="post" action="<?= e(url('/providers/request-a-quote')) ?>" data-warn-unsaved>
      <?= csrf_field() ?>
      <input type="hidden" name="step" value="<?= (int) $state['step'] ?>">
      <input type="hidden" name="started" value="<?= (int) ($state['started'] ?? time()) ?>">

      <div class="u-hidden" aria-hidden="true">
        <label for="f-website">Website</label>
        <input type="text" id="f-website" name="website" tabindex="-1" autocomplete="off">
      </div>

      <?php if ($state['step'] === 1): ?>
        <fieldset class="fieldset">
          <legend>What you need priced</legend>
          <?php foreach ($step1 as $name => $def) { enquiry_field($name, $def, $state); } ?>
        </fieldset>
        <div class="actions" style="margin-top:0">
          <button class="btn btn--primary" type="submit">Continue</button>
        </div>
      <?php else: ?>
        <fieldset class="fieldset">
          <legend>How we reach you</legend>
          <?php foreach ($step2 as $name => $def) { enquiry_field($name, $def, $state); } ?>
        </fieldset>
        <?php enquiry_carry($step1, $state); ?>
        <div class="actions" style="margin-top:0">
          <button class="btn btn--primary" type="submit">Send this request</button>
        </div>
      <?php endif; ?>
    </form>

    <div class="notice" style="max-width:34rem">
      <p class="notice__head">What makes a quote faster</p>
      <p>
        Send the protocol rather than a single line where you can, and say whether you need a
        delivered price into another country. Both change the figure, and asking afterwards
        adds a day.
      </p>
    </div>
  </div>

<?php endif; ?>

<?php include INC . '/footer.php'; ?>
