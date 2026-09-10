<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Part of Getmeds',
    'desc'  => 'How the wider Getmeds group supports specialty medicine supply into Vanuatu.',
    'ref'   => 'GV-ABT-4',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Part of Getmeds',
    'Why belonging to a larger group is the reason a small market can get these medicines at all.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="prose">
    <p>
      Getmeds Vanuatu is part of <?= e(cfg('group_name')) ?>. The practical effect is
      purchasing scale. A single pharmacy in Port Vila ordering one course of a targeted
      therapy is a rounding error to a manufacturer. A group ordering across several markets
      is a customer.
    </p>
    <p>
      That is not a marketing point. It is the mechanism by which a medicine reaches a patient
      in Vanuatu at all, and it is worth being plain about.
    </p>
  </div>

  <h2>What the group provides</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Purchasing</dt>
      <dd>Access to supply at quantities a standalone Vanuatu pharmacy could not order.</dd>
    </div>
    <div class="record__row">
      <dt>Sourcing</dt>
      <dd>Routes to medicines that have never had a Vanuatu distributor.</dd>
    </div>
    <div class="record__row">
      <dt>Cold chain in transit</dt>
      <dd>Validated shipping into Port Vila, rather than each consignment being improvised.</dd>
    </div>
    <div class="record__row">
      <dt>Group companies</dt>
      <dd><?= e(implode(', ', cfg('group_sites'))) ?></dd>
    </div>
  </dl>

  <h2>What stays local</h2>
  <ul class="clauses">
    <li>The pharmacy licence, held in Vanuatu, for these premises.</li>
    <li>The responsible pharmacist, accountable under Vanuatu law for every medicine dispensed
        here.</li>
    <li>Dispensing, counselling and the conversation you have when you collect. Those happen in
        Port Vila, with a person.</li>
    <li>The cold chain from our refrigerator to you.</li>
  </ul>
</div>

<section class="section shell" aria-labelledby="who-you-deal-with">
  <h2 id="who-you-deal-with">Who you are dealing with</h2>
  <div class="prose">
    <p>
      When you enquire here, a pharmacist in Port Vila reads it. When you collect, you collect
      here. Group membership changes where the medicine is bought, not who is accountable for
      it once it arrives.
    </p>
    <p>
      If you want to know who holds the licence and how to check it, that is on
      <a href="<?= e(url('/about/licences')) ?>">Our Licences</a>.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/contact')) ?>">Contact the pharmacy</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
