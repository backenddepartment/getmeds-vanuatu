<?php
/**
 * How it works. The six-step journey from the 2026 content brief, from
 * prescription to dispensing. The URL stays /patients/how-to-order so every
 * existing link to it keeps working.
 */
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'How it works',
    'desc'  => 'The six steps to getting a medicine from Getmeds Vanuatu-Pacific in Port Vila, '
             . 'from prescription to dispensing.',
    'ref'   => 'GV-PAT-1',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'How it works',
    'Six steps, from your prescription to your medicine. Nothing is ordered and nothing is '
    . 'charged until you confirm.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <?php step_cards([
      ['icon' => 'script', 'title' => 'Prescription',
       'text' => 'Submit your prescription or treatment protocol, in person or by photo.'],
      ['icon' => 'search', 'title' => 'Medicine check',
       'text' => 'We check availability and, if needed, sourcing options.'],
      ['icon' => 'person', 'title' => 'Pharmacist review',
       'text' => 'The request is reviewed by a pharmacist or appropriate healthcare professional.'],
      ['icon' => 'card', 'title' => 'Quotation',
       'text' => 'You receive the price and supply information before deciding.'],
      ['icon' => 'check', 'title' => 'Confirmation',
       'text' => 'You confirm the order and any arrangements needed: collection, delivery, or '
               . 'hospital pick-up for injectables.'],
      ['icon' => 'box', 'title' => 'Supply and dispensing',
       'text' => 'The medicine is sourced, received, recorded and dispensed according to the usual '
               . 'procedure.'],
  ]); ?>

  <div class="notice">
    <p class="notice__head"><?= icon('check') ?>The label is read back before anything leaves the counter</p>
    <p>
      At dispensing, a pharmacist reads the label back against your prescription and goes
      through the medicine with you before you leave.
    </p>
  </div>
</div>

<section class="section shell" aria-labelledby="sending">
  <h2 id="sending">Sending your prescription</h2>
  <div class="prose">
    <p>
      A clear photograph of the prescription is enough to start. Email it to
      <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>, or use the
      <a href="<?= e(url('/enquire')) ?>">enquiry form</a>, or bring the paper to the pharmacy.
      You keep the original either way. The prescription must come from a doctor licensed to
      prescribe the medicine, and it must be current.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="how-long">
  <h2 id="how-long">How long it usually takes</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Already in the pharmacy</dt>
      <dd>Same day, once the prescription is checked.</dd>
    </div>
    <div class="record__row">
      <dt>Ordered into Port Vila</dt>
      <dd>Commonly a few days to two weeks, depending on the medicine and the flight schedule.</dd>
    </div>
    <div class="record__row">
      <dt>Imported specially for you</dt>
      <dd>Longer, and we will give you a real date rather than an optimistic one. See
          <a href="<?= e(url('/patients/named-patient-access')) ?>">Named Patient Access</a>.</dd>
    </div>
    <div class="record__row">
      <dt>Given at the hospital</dt>
      <dd>Your ward orders it. Ask your oncology nurse where it is up to.</dd>
    </div>
  </dl>
  <p class="small quiet" style="margin-top:var(--s-4)">
    These are typical, not promises. A pharmacist gives you the real figure for your
    medicine before you commit to anything.
  </p>
</section>

<section class="section shell" aria-labelledby="repeat">
  <h2 id="repeat">Repeat supplies</h2>
  <div class="prose">
    <p>
      If you take something every month, tell us at the first collection and we will keep it
      moving rather than waiting for you to run out. Ask us again when you have about three
      weeks left. That is the point at which we can almost always avoid a gap.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/patients/what-you-need')) ?>">See what you need to bring</a>
  </div>
</section>

<?php
parallel_column('The six steps, in short', [
    ['en' => 'Step one: bring or send us your prescription.', 'bi' => null],
    ['en' => 'Step two: we check whether we have the medicine, or can get it.', 'bi' => null],
    ['en' => 'Step three: a pharmacist checks the prescription.', 'bi' => null],
    ['en' => 'Step four: we tell you the price and how long it takes.', 'bi' => null],
    ['en' => 'Step five: you say yes, and choose collection or delivery.', 'bi' => null],
    ['en' => 'Step six: we get the medicine and give it to you.', 'bi' => null],
    ['en' => 'You pay nothing until you say yes.', 'bi' => null],
], 'parallel-order');

include INC . '/footer.php';
