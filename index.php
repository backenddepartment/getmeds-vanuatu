<?php
/**
 * Home. The hero, the five things a visitor should understand in seconds, the
 * quick links, then one decision with two routes.
 *
 * Copy from the 2026 content brief: affordability, access and the Pacific
 * first, in the site's plain, careful voice. Under the quick links sits the
 * question and the two large doors, and everything downstream is filtered by
 * which one the visitor takes.
 */
require __DIR__ . '/includes/bootstrap.php';
require_once INC . '/icons.php';
require_once INC . '/components.php';

$page = [
    // The browser tab reads just "Getmeds Vanuatu" on the home page.
    'title' => 'Getmeds Vanuatu',
    'desc'  => 'Getmeds Vanuatu is a licensed specialty pharmacy in Port Vila supplying '
             . 'cancer medicines and the medicines that manage their side effects, against '
             . 'a valid prescription.',
    'ref'   => 'GV-HOME',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<?php page_hero([
    'photo'   => 'shelves',
    'pos'     => '50% 40%',
    'kicker'  => 'Licensed pharmacy · ' . cfg('address_city') . ', ' . cfg('address_country'),
    'title'   => 'Affordable Medicines. Better Access. Stronger Cancer Care.',
    'lede'    => 'Getmeds Vanuatu-Pacific helps patients and healthcare providers access essential '
               . 'and cancer medicines through reliable sourcing, more affordable pricing, and a '
               . 'growing supply network across the Pacific.',
    'sub'     => 'Making cancer medicines more accessible to patients in Vanuatu and across the Pacific.',
    'actions' => [
        ['label' => 'Find a Medicine', 'href' => url('/enquire'), 'icon' => 'search', 'fill' => true],
        ['label' => 'How It Works', 'href' => url('/patients/how-to-order')],
        ['label' => 'Contact Getmeds', 'href' => url('/contact')],
    ],
    'points'  => [
        ['script', 'Prescription required'],
        ['thermo', "Held at 2 to 8\u{00A0}°C"],
        ['ship',   'Delivery across Vanuatu'],
    ],
]); ?>

<?php /* The five things to understand in the first few seconds, as plain prose. */ ?>
<section class="section shell" aria-labelledby="intro-head" style="margin-top:var(--s-8);border-top:0;padding-top:0">
  <h2 id="intro-head" class="u-hidden">What Getmeds Vanuatu-Pacific does</h2>
  <div class="prose" style="max-width:62rem">
    <p class="lede" style="max-width:none">
      Getmeds Vanuatu-Pacific exists to make medicines — cancer medicines especially — more
      affordable and easier to reach.
    </p>
    <p>
      Cancer medicines are our primary specialisation, and we are working with Vanuatu’s
      Ministry of Health toward the country’s first dedicated chemotherapy pharmacy and
      treatment pathway, based at Vila Central Hospital. Where a medicine your doctor has
      prescribed isn’t already on our shelf, we can usually still source it for you
      individually — what’s called
      <a href="<?= e(url('/patients/named-patient-access')) ?>">Named Patient Access</a> —
      subject to your prescription and the normal import and regulatory requirements. And
      Vanuatu is only the start: we’re building sourcing and referral partnerships across the
      Pacific so patients aren’t left waiting on a single supply line.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="quick-links" style="margin-top:var(--s-7);border-top:0;padding-top:0">
  <h2 id="quick-links">Quick links</h2>
  <?php link_cards([
      ['icon' => 'search', 'label' => 'Find a Medicine', 'url' => '/enquire',
       'blurb' => 'Ask a pharmacist whether we can supply what’s been prescribed.'],
      ['icon' => 'script', 'label' => 'How It Works', 'url' => '/patients/how-to-order',
       'blurb' => 'The six steps from prescription to dispensing.'],
      ['icon' => 'box', 'label' => 'Named Patient Access', 'url' => '/patients/named-patient-access',
       'blurb' => 'For a medicine that isn’t available locally.'],
      ['icon' => 'hosp', 'label' => 'For Healthcare Providers', 'url' => '/providers',
       'blurb' => 'Sourcing, quotes, and institutional ordering.'],
  ]); ?>
</section>

<section class="entry shell" aria-labelledby="entry-question">
  <div class="entry__words">
    <h2 class="entry__question" id="entry-question">Who is this for?</h2>
    <p class="entry__sub">Choose one and we will take you straight to what you need.</p>
    <?php /* The escape hatch sits with the question, not below the doors: it is
             help for the choice. */ ?>
    <p class="doors-aside">
      Not sure which? Call the pharmacy on <?= phone_link('body-link') ?> and a
      pharmacist will point you the right way. <?= e(cfg('hours_short')) ?>.
    </p>
  </div>

  <div class="doors">
    <a class="door" href="<?= e(url('/patients')) ?>">
      <span class="door__label">A patient or family member</span>
      <span class="door__lines">
        <span class="door__line">How to order, and what to bring</span>
        <span class="door__line">Prices, payment, and delivery</span>
        <span class="door__line">How to reach a pharmacist</span>
      </span>
      <span class="door__rule" aria-hidden="true"></span>
    </a>

    <a class="door" href="<?= e(url('/providers')) ?>">
      <span class="door__label">A doctor, nurse or hospital</span>
      <span class="door__lines">
        <span class="door__line">What we stock, and lead times</span>
        <span class="door__line">Ordering for a ward or facility</span>
        <span class="door__line">Storage, handling, and quotes</span>
      </span>
      <span class="door__rule" aria-hidden="true"></span>
    </a>
  </div>

</section>

<section class="section shell" aria-labelledby="affordability">
  <h2 id="affordability">Why affordability comes first</h2>
  <div class="prose">
    <p>
      Cancer treatment is expensive almost everywhere, and Vanuatu has no local system set up
      to absorb that cost. A patient referred overseas for treatment — most often to India —
      can face costs estimated from around USD 8,000 into the tens of thousands of dollars,
      before counting a caretaker’s travel or the medicines themselves. Importing a single
      course of medicine individually is expensive for the same reason: there’s no volume, and
      freight into a Pacific archipelago isn’t cheap. The result is stockouts, long delays, and
      treatment that stops partway through — not because the medicine doesn’t exist, but
      because getting it to Vanuatu one patient at a time is hard.
    </p>
    <p>
      Getmeds Vanuatu-Pacific was set up to soften that specific problem: sourcing cancer and
      essential medicines at more competitive, reduced-cost terms, and building the local
      pathway so patients don’t have to solve it alone each time.
    </p>
  </div>
</section>

<?php band('island', [
  'id'     => 'reach',
  'kicker' => 'Where we deliver',
  'head'   => 'Eighty-three islands, and a cold chain that has to hold on every one.',
  'pos'    => '50% 42%',
  'body'   => 'Collection in Port Vila, delivery elsewhere in Vanuatu, and institutional '
            . 'supply across the Pacific. The medicine is held between 2 and 8 degrees, '
            . 'monitored and logged, from the supplier to the patient.',
]); ?>

<section class="section shell" aria-labelledby="what-we-do">
  <h2 id="what-we-do">What we do</h2>
  <div class="prose">
    <p class="lede">
      We are a licensed pharmacy in Port Vila. We supply cancer medicines, and the medicines
      that manage their side effects, against a valid prescription from a licensed doctor.
    </p>
    <p>
      We do not diagnose, treat or advise on the management of illness. That is the work of
      the doctor treating you. What we do is make sure the medicine they prescribe is real,
      has been stored properly the whole way, and is here when it is needed.
    </p>
  </div>

  <dl class="record" style="margin-top:var(--s-6)">
    <div class="record__row">
      <dt><?= icon('thermo') ?>Cold chain</dt>
      <dd>Held between 2 and 8&nbsp;degrees, monitored and logged from the supplier to the patient.</dd>
    </div>
    <div class="record__row">
      <dt><?= icon('script') ?>Prescription</dt>
      <dd>Required for every medicine we supply. No exceptions, and no medicine sold from this website.</dd>
    </div>
    <div class="record__row">
      <dt><?= icon('ship') ?>Where we deliver</dt>
      <dd>Collection in Port Vila, delivery elsewhere in Vanuatu, and institutional supply across the Pacific.</dd>
    </div>
    <div class="record__row">
      <dt><?= icon('shield') ?>Pharmacy licence</dt>
      <dd><span class="num"><?= val('pharmacy_licence_no') ?></span>
        <span class="record__sub"><a href="<?= e(url('/about/licences')) ?>">How to check our licence</a></span></dd>
    </div>
  </dl>
</section>

<section class="section shell" aria-labelledby="ask-about">
  <h2 id="ask-about">Asking about a medicine</h2>
  <div class="prose">
    <p>
      We do not publish a stock list or prices. Cancer medicines are ordered per patient,
      and the price depends on the medicine, the dose and the course your doctor has set.
      So the way to find out about a specific medicine is to ask, and a pharmacist will
      answer you.
    </p>
    <p>
      You do not need a prescription in your hand to ask. You do need one before we can
      dispense.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/patients/how-to-order')) ?>">Read how ordering works</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
