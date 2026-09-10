<?php
/**
 * Ask About a Medicine. The site's one conversion, and the only thing the header
 * CTA has ever pointed at.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/enquiry-handler.php';

// A form on this page needs CSRF, so the session must start before any output.
ensure_session();

$state  = enquiry_run('enquiry');
$step1  = $state['schema']['step1'];
$step2  = enquiry_step2_schema();

$page = [
    'title' => $state['done'] ? 'Your enquiry has been sent' : 'Ask about a medicine',
    'desc'  => 'Ask Getmeds Vanuatu whether a medicine is available, what it costs, and how '
             . 'to order it. A pharmacist reads every enquiry.',
    'ref'   => 'GV-ENQ',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<?php if ($state['done']): ?>

  <div class="section shell" style="margin-top:0">
    <div class="receipt">
      <h1>Your enquiry has been sent</h1>
      <p class="quiet" style="margin-bottom:var(--s-2)">Your reference</p>
      <strong class="receipt__ref"><?= e($state['ref']) ?></strong>

      <p>
        Write this down or take a photograph of it. If you call the pharmacy, quote it and
        whoever answers will find your enquiry straight away.
      </p>

      <h2 style="font-size:var(--t-3);margin-top:var(--s-6)">What happens next</h2>
      <ol class="clauses" style="margin-bottom:0">
        <li>A pharmacist reads your enquiry. They read every one.</li>
        <li>They check whether the medicine can be supplied, and what it costs.</li>
        <li>They contact you the way you asked, usually within one working day.</li>
      </ol>

      <div class="notice" style="margin-bottom:0">
        <p class="notice__head">If it is urgent, do not wait for us</p>
        <p>Call <?= phone_link() ?>. <?= e(cfg('hours_long')) ?></p>
      </div>
    </div>

    <p class="small quiet" style="margin-top:var(--s-6);max-width:34rem">
      Nothing has been ordered and nothing has been charged. An enquiry is a question, not a
      purchase, and a pharmacist will tell you the price before anything is ordered.
    </p>
  </div>

<?php else: ?>

  <?php page_open(
      'Ask about a medicine',
      'A pharmacist reads every enquiry and answers it. Nothing is ordered and nothing is '
      . 'charged by sending this.'
  ); ?>

  <div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">

    <?php enquiry_errors($state); ?>
    <?php enquiry_progress($state['step']); ?>

    <form class="form" method="post" action="<?= e(url('/enquire')) ?>" data-warn-unsaved>
      <?= csrf_field() ?>
      <input type="hidden" name="step" value="<?= (int) $state['step'] ?>">
      <input type="hidden" name="started" value="<?= (int) ($state['started'] ?? time()) ?>">

      <?php /* Honeypot. Hidden from sight and from screen readers, seen by bots. */ ?>
      <div class="u-hidden" aria-hidden="true">
        <label for="f-website">Website</label>
        <input type="text" id="f-website" name="website" tabindex="-1" autocomplete="off">
      </div>

      <?php if ($state['step'] === 1): ?>

        <fieldset class="fieldset">
          <legend>About the medicine</legend>
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
          <button class="btn btn--primary" type="submit">Send this enquiry</button>
        </div>

        <p class="small quiet" style="margin-top:var(--s-5)">
          Answering about the medicine already?
          <a href="<?= e(url('/enquire')) ?>">Start again</a>.
        </p>

      <?php endif; ?>
    </form>

    <div class="notice" style="max-width:34rem">
      <p class="notice__head">Would you rather talk to someone?</p>
      <p>
        Call <?= phone_link() ?>. <?= e(cfg('hours_long')) ?> Most people find the phone faster
        than this form, and a pharmacist can answer while you are on the line.
      </p>
    </div>

    <div class="prose" style="margin-top:var(--s-6)">
      <h2>What we do with what you send</h2>
      <p class="small">
        A pharmacist reads it, and it is used to answer your question. We do not need your
        medical history and you should not send it here. See
        <a href="<?= e(url('/privacy')) ?>">Privacy Policy</a>.
      </p>
    </div>
  </div>

<?php endif; ?>

<?php include INC . '/footer.php'; ?>
