<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'How to order',
    'desc'  => 'The four steps to getting cancer medicine from Getmeds Vanuatu in Port Vila, '
             . 'from prescription to collection.',
    'ref'   => 'GV-PAT-1',
];

include INC . '/head.php';
include INC . '/header.php';

page_open('How to order', 'Four steps. A pharmacist does the work in steps two and three.');
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <ol class="steps">
    <li>
      <div>
        <h2>Get a prescription from your doctor</h2>
        <p>
          We cannot start without one. The prescription must come from a doctor licensed to
          prescribe the medicine, and it must be current. If you are being treated at Vila
          Central Hospital or the private hospital, your oncology team writes it.
        </p>
      </div>
    </li>
    <li>
      <div>
        <h2>Send it to us, or bring it in</h2>
        <p>
          A clear photograph of the prescription is enough to start. Email it to
          <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>, or use the
          enquiry form, or bring the paper to the pharmacy. You keep the original either way.
        </p>
      </div>
    </li>
    <li>
      <div>
        <h2>We tell you the price and the time</h2>
        <p>
          A pharmacist checks the prescription, checks availability, and comes back to you
          with the cost and how soon we can have it. Usually within one working day. Nothing
          is ordered and nothing is charged until you say yes.
        </p>
      </div>
    </li>
    <li>
      <div>
        <h2>Collect it, or have it delivered</h2>
        <p>
          Come to the pharmacy and a pharmacist will go through the medicine with you before
          you leave. Or we deliver, in Port Vila and to the other islands. See
          <a href="<?= e(url('/patients/delivery')) ?>">Delivery</a>.
        </p>
      </div>
    </li>
  </ol>
    </div>
    <div class="split__aside">
      <?php plate('dispenser', [
        'ar'    => '4 / 5',
      ]); ?>
    </div>
  </div>
</div>

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
      <dd>Longer, and we will give you a real date rather than an optimistic one.</dd>
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
parallel_column('The four steps, in short', [
    ['en' => 'Step one: your doctor writes a prescription.', 'bi' => null],
    ['en' => 'Step two: send us a photo of it, or bring it in.', 'bi' => null],
    ['en' => 'Step three: we tell you the price and how long it takes.', 'bi' => null],
    ['en' => 'Step four: collect it, or we deliver it.', 'bi' => null],
    ['en' => 'You pay nothing until you say yes.', 'bi' => null],
    ['en' => 'If you take medicine every month, tell us when you have three weeks left.', 'bi' => null],
], 'parallel-order');

include INC . '/footer.php';
