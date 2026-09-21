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
  <h2 id="value">What that means for you</h2>
  <?php reason_grid([
      ['icon' => 'box', 'title' => 'Fewer stockouts',
       'text' => 'We keep a wide sourcing network working in the background, so a course of '
               . 'treatment is less likely to stall halfway through because a delivery didn’t '
               . 'arrive.'],
      ['icon' => 'shield', 'title' => 'Someone accountable',
       'text' => 'A named, registered pharmacist checks every order — from the first phone call '
               . 'or enquiry through to the medicine actually in your hand.'],
      ['icon' => 'card', 'title' => 'A fair price, agreed first',
       'text' => 'We aim for the best price we can offer, and we always tell you the real figure '
               . 'before you decide anything. Nothing is ordered until you say yes.'],
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
  <h2 id="why-getmeds">Why families and hospitals choose us</h2>
  <?php reason_grid([
      ['icon' => 'vial', 'title' => 'Cancer care is what we know',
       'text' => 'It’s our main focus, not one line among many — so we understand what a delay or '
               . 'a shortage actually costs a patient.'],
      ['icon' => 'truck', 'title' => 'We chase down hard-to-find medicine',
       'text' => 'If something isn’t already here, we’ll tell you honestly whether — and how — we '
               . 'can still get it to you.'],
      ['icon' => 'check', 'title' => 'We do what we say',
       'text' => 'The price and the timeline we give you are the real ones, and we follow up '
               . 'rather than leaving you to chase us.'],
      ['icon' => 'person', 'title' => 'Your doctor stays in charge',
       'text' => 'We support the treatment your own doctor has already planned. We never '
               . 'second-guess it, and we never will.'],
      ['icon' => 'ship', 'title' => 'Built for the Pacific, not just Vanuatu',
       'text' => 'A supply line that only works for one country breaks the moment something on '
               . 'that route goes wrong. We’re building more than one.'],
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