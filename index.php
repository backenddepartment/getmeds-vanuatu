<?php
/**
 * Home.
 *
 * Rewritten 2026-09-24. It used to open with a hero, a quick-link row, a
 * full-bleed essay on affordability, a second essay on why affordability comes
 * first, a photo band, "what we do", and finally "asking about a medicine" —
 * roughly nine hundred words before anyone found out where to send a
 * prescription. The owner's words: "it's like a presentation full of words."
 *
 * The order now is: what this is, how to order in three steps, where to go,
 * then the short factual record. Everything a patient must do sits above the
 * essays, and the essays are gone rather than moved further down.
 */
require __DIR__ . '/includes/bootstrap.php';
require_once INC . '/icons.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Getmeds Vanuatu',
    'desc'  => 'Send your prescription to a licensed pharmacy in Port Vila. A pharmacist '
             . 'calls you back with the price and how long it will take.',
    'ref'   => 'GV-HOME',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<?php page_hero([
    'image'   => ['base' => '/assets/img/homeherosection', 'widths' => [800, 1200], 'w' => 1200, 'h' => 628],
    'plain'   => true,
    'pos'     => '50% 50%',
    'kicker'  => 'Licensed pharmacy · ' . cfg('address_city') . ', ' . cfg('address_country'),
    'title'   => 'Affordable Medicines. Better Access. Stronger Cancer Care.',
    'lede'    => 'Getmeds Vanuatu-Pacific helps patients and healthcare providers access essential '
               . 'and cancer medicines through reliable sourcing, more affordable pricing, and a '
               . 'growing supply network across the Pacific.',
    'actions' => [
        ['label' => 'Order a Medicine', 'href' => url('/order'), 'icon' => 'script', 'fill' => true],
        ['label' => 'Call ' . cfg('phone'), 'href' => 'tel:' . cfg('phone_href'), 'icon' => 'phone'],
    ],
    // No facts row: the phone and hours are in the footer on every page.
    'facts'   => false,
]); ?>

<?php /* Three steps, immediately. The old page put this seven hundred words down. */ ?>
<section class="section section--open shell" aria-labelledby="how" style="padding-top:var(--s-7)">
  <h2 id="how" class="heading--blue">Ordering takes three steps</h2>
  <?php step_cards([
      ['icon' => 'script', 'title' => 'Send the prescription',
       'text' => 'Upload a photo from your phone, WhatsApp it, or bring the paper in.'],
      ['icon' => 'check', 'title' => 'A pharmacist checks it',
       'text' => 'They confirm what it is, whether we can supply it, and what it will cost.'],
      ['icon' => 'box', 'title' => 'You decide, then collect',
       'text' => 'Collect in Port Vila, or we send it to your island. Nothing is charged until you agree.'],
  ]); ?>

  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/order')) ?>">Order a Medicine</a>
    <a class="next-link" href="<?= e(url('/how-it-works')) ?>">What to send, and what it costs</a>
  </div>
</section>

<?php quick_cards([
    'id'     => 'quick-links',
    'title'  => 'Where to go',
    'action' => ['label' => 'Contact us', 'href' => url('/contact')],
    'items'  => [
        ['label' => 'Order a Medicine', 'url' => '/order', 'tone' => 'blue',
         'tag' => 'Start here', 'photo' => 'script', 'icon' => 'script',
         'blurb' => 'Send the prescription and get a real price back.',
         'cta' => 'Upload a prescription'],
        ['label' => 'Medicines We Supply', 'url' => '/medicines', 'tone' => 'sky',
         'tag' => 'What we handle', 'photo' => 'shelves', 'icon' => 'pill',
         'blurb' => 'Cancer medicines, side-effect medicines, and other specialty lines.',
         'cta' => 'See the groups'],
        ['label' => 'How It Works', 'url' => '/how-it-works', 'tone' => 'sky',
         'tag' => 'For patients', 'photo' => 'parcels', 'icon' => 'info',
         'blurb' => 'What to send, what it costs, and how it reaches your island.',
         'cta' => 'Read the steps'],
        ['label' => 'For Doctors & Hospitals', 'url' => '/providers', 'tone' => 'blue',
         'tag' => 'Institutional', 'photo' => 'ward', 'icon' => 'hosp',
         'blurb' => 'Scope, ordering on account, cold chain and quotes.',
         'cta' => 'Provider services'],
    ],
]); ?>

<?php band('island', [
  'id'     => 'reach',
  'kicker' => 'Where we deliver',
  'head'   => 'Eighty-three islands, and a cold chain that has to hold on every one.',
  'pos'    => '50% 42%',
  'body'   => 'Collection in Port Vila, delivery elsewhere in Vanuatu, and institutional '
            . 'supply across the Pacific. Medicine that has to stay between 2 and 8 degrees '
            . 'travels in a validated cold box, monitored and logged the whole way.',
]); ?>

<section class="section shell" aria-labelledby="what-we-do">
  <h2 id="what-we-do">What we are</h2>
  <div class="prose">
    <p class="lede">
      A licensed pharmacy in Port Vila, supplying cancer medicines against a prescription from
      the doctor treating you.
    </p>
    <p>
      We do not diagnose, treat or advise on the management of illness — that is your doctor's
      work. Ours is making sure the medicine they prescribed is real, was stored properly the
      whole way, and is here when it is needed.
    </p>
  </div>

  <dl class="record" style="margin-top:var(--s-6)">
    <div class="record__row">
      <dt><?= icon('script') ?>Prescription</dt>
      <dd>Required for every medicine we supply. No exceptions, and nothing is sold or paid
          for on this website.</dd>
    </div>
    <div class="record__row">
      <dt><?= icon('thermo') ?>Cold chain</dt>
      <dd>Held between 2 and 8&nbsp;degrees, monitored and logged from the supplier to the patient.</dd>
    </div>
    <div class="record__row">
      <dt><?= icon('card') ?>Prices</dt>
      <dd>Not published, because they depend on your medicine, dose and course. You get a real
          figure before you commit to anything.</dd>
    </div>
    <div class="record__row">
      <dt><?= icon('shield') ?>Licensed pharmacy</dt>
      <dd>Licensed in Vanuatu to dispense prescription medicines.
        <span class="record__sub"><a href="<?= e(url('/about') . '#licences') ?>">How to check our licence</a></span></dd>
    </div>
  </dl>

  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/order')) ?>">Order a Medicine</a>
    <a class="next-link" href="<?= e(url('/about')) ?>">About the pharmacy</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
