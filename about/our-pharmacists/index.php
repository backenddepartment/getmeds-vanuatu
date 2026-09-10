<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Our pharmacists',
    'desc'  => 'Who dispenses your medicine at Getmeds Vanuatu, and their registration.',
    'ref'   => 'GV-ABT-2',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Our pharmacists',
    'Who dispenses your medicine, and how to check they are registered to do it.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <h2 style="margin-top:0">Responsible pharmacist</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Name</dt>
      <dd><?= val('pharmacist_name') ?></dd>
    </div>
    <div class="record__row">
      <dt>Registration number</dt>
      <dd><span class="num"><?= val('pharmacist_reg_no') ?></span></dd>
    </div>
    <div class="record__row">
      <dt>Accountable for</dt>
      <dd>Every medicine dispensed from these premises, the cold chain, the controlled
          medicines register, and the conduct of the pharmacy.</dd>
    </div>
  </dl>

  <?= photo_needed('The responsible pharmacist at the dispensary counter') ?>

  <div class="todo-block">
    <p class="todo-block__head">Biography required</p>
    <p class="todo-block__body">
      Qualifications, years in practice, oncology experience and languages spoken. Supplied by
      the responsible pharmacist. Nothing has been invented here.
    </p>
  </div>
</div>

<section class="section shell" aria-labelledby="languages">
  <h2 id="languages">Languages</h2>
  <div class="prose">
    <p>
      Vanuatu's official languages are Bislama, English and French, and a medicine schedule is
      hard enough to follow in your first language. Tell us when you call which you would
      rather use and we will find someone who can help.
    </p>
  </div>
  <div class="todo-block">
    <p class="todo-block__head">Confirmation required</p>
    <p class="todo-block__body">
      Which languages the pharmacy can actually offer, and on which days. This must be
      confirmed rather than assumed, because a promise here that cannot be kept is worse than
      no promise.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="counselling">
  <h2 id="counselling">What a pharmacist will do when you collect</h2>
  <ol class="clauses">
    <li>Check the prescription against the medicine, and check it against anything else you
        have told us you take.</li>
    <li>Go through the schedule with you: how much, how often, with or without food, and what
        to do if you miss one.</li>
    <li>Tell you what side effects are expected, which ones mean call us, and which ones mean
        go to hospital.</li>
    <li>Explain how to store it at home, and how to bring back what you do not use.</li>
  </ol>
  <div class="prose">
    <p class="small quiet">
      Bring someone with you if you can. Two people remember a schedule better than one.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/about/licences')) ?>">Our licences</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
