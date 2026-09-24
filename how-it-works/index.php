<?php
/**
 * /how-it-works — one page replacing the whole /patients section.
 *
 * It used to be seven pages: a landing page, the six steps, what you need,
 * prices, delivery, named patient access, and talk to a pharmacist. A patient
 * trying to work out how to order had to read all of them and hold it in their
 * head. The facts are unchanged; the essays around them are gone, and every
 * section ends pointing at /order rather than at another page of reading.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/icons.php';

$page = [
    'title' => 'How it works',
    'desc'  => 'Send your prescription, a pharmacist checks it and calls you with the price, '
             . 'then you collect it in Port Vila or we send it to your island.',
    'ref'   => 'GV-HOW',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'How it works',
    'Four steps. Nothing is ordered and nothing is charged until you say yes.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <?php step_cards([
      ['icon' => 'script', 'title' => 'Send the prescription',
       'text' => 'A photo taken on your phone is enough. Upload it, WhatsApp it, or bring the paper in.'],
      ['icon' => 'check', 'title' => 'A pharmacist checks it',
       'text' => 'They confirm what the medicine is, whether we hold it, and what it takes to get it.'],
      ['icon' => 'card', 'title' => 'You get the price',
       'text' => 'A real figure for your full course, usually the same working day. You decide then.'],
      ['icon' => 'box', 'title' => 'Collect it, or we send it',
       'text' => 'Collect in Port Vila, or we freight it to your island in the right conditions.'],
  ]); ?>

  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/order')) ?>">Order a Medicine</a>
    <a class="btn btn--secondary" href="tel:<?= e(cfg('phone_href')) ?>">Call <?= e(cfg('phone')) ?></a>
  </div>
</div>

<section class="section shell" aria-labelledby="what-you-need">
  <h2 id="what-you-need">What to send, and what to bring</h2>

  <h3>To start</h3>
  <ul class="clauses">
    <li><strong>The medicine name</strong>, as written on the prescription. If you cannot read
        it, send the photo and a pharmacist will work it out.</li>
    <li><strong>A phone number.</strong> Almost every order ends in a phone call, because it
        is faster and clearer than typing.</li>
  </ul>

  <h3>To collect the medicine</h3>
  <ul class="clauses">
    <li><strong>The original prescription.</strong> A photo starts the order; we need the paper
        before we hand medicine over.</li>
    <li><strong>Photo identification.</strong> Passport, driver's licence or a Vanuatu ID card.</li>
    <li><strong>A letter from the patient</strong> if you are collecting for somebody else, plus
        your own ID. A message on a phone is fine if it names you.</li>
    <li><strong>Payment.</strong> A pharmacist will have told you the amount already, so there
        are no surprises at the counter.</li>
  </ul>

  <p class="small quiet">
    Helpful but not required: anything else you take, including herbal remedies, and any
    allergy you know about. Some of these interact with cancer medicine.
  </p>
</section>

<section class="section shell" aria-labelledby="prices">
  <h2 id="prices">What it costs</h2>
  <div class="prose">
    <p>
      We do not publish prices, because a number on a website would be wrong for almost
      everyone reading it. The cost depends on the exact medicine, the strength, your dose, how
      many cycles your doctor planned, and whether it has to be imported for you. Two people
      holding the same prescription can owe very different amounts.
    </p>
    <p>
      So you get a real figure for your prescription before you commit to anything, and we do
      not order until you agree to it.
    </p>
  </div>

  <ul class="clauses">
    <li><strong>Where you pay.</strong> At the counter in Port Vila, or by arrangement for an
        institution. There is no payment page on this website, by design.</li>
    <li><strong>When you pay.</strong> On collection — unless we imported it specially for you,
        and then we discuss a deposit first.</li>
    <li><strong>What you get.</strong> A receipt itemising the medicine, quantity and amount.
        Keep it if you are claiming from an insurer or employer.</li>
  </ul>

  <p class="small quiet">
    If the cost is the problem, say so. We cannot change what a medicine costs, but we can
    sometimes name a cheaper equivalent to take back to your doctor, split a long course into
    stages, or point you at the hospital pathway instead.
  </p>
</section>

<section class="section shell" aria-labelledby="delivery">
  <h2 id="delivery">Getting it to you</h2>

  <h3>Collecting in Port Vila</h3>
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
      <dt>How long at the counter</dt>
      <dd>Allow fifteen minutes. A pharmacist goes through the medicine with you rather than
          handing you a bag.</dd>
    </div>
  </dl>

  <h3>To the other islands</h3>
  <div class="prose">
    <p>
      We send to Santo, Tanna, Malekula and the outer islands. An ordinary tablet travels on
      the usual freight. Anything that has to stay between 2 and 8 degrees goes in a validated
      cold box on a specific flight, which limits the days it can leave.
    </p>
    <p>
      Tell us the island and the town when you order, and a pharmacist will give you the
      realistic arrival day rather than the best case.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="not-local">
  <h2 id="not-local">If the medicine is not available in Vanuatu</h2>
  <div class="prose">
    <p>
      We can often still source it for you individually, against your prescription — this is
      called Named Patient Access. The medicine is ordered for you by name rather than pulled
      from general stock. It is not a way around a doctor or a pharmacist, and the normal
      import and regulatory requirements still apply.
    </p>
    <p>
      Order the same way. A pharmacist will tell you whether it can be sourced and how long it
      will realistically take.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/order')) ?>">Order a Medicine</a>
    <a class="next-link" href="<?= e(url('/medicines')) ?>">What we supply</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
