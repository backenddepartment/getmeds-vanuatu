<?php
/**
 * About Getmeds Vanuatu-Pacific. Copy from the 2026 content brief: who we are,
 * what we supply, the three-part value proposition, and why Getmeds. The
 * record of licence and responsibility stays, because it is how a reader
 * checks that any of this is true.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'About us',
    'desc'  => 'Getmeds Vanuatu-Pacific is a licensed specialty pharmacy in Port Vila, focused on '
             . 'improving access to cancer and essential medicines in Vanuatu and the Pacific.',
    'ref'   => 'GV-ABT',
];

include INC . '/head.php';
include INC . '/header.php';

page_hero([
    'photo'   => 'drawer',
    'pos'     => '50% 50%',
    'kicker'  => 'About Getmeds Vanuatu-Pacific',
    'title'   => 'Improving access to medicines in Vanuatu and the Pacific.',
    'lede'    => 'Our primary specialisation is cancer medicines, supplied against a prescription '
               . 'from local stock or sourced individually for the patient who needs them.',
    'actions' => [
        ['label' => 'Who we are', 'href' => url('/about/who-we-are'), 'fill' => true],
        ['label' => 'Pacific Network', 'href' => url('/pacific-network')],
    ],
    'points'  => [
        ['shield', 'Licensed in Vanuatu'],
        ['person', 'Registered pharmacists'],
        ['pin',    'Golden Port, ' . cfg('address_city')],
    ],
]);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
      <div class="prose">
        <p class="lede" style="max-width:none">
          Getmeds Vanuatu-Pacific is focused on improving access to medicines in Vanuatu and
          the wider Pacific region.
        </p>
        <p>
          Our primary specialisation is cancer medicines. Our broader portfolio can also include
          essential medicines, medical supplies, medical consumables, medical devices and
          equipment, and other healthcare products, sourced and supplied according to what a
          prescriber or facility actually needs.
        </p>
      </div>

      <h2>What we supply</h2>
      <?php chunk_facts([
          ['name' => 'Cancer medicines',
           'desc' => 'Our core focus, both from local stock and sourced individually per patient.'],
          ['name' => 'Essential medicines',
           'desc' => 'Including over-the-counter items, antibiotics, and cardiometabolic medicines '
                   . 'for conditions such as hypertension, diabetes and cholesterol.'],
          ['name' => 'Medical supplies, devices and equipment',
           'desc' => 'Sourced and supplied according to what a prescriber or facility needs.'],
          ['name' => 'Basic monitoring services',
           'desc' => 'Such as blood pressure and blood sugar checks, where offered.'],
      ]); ?>
    </div>
    <div class="split__aside">
      <?php plate('shelves', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="value">
  <h2 id="value">Our value proposition</h2>
  <?php reason_grid([
      ['icon' => 'box', 'title' => 'Availability',
       'text' => 'A wide sourcing network, working to keep the medicines patients and providers need '
               . 'in reliable supply and to reduce the stockouts that interrupt treatment.'],
      ['icon' => 'shield', 'title' => 'Assurance',
       'text' => 'Consistent service, accurate orders, professional communication, and follow-up you '
               . 'can rely on — from the first enquiry through to dispensing.'],
      ['icon' => 'card', 'title' => 'Affordability and advantage',
       'text' => 'Reduced-cost and competitive pricing wherever we can offer it, flexible sourcing '
               . 'when a medicine isn’t already local, and a long-term rather than one-off '
               . 'relationship with the patients and providers we work with.'],
  ]); ?>

  <div class="prose" style="margin-top:var(--s-6)">
    <p>
      We do not diagnose, treat, or advise on the management of illness — that is the work of
      the doctor treating you. What we do is make sure the medicine that’s been prescribed is
      genuine, has been stored correctly the whole way, is priced as fairly as we can manage,
      and is here when it’s needed.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="why-getmeds">
  <h2 id="why-getmeds">Why Getmeds</h2>
  <?php reason_grid([
      ['icon' => 'card', 'title' => 'Affordable',
       'text' => 'We focus on reduced-cost and competitive medicine options wherever we can offer them.'],
      ['icon' => 'truck', 'title' => 'Accessible',
       'text' => 'We help connect patients with medicines that would otherwise be difficult to obtain locally.'],
      ['icon' => 'check', 'title' => 'Reliable',
       'text' => 'Dependable sourcing, accurate order handling, and follow-up you can count on.'],
      ['icon' => 'vial', 'title' => 'Cancer-focused',
       'text' => 'Cancer medicines are our primary specialisation, not an afterthought.'],
      ['icon' => 'person', 'title' => 'Patient-centred',
       'text' => 'Our goal is helping you continue the treatment your own doctor has prescribed — not '
               . 'replacing their judgement.'],
      ['icon' => 'ship', 'title' => 'Pacific-focused',
       'text' => 'We’re building access and distribution pathways across the wider region, not just '
               . 'one country.'],
  ]); ?>
</section>

<section class="section shell" aria-labelledby="more-about">
  <h2 id="more-about">More about us</h2>
  <?php chunk_links(nav_children('/about')); ?>
</section>

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

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/about/who-we-are')) ?>">Who we are</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
