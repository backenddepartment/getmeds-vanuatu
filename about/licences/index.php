<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Our licences',
    'desc'  => 'The pharmacy licence Getmeds Vanuatu holds, and how to verify it independently.',
    'ref'   => 'GV-ABT-3',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Our licences',
    'What we hold, and how to check it yourself rather than taking our word for it.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <h2 style="margin-top:0">What we hold</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Pharmacy licence</dt>
      <dd><span class="num"><?= val('pharmacy_licence_no') ?></span></dd>
    </div>
    <div class="record__row">
      <dt>Held in the name of</dt>
      <dd><?= val('legal_entity_name') ?></dd>
    </div>
    <div class="record__row">
      <dt>Premises licensed</dt>
      <dd><?= e(cfg('address_line')) ?><br><?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></dd>
    </div>
    <div class="record__row">
      <dt>Responsible pharmacist</dt>
      <dd>A pharmacist registered in Vanuatu is named on the licence and is accountable
          for every medicine dispensed here.</dd>
    </div>
  </dl>

  <div class="todo-block">
    <p class="todo-block__head">Licence details required</p>
    <p class="todo-block__body">
      Issuing authority, licence category, issue date and expiry date, plus any import
      authorisation held for controlled or cold-chain medicines. Supplied by the responsible
      pharmacist. None of these have been guessed.
    </p>
  </div>
    </div>
    <div class="split__aside">
      <?php plate('script', [
        'ar'    => '4 / 5',
        'cap'   => 'A licence is a document you can ask to see.',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="verify">
  <h2 id="verify">How to verify it</h2>
  <div class="prose">
    <p>
      You should not have to trust a website. A licence number on a page proves nothing on its
      own, and any pharmacy asking you to spend money on cancer medicine should expect to be
      checked.
    </p>
  </div>
  <ol class="clauses">
    <li>Ask us for a copy of the licence. We will send it. If a pharmacy will not show you its
        licence, that tells you something.</li>
    <li>Check it with the issuing authority in Vanuatu directly. The registry, not us, is the
        source of truth.</li>
    <li>Come to the premises. The licence is displayed there, as it is required to be.</li>
  </ol>
  <div class="todo-block">
    <p class="todo-block__head">Verification route required</p>
    <p class="todo-block__body">
      The name of the issuing authority in Vanuatu, and the public route for checking a
      pharmacy licence and a pharmacist registration — a register, a phone number or an office.
      This must be accurate, so it has not been drafted here.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="scams">
  <h2 id="scams">Buying cancer medicine online</h2>
  <div class="notice notice--safety">
    <p class="notice__head">Counterfeit cancer medicine exists, and it kills people</p>
    <p>
      A website offering cancer medicine without a prescription, at a price well below the
      market, shipped from an unnamed country, is not a bargain. Falsified oncology product is
      a documented global problem, and a medicine that contains nothing is indistinguishable
      from a real one until the treatment fails.
    </p>
    <p>
      Ask any supplier for a licence you can verify, a named pharmacist, and a physical address
      you could walk into. Including us.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/about/part-of-getmeds')) ?>">Part of Getmeds</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
