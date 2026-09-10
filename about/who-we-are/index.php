<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Who we are',
    'desc'  => 'What Getmeds Vanuatu is, and what it deliberately is not.',
    'ref'   => 'GV-ABT-1',
];

include INC . '/head.php';
include INC . '/header.php';

page_open('Who we are', 'What this pharmacy is, and what it is not.');
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split split--flip">
    <div class="split__body">
  <div class="prose">
    <p>
      Getmeds Vanuatu is a licensed specialty pharmacy on the ground floor of Golden Port in
      Port Vila. It is staffed by registered pharmacists. Its work is supplying cancer
      medicines, the medicines that manage their side effects, and other specialty medicines
      that need careful storage or importing.
    </p>
  </div>

  <h2>What we are</h2>
  <ul class="clauses">
    <li>A pharmacy. Licensed in Vanuatu, with a named responsible pharmacist accountable for
        every medicine that leaves the premises.</li>
    <li>A cold-chain operation. Monitored storage, logged, with backup power, because a
        medicine that has been too warm can look perfectly normal and no longer work.</li>
    <li>An importer. Through <?= e(cfg('group_name')) ?>, which buys at a scale that makes
        small Vanuatu quantities viable.</li>
    <li>A phone number somebody answers. For most patients that matters more than anything
        on this website.</li>
  </ul>

  <h2>What we are not</h2>
  <ul class="clauses">
    <li>Not a clinic. We do not diagnose, we do not treat, and we do not advise on how your
        illness should be managed. That is your doctor's work and we will not step into it.</li>
    <li>Not an online shop. There is no cart and no checkout on this site, and there will not
        be. Cancer medicines cannot responsibly be sold that way.</li>
    <li>Not a second opinion. If you disagree with your treatment plan, the conversation to
        have is with your oncologist, not with a pharmacist.</li>
    <li>Not a substitute for the hospital. Some medicines are supplied through the public
        system, and if that is the better route for you we will tell you so.</li>
  </ul>
    </div>
    <div class="split__aside">
      <?php plate('dispensary', [
        'ar'    => '4 / 5',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="premises">
  <h2 id="premises">The premises</h2>
  <?= photo_needed('The dispensary counter and the cold-chain refrigeration, Golden Port, Port Vila') ?>
  <div class="prose">
    <p>
      You are welcome to come and look. A pharmacy that asks people to trust its cold chain
      should be willing to show it, and we are.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/about/our-pharmacists')) ?>">Our pharmacists</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
