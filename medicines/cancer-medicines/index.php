<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$cats = require APP_ROOT . '/data/medicines.php';
$cat  = $cats['cancer-medicines'];

$page = [
    'title' => $cat['title'],
    'desc'  => 'The groups of cancer medicine Getmeds Vanuatu supplies in Port Vila, against '
             . 'a valid prescription. No stock list or prices are published.',
    // The medicine lookup is this page's primary action.
    'own_primary' => true,
    'ref'   => 'GV-MED-1',
];

include INC . '/head.php';
include INC . '/header.php';

page_open($cat['title'], e($cat['intro']));
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split split--flip">
    <div class="split__body">
  <?php medicine_lookup('cancer-medicines'); ?>

  <h2>The groups we handle</h2>
  <?php chunk_facts($cat['groups']); ?>
    </div>
    <div class="split__aside">
      <?php plate('line', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="how-supplied">
  <h2 id="how-supplied">Who we give the medicine to</h2>
  <div class="prose">
    <p>
      This surprises people, so it is worth saying plainly. Medicines given by drip or
      injection go to the hospital or clinic that will give them to you. They do not come
      home with you. Your ward or your oncology nurse orders them, and we supply them there.
    </p>
    <p>
      Tablets and capsules you take at home are dispensed to you, in person, with a
      pharmacist going through the schedule with you before you leave.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="cyto">
  <h2 id="cyto">Handling and safety</h2>
  <div class="notice notice--safety">
    <p class="notice__head">Cancer medicines are hazardous to handle</p>
    <p>
      Do not crush, split or open a cancer tablet or capsule unless a pharmacist or your
      doctor has told you to. Keep them in the container they came in, out of reach of
      children, and away from anyone who is pregnant or breastfeeding. If a tablet breaks or
      spills, do not sweep it up with your hands. Call us on <?= phone_link() ?> and we will
      tell you what to do.
    </p>
  </div>
  <div class="prose">
    <p>
      When a course finishes, unused cancer medicine must not go in household rubbish or a
      toilet. Bring it back to us. See
      <a href="<?= e(url('/returns')) ?>">Returns &amp; Medicine Disposal</a>.
    </p>
  </div>
</section>

<?php
parallel_column('Cancer medicines, in short', [
    ['en' => 'Every cancer medicine needs a prescription from your doctor.', 'bi' => null],
    ['en' => 'We do not sell medicine on this website. A pharmacist checks every request.', 'bi' => null],
    ['en' => 'Medicine given by drip goes to your hospital, not to your house.', 'bi' => null],
    ['en' => 'Do not break or open cancer tablets. Keep them away from children.', 'bi' => null],
    ['en' => 'Bring unused cancer medicine back to the pharmacy. Do not throw it away.', 'bi' => null],
    ['en' => 'If you are unsure about anything, call ' . cfg('phone') . '.', 'bi' => null],
], 'parallel-cancer');
?>

<div class="section shell">
  <div class="actions" style="margin-top:0">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/medicines/side-effects')) ?>">Medicines for side effects</a>
  </div>
</div>

<?php
include INC . '/medicine-disclaimer.php';
include INC . '/footer.php';
