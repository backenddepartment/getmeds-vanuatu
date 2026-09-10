<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Prices and payment',
    'desc'  => 'Why Getmeds Vanuatu does not publish medicine prices, how to get a figure for '
             . 'your medicine, and how payment works.',
    'ref'   => 'GV-PAT-3',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Prices and payment',
    'We do not publish prices, and this page explains why rather than hiding it.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="prose">
    <p>
      A price on a website would be wrong for most people who read it. The cost of a cancer
      medicine depends on the exact medicine, the strength, your dose, how many cycles your
      doctor has planned, and whether it has to be imported for you. Two people holding
      prescriptions for the same medicine can owe very different amounts.
    </p>
    <p>
      So instead of a number that would mislead you, we give you a real figure for your
      prescription, before you commit to anything, and we do not order until you agree to it.
    </p>
  </div>

  <h2>How to get a figure</h2>
  <ol class="clauses">
    <li>Send us the medicine name and strength from your prescription, or a photograph of it.</li>
    <li>A pharmacist checks it and works out the cost of the full course, not just one box.</li>
    <li>We tell you the amount and how long supply takes. Usually within one working day.</li>
    <li>You decide. If it is more than you expected, say so — sometimes there is a different
        option your doctor would accept, and it is worth asking them.</li>
  </ol>
</div>

<section class="section shell" aria-labelledby="paying">
  <h2 id="paying">Paying</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Where you pay</dt>
      <dd>At the pharmacy counter in Port Vila, or by arrangement for an institution. There is
          no payment page on this website, by design.</dd>
    </div>
    <div class="record__row">
      <dt>When you pay</dt>
      <dd>When you collect, unless we have had to import the medicine specially for you, in
          which case we will discuss a deposit with you first.</dd>
    </div>
    <div class="record__row">
      <dt>What you get</dt>
      <dd>A receipt itemising the medicine, the quantity and the amount. Keep it if you intend
          to claim from an insurer or an employer.</dd>
    </div>
    <div class="record__row">
      <dt>Insurance and employers</dt>
      <dd>Some insurers and employers in Vanuatu reimburse medicine costs. We do not bill them
          directly, but we will give you whatever documentation they ask for.</dd>
    </div>
  </dl>
</section>

<section class="section shell" aria-labelledby="cost-worry">
  <h2 id="cost-worry">If the cost is the problem</h2>
  <div class="prose">
    <p>
      Tell us. We cannot change the price of a medicine, and we will not pretend otherwise.
      What we can sometimes do is tell you the cheaper equivalent to take back to your doctor,
      split a long course into stages so it is not all due at once, or point you at the
      hospital pathway if the medicine is one the public system supplies.
    </p>
    <p>
      That conversation is free and a pharmacist would rather have it than watch someone
      abandon treatment quietly. Call <?= phone_link() ?>.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/patients/talk-to-a-pharmacist')) ?>">Talk to a pharmacist</a>
  </div>
</section>

<?php
parallel_column('How prices work, in short', [
    ['en' => 'We do not put prices on this website because every prescription is different.', 'bi' => null],
    ['en' => 'Send us your prescription and we will tell you the real price.', 'bi' => null],
    ['en' => 'You pay at the pharmacy, not on this website.', 'bi' => null],
    ['en' => 'Nothing is ordered until you agree to the price.', 'bi' => null],
    ['en' => 'If the price is too much, tell us. We will help you look at other options.', 'bi' => null],
], 'parallel-prices');

include INC . '/footer.php';
