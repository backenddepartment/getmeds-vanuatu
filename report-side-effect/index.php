<?php
/**
 * Report a Side Effect.
 *
 * This page is a deliberate partial exception to the "legal copy required" rule.
 * The regulatory and reporting-obligation wording is left for a lawyer, as the
 * brief requires. The practical route — how to tell somebody, right now — is
 * written, because leaving a patient on a page that cannot tell them what to do
 * about a reaction would be actively harmful. That is a safety pathway, not a
 * legal document.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Report a side effect',
    'desc'  => 'How to report a side effect or a reaction to a medicine supplied by Getmeds '
             . 'Vanuatu.',
    'ref'   => 'GV-LEG-7',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Report a side effect',
    'Tell us, and tell the doctor treating you. Reporting a reaction is how the next patient '
    . 'gets a safer medicine.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="notice notice--safety">
    <p class="notice__head">If this is happening now and it is serious, stop reading</p>
    <p>
      Trouble breathing, swelling of the face, lips or throat, a widespread rash, chest pain,
      collapse, a temperature over 38&nbsp;degrees during chemotherapy, or bleeding that will
      not stop: go to the emergency department or contact your treating hospital immediately.
      Do not fill in a form and do not wait for a pharmacy to open.
    </p>
  </div>

  <h2>To report something that is not an emergency</h2>
  <dl class="record">
    <div class="record__row">
      <dt>By phone</dt>
      <dd><a href="tel:<?= e(cfg('phone_href')) ?>" class="num"><?= e(cfg('phone')) ?></a>
          <span class="record__sub"><?= e(cfg('hours_long')) ?> This is the fastest route.</span></dd>
    </div>
    <div class="record__row">
      <dt>By email</dt>
      <dd><a href="mailto:<?= e(cfg('email')) ?>?subject=Side%20effect%20report"><?= e(cfg('email')) ?></a>
          <span class="record__sub">Put "side effect" in the subject line.</span></dd>
    </div>
    <div class="record__row">
      <dt>In person</dt>
      <dd><?= e(cfg('address_line')) ?>, <?= e(cfg('address_city')) ?></dd>
    </div>
    <div class="record__row">
      <dt>Also tell</dt>
      <dd>The doctor treating you. They may need to change your treatment, and only they can.</dd>
    </div>
  </dl>

  <h2>What helps us most</h2>
  <ul class="clauses">
    <li>The name of the medicine, and the batch number if you still have the box.</li>
    <li>What happened, in your own words. You do not need medical language.</li>
    <li>When it started, and how long after taking the medicine.</li>
    <li>Whether it is still happening, and whether you have stopped the medicine.</li>
    <li>Anything else you were taking at the time, including herbal remedies.</li>
  </ul>

  <div class="notice">
    <p class="notice__head">Do not stop a cancer medicine on your own</p>
    <p>
      Unless the reaction is severe, do not stop taking a prescribed cancer medicine before
      speaking to your doctor. Stopping mid-course can matter as much as the side effect. Ring
      us on <?= phone_link() ?> and we will help you get hold of the right person quickly.
    </p>
  </div>
</div>

<section class="section shell" aria-labelledby="what-we-do-with-it">
  <h2 id="what-we-do-with-it">What we do with a report</h2>
  <div class="prose">
    <p>
      A pharmacist records it, checks whether the reaction is a known one for that medicine,
      and tells you what we know. Where a report suggests a problem with a specific batch, we
      quarantine our remaining stock of that batch while it is looked into.
    </p>
  </div>

  <div class="todo-block">
    <p class="todo-block__head">Regulatory reporting obligations required</p>
    <p class="todo-block__body">
      Which authority in Vanuatu adverse reactions must be reported to, the timeframe, the form
      or channel, and the obligations that fall on the pharmacy as opposed to the prescriber.
      Also whether reports are forwarded to the manufacturer and to any regional
      pharmacovigilance programme. To be confirmed by a lawyer qualified in Vanuatu together
      with the responsible pharmacist. Nothing has been assumed here.
    </p>
  </div>
</section>

<?php
parallel_column('Reporting a side effect, in short', [
    ['en' => 'If you cannot breathe, or your face is swelling, go to hospital now.', 'bi' => null],
    ['en' => 'For anything else, call ' . cfg('phone') . ' and tell us what happened.', 'bi' => null],
    ['en' => 'Also tell the doctor who is treating you.', 'bi' => null],
    ['en' => 'Keep the medicine box. The batch number on it helps us.', 'bi' => null],
    ['en' => 'Do not stop a cancer medicine on your own. Talk to your doctor first.', 'bi' => null],
], 'parallel-side-effect-report');

include INC . '/footer.php';
