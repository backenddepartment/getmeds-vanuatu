<?php
/**
 * Home. One decision, two routes.
 *
 * The hero is not a slogan, a photograph or a carousel. It is a single question
 * and two large doors, and everything downstream is filtered by which one the
 * visitor takes. This is where the design spends all of its boldness.
 */
require __DIR__ . '/includes/bootstrap.php';
require_once INC . '/icons.php';

$page = [
    'title' => 'Cancer medicines in Port Vila',
    'desc'  => 'Getmeds Vanuatu is a licensed specialty pharmacy in Port Vila supplying '
             . 'cancer medicines and the medicines that manage their side effects, against '
             . 'a valid prescription.',
    'ref'   => 'GV-HOME',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="entry shell" aria-labelledby="entry-question">
  <div class="entry__head">
    <div class="entry__words">
      <h1 class="entry__question" id="entry-question">Who is this for?</h1>
      <p class="entry__sub">Choose one and we will take you straight to what you need.</p>
      <?php /* The escape hatch sits with the question, not below the doors: it is
               help for the choice, and it is what fills this column beside the
               plate rather than leaving the page half empty. */ ?>
      <p class="doors-aside">
        Not sure which? Call the pharmacy on <?= phone_link('body-link') ?> and a
        pharmacist will point you the right way. <?= e(cfg('hours_short')) ?>.
      </p>
    </div>
    <?php /* The one plate above the fold, so it is the only one loaded eagerly. */
          plate('dispensary', [
            'class' => 'entry__plate',
            'pos'   => '50% 34%',
            'sizes' => '(min-width: 60em) 30vw, (min-width: 40em) 46vw, 92vw',
            'cap'   => 'Cancer medicines are ordered per patient, not held on an open shelf.',
            'ref'   => 'Plate 1',
            'eager' => true,
          ]); ?>
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
