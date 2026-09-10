<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Delivery',
    'desc'  => 'Collecting medicine in Port Vila, delivery within Vanuatu, and sending '
             . 'cold-chain medicine to the outer islands.',
    'ref'   => 'GV-PAT-5',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Delivery',
    'Collection in Port Vila, delivery to the other islands, and what the cold chain means '
    . 'for both.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <h2 style="margin-top:0">Collecting in Port Vila</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Where</dt>
      <dd><?= e(cfg('address_line')) ?><br><?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></dd>
    </div>
    <div class="record__row">
      <dt>When</dt>
      <dd><?= e(cfg('hours_long')) ?></dd>
    </div>
    <div class="record__row">
      <dt>What to bring</dt>
      <dd>The original prescription and photo identification. See
          <a href="<?= e(url('/patients/what-you-need')) ?>">What You Need</a>.</dd>
    </div>
    <div class="record__row">
      <dt>How long it takes at the counter</dt>
      <dd>Allow fifteen minutes. A pharmacist goes through the medicine with you rather than
          handing you a bag.</dd>
    </div>
  </dl>
    </div>
    <div class="split__aside">
      <?php plate('wharf', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="outer">
  <h2 id="outer">Sending to the other islands</h2>
  <div class="prose">
    <p>
      We send medicine to Santo, Tanna, Malekula and the outer islands. How it travels depends
      on what it is. An ordinary tablet can go by the usual freight. Anything that has to stay
      between 2 and 8 degrees travels in a validated cold box on a specific flight, and that
      constrains which days it can leave.
    </p>
    <p>
      Tell us which island and which town when you enquire, and a pharmacist will tell you the
      realistic day it arrives rather than the best case.
    </p>
  </div>

  <dl class="record">
    <div class="record__row">
      <dt>Ordinary medicines</dt>
      <dd>Sent with regular freight. Timing follows the flight or boat schedule to your island.</dd>
    </div>
    <div class="record__row">
      <dt>Cold-chain medicines</dt>
      <dd>Validated cold box, monitored in transit, on a flight where someone can receive it at
          the other end. We will not send one into an unattended arrival.</dd>
    </div>
    <div class="record__row">
      <dt>Controlled pain medicines</dt>
      <dd>Extra documentation, and the person receiving must be identified in advance.</dd>
    </div>
    <div class="record__row">
      <dt>Cost</dt>
      <dd>Freight is charged at cost and quoted with the medicine, never added afterwards.</dd>
    </div>
  </dl>
</section>

<section class="section shell" aria-labelledby="arrives">
  <h2 id="arrives">When it arrives</h2>
  <div class="notice">
    <p class="notice__head">Check the medicine before you sign for it</p>
    <p>
      Open the box while the driver or agent is still there. Check the name on the label is
      yours and the medicine is the one you expected. If a cold-chain box arrives warm, or the
      monitor inside shows a breach, do not use the medicine and do not put it in a fridge.
      Call <?= phone_link() ?> straight away and we will replace it.
    </p>
  </div>
  <div class="prose">
    <p>
      A medicine that has been too warm can look completely normal and no longer work. That is
      why the monitor is in the box, and why we would rather replace a consignment than have
      you take a chance on it.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/shipping-rules')) ?>">Shipping and import rules</a>
  </div>
</section>

<?php
parallel_column('Collection and delivery, in short', [
    ['en' => 'You can collect medicine at the pharmacy in Port Vila, Monday to Friday.', 'bi' => null],
    ['en' => 'Bring the prescription paper and photo ID to collect.', 'bi' => null],
    ['en' => 'We send medicine to Santo, Tanna, Malekula and the outer islands.', 'bi' => null],
    ['en' => 'Cold medicine travels in a special box on certain flights only.', 'bi' => null],
    ['en' => 'Open the box and check the medicine before you sign for it.', 'bi' => null],
    ['en' => 'If a cold box arrives warm, do not use the medicine. Call us.', 'bi' => null],
], 'parallel-delivery');

include INC . '/footer.php';
