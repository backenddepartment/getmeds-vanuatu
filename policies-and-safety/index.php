<?php
/**
 * Policies and Safety (content guide, page 18).
 *
 * A reference page: a sticky in-page menu on the left on desktop, and each
 * policy a short block with an icon. On a phone and tablet the menu hides and
 * the blocks become accordions (they start closed there, data-mobile-closed;
 * a link to #returns etc. opens its block). Old policy addresses redirect
 * here with the matching anchor.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Policies and patient safety',
    'seo_title' => 'Policies and Patient Safety — Getmeds Vanuatu',
    'desc'      => 'How Getmeds Vanuatu keeps medicines safe: prescriptions, pharmacist checks, cold storage, '
                 . 'returns and safe disposal.',
];

$menu = [
    ['prescriptions',     'Prescriptions'],
    ['pharmacist-checks', 'Pharmacist checks'],
    ['cold-chain',        'Storage and cold chain'],
    ['returns',           'Returns and safe disposal'],
    ['your-information',  'Your information'],
];

include INC . '/head.php';
include INC . '/header.php';

/** Opens one policy block: a card on desktop, an accordion on smaller screens. */
$open = static function (string $id, string $icon, string $title): void {
    ?>
    <details class="g-acc__item g-policy" id="<?= e($id) ?>" open data-mobile-closed>
      <summary><span class="g-acc__title"><?= gi($icon) ?><h2><?= e($title) ?></h2></span></summary>
      <div class="g-acc__body">
    <?php
};
$close = static function (): void {
    ?>
      </div>
    </details>
    <?php
};
?>

<section class="g-hero g-hero--sky g-hero--short">
  <div class="g-wrap">
    <?php g_crumbs([['Home', '/'], ['Policies and patient safety', null]]); ?>
    <h1>Policies and patient safety</h1>
  </div>
</section>

<section class="g-sec g-bg-white g-sec--tight">
  <div class="g-wrap g-withside">
    <nav class="g-sidemenu g-sidemenu--blue" aria-label="On this page" data-spy>
      <ul>
        <?php foreach ($menu as [$id, $label]): ?>
        <li><a href="#<?= e($id) ?>"><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="g-acc g-acc--cards g-policies">
      <?php $open('prescriptions', 'file', 'Prescriptions'); ?>
        <p>Every prescription medicine needs a valid prescription from a doctor. Cancer medicines must follow a prescription or treatment protocol. We do not sell prescription medicines without one.</p>
      <?php $close(); ?>

      <?php $open('pharmacist-checks', 'pharmacist', 'Pharmacist checks'); ?>
        <p>A pharmacist checks every prescription for the right medicine, strength and amount before supply, and explains how to take or handle the medicine.</p>
      <?php $close(); ?>

      <?php $open('cold-chain', 'thermo', 'Storage and cold chain'); ?>
        <p>Medicines are stored as the maker directs.</p>
        <?php g_box('info', 'Medicines that must stay between 2 and 8 °C are kept in monitored refrigerators and travel in monitored cold boxes, logged from supplier to patient.', '', 'thermo'); ?>
      <?php $close(); ?>

      <?php $open('returns', 'refresh', 'Returns and safe disposal'); ?>
        <p>For safety, we cannot resell medicines once they have left the pharmacy.</p>
        <?php g_box('safety', 'Unused cancer medicines must not go in household rubbish or down the toilet. Bring them back to us in their original pack and we will dispose of them safely.', '', 'alert'); ?>
      <?php $close(); ?>

      <?php $open('your-information', 'lock', 'Your information'); ?>
        <p>We keep your prescription and personal details private and use them only to supply your medicine. See our <a href="<?= e(url('/privacy')) ?>">Privacy Policy</a>.</p>
      <?php $close(); ?>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
