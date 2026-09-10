<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Medicines',
    'desc'  => 'The kinds of medicine Getmeds Vanuatu handles: cancer medicines, medicines '
             . 'for side effects, and other specialty medicines. All require a prescription.',
    // The medicine lookup is this page's primary action.
    'own_primary' => true,
    'ref'   => 'GV-MED',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Medicines',
    'We do not publish a stock list or prices. These are the kinds of medicine we handle, '
    . 'so you can tell whether to ask us.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <div class="prose">
    <p>
      Cancer medicines are ordered for one patient at a time, against one prescription.
      What is on the shelf changes week to week, and the price depends on the medicine, the
      dose and the length of the course. A list on a website would be wrong within days and
      could not tell you what you actually need to know.
    </p>
    <p>
      So the honest answer is to ask. Type a medicine name below and a pharmacist will tell
      you whether we can supply it, what it costs and how long it takes.
    </p>
  </div>

  <?php medicine_lookup('medicines'); ?>

  <h2>The three groups</h2>
  <?php chunk_links(nav_children('/medicines')); ?>
    </div>
    <div class="split__aside">
      <?php plate('ampoules', [
        'ar'    => '4 / 5',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="every-medicine">
  <h2 id="every-medicine">What is true of every medicine here</h2>
  <ul class="clauses">
    <li>It requires a valid prescription from a doctor licensed to prescribe it. We cannot
        dispense without one, and we will not.</li>
    <li>It is not sold from this website. There is no cart and no checkout. Every enquiry is
        read by a pharmacist before anything is ordered.</li>
    <li>It is stored to the manufacturer's requirement, and anything needing 2 to 8 degrees
        stays in the cold chain from the supplier to you.</li>
    <li>A pharmacist will go through how to take it, what to watch for, and what to do if
        something goes wrong, before you leave with it.</li>
  </ul>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/patients/what-you-need')) ?>">See what you need before ordering</a>
  </div>
</section>

<?php
include INC . '/medicine-disclaimer.php';
include INC . '/footer.php';
