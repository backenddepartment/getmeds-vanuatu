<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$cats = require APP_ROOT . '/data/medicines.php';
$cat  = $cats['other-specialty'];

$page = [
    'title' => $cat['title'],
    'desc'  => 'Cold-chain, imported and hard-to-find specialty medicines supplied by '
             . 'Getmeds Vanuatu outside cancer care.',
    // The medicine lookup is this page's primary action.
    'own_primary' => true,
    'ref'   => 'GV-MED-3',
];

include INC . '/head.php';
include INC . '/header.php';

page_open($cat['title'], e($cat['intro']));
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <?php medicine_lookup('other-specialty'); ?>

  <h2>The groups we handle</h2>
  <?php chunk_facts($cat['groups']); ?>
    </div>
    <div class="split__aside">
      <?php plate('bottles', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="sourcing">
  <h2 id="sourcing">If it is not normally available in Vanuatu</h2>
  <div class="prose">
    <p>
      A lot of specialty medicine has never been stocked in Vanuatu, simply because the
      quantities are small. That does not mean it cannot be got. We import through the wider
      <?= e(cfg('group_name')) ?>, which buys at a scale a single Port Vila pharmacy could
      not, and we handle the import paperwork.
    </p>
    <p>
      What we need from you is the exact name and strength on the prescription. What you need
      from us is an honest lead time before you commit, and you will get one. See
      <a href="<?= e(url('/shipping-rules')) ?>">Shipping &amp; Import Rules</a> for what
      customs requires.
    </p>
  </div>

  <div class="notice">
    <p class="notice__head">Ask early if you are running low</p>
    <p>
      An imported medicine is not a same-day service. If you take something long-term and
      you are down to a few weeks' supply, tell us now rather than when the box is empty.
      Call <?= phone_link() ?>.
    </p>
  </div>
</section>

<div class="section shell">
  <div class="actions" style="margin-top:0">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/patients/delivery')) ?>">How delivery works</a>
  </div>
</div>

<?php
include INC . '/medicine-disclaimer.php';
include INC . '/footer.php';
