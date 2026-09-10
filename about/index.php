<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'About us',
    'desc'  => 'Getmeds Vanuatu is a licensed specialty oncology pharmacy in Port Vila, part '
             . 'of the Getmeds group.',
    'ref'   => 'GV-ABT',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'About us',
    'A licensed pharmacy in Port Vila, staffed by pharmacists, supplying cancer medicines '
    . 'against a prescription.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <?php chunk_links(nav_children('/about')); ?>
    </div>
    <div class="split__aside">
      <?php plate('shelves', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="record">
  <h2 id="record">The pharmacy, on the record</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Registered name</dt>
      <dd><?= val('legal_entity_name') ?></dd>
    </div>
    <div class="record__row">
      <dt>Trading as</dt>
      <dd><?= e(cfg('site_name')) ?> — <?= e(cfg('site_descriptor')) ?></dd>
    </div>
    <div class="record__row">
      <dt>Pharmacy licence</dt>
      <dd><span class="num"><?= val('pharmacy_licence_no') ?></span>
          <span class="record__sub"><a href="<?= e(url('/about/licences')) ?>">How to verify this</a></span></dd>
    </div>
    <div class="record__row">
      <dt>Responsible pharmacist</dt>
      <dd><?= val('pharmacist_name') ?>
          <span class="record__sub">Registration <span class="num"><?= val('pharmacist_reg_no') ?></span></span></dd>
    </div>
    <div class="record__row">
      <dt>Premises</dt>
      <dd><?= e(cfg('address_line')) ?><br><?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></dd>
    </div>
    <div class="record__row">
      <dt>Part of</dt>
      <dd><?= e(cfg('group_name')) ?>
          <span class="record__sub"><a href="<?= e(url('/about/part-of-getmeds')) ?>">What that means for supply</a></span></dd>
    </div>
  </dl>
</section>

<section class="section shell" aria-labelledby="why">
  <h2 id="why">Why this pharmacy exists</h2>
  <div class="prose">
    <p>
      Vanuatu is a small market a long way from the places cancer medicines are made. That
      combination has historically meant patients waiting, travelling to Australia or New
      Zealand for supply, or going without.
    </p>
    <p>
      A specialty pharmacy in Port Vila changes the arithmetic. Buying through a larger group
      makes small quantities viable, and holding the cold chain locally means a medicine that
      needs 2 to 8 degrees can reach a patient here without a five-thousand-kilometre gamble.
    </p>
    <p>
      That is the whole proposition, and it is deliberately narrow. We supply medicine. The
      treating doctor does the rest.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/about/who-we-are')) ?>">Who we are</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
