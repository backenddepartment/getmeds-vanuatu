<?php
/**
 * /medicines — one page replacing the landing page and its three children.
 *
 * Still no medicine names. data/medicines.php explains why at length and the
 * rule has not changed: naming a molecule here would read as a stock claim, and
 * a patient who drove in for something we do not hold has been badly served.
 *
 * What did change is the ending. Each group used to lead to another page of
 * reading; now each section ends at /order with the category pre-selected, so
 * "this sounds like mine" and "ask them about it" are one tap apart.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/icons.php';

$cats = require APP_ROOT . '/data/medicines.php';

$page = [
    'title' => 'Medicines we supply',
    'desc'  => 'The groups of cancer medicine, side-effect medicine and other specialty '
             . 'medicine Getmeds Vanuatu supplies against a prescription.',
    'ref'   => 'GV-MED',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Medicines we supply',
    'The groups we handle. Every one needs a prescription from the doctor treating you.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="notice">
    <p class="notice__head"><?= icon('info') ?>We do not list individual medicines here</p>
    <p>
      Stock changes, and a name on a website would read as a promise we might not be able to
      keep. Send the prescription instead and a pharmacist will tell you, usually the same
      working day, whether we can supply it and what it costs.
    </p>
    <p style="margin-top:var(--s-4)">
      <a class="btn btn--primary" href="<?= e(url('/order')) ?>">Order a Medicine</a>
    </p>
  </div>
</div>

<?php
/* The three groups, in the order a cancer patient meets them. */
$sections = [
    'cancer'       => ['key' => 'cancer-medicines', 'need' => 'cancer'],
    'side-effects' => ['key' => 'side-effects',     'need' => 'side'],
    'other'        => ['key' => 'other-specialty',  'need' => 'other'],
];

foreach ($sections as $anchor => $meta):
    $cat = $cats[$meta['key']] ?? null;
    if (!$cat) { continue; }
?>
<section class="section shell" aria-labelledby="<?= e($anchor) ?>">
  <h2 id="<?= e($anchor) ?>"><?= e($cat['title']) ?></h2>
  <div class="prose">
    <p><?= e($cat['intro']) ?></p>
  </div>

  <?php chunk_facts($cat['groups']); ?>

  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/order')) ?>?need=<?= e($meta['need']) ?>">
      Order from this group
    </a>
  </div>
</section>
<?php endforeach; ?>

<div class="section shell">
  <div class="prose">
    <p class="small quiet">
      Injectable and hospital-administered medicines are supplied to the ward or clinic giving
      them, not to you directly.
      <a href="<?= e(url('/how-it-works')) ?>">How ordering works</a> ·
      <a href="<?= e(url('/providers')) ?>">For doctors and hospitals</a>
    </p>
  </div>
</div>

<?php include INC . '/footer.php'; ?>
