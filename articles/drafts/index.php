<?php
/**
 * Article drafts for pharmacist review. Hidden: noindex, out of the sitemap
 * (sitemap_skip_dirs), the site search, menus and the /articles listing.
 * How to publish one: README.md in this folder.
 */
require __DIR__ . '/../../includes/bootstrap.php';

$page = [
    'title'     => 'Article drafts',
    'seo_title' => 'Article drafts — Getmeds Vanuatu',
    'desc'      => 'Article drafts awaiting pharmacist review.',
    'noindex'   => true,
];

$drafts = [
    ['what-is-named-patient-supply', 'What is Named Patient Supply?',
     'How a medicine not normally available in Vanuatu is ordered for one named patient.'],
    ['preparing-for-your-chemotherapy-cycle', 'Preparing for your chemotherapy cycle',
     'Practical, non-clinical steps to have the medicine ready for each cycle.'],
    ['request-a-medicine-from-the-outer-islands', 'How to request a medicine from Santo, Tanna or the outer islands',
     'Sending a prescription from outside Port Vila, and getting the medicine to the island.'],
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-sec--tight">
  <div class="g-wrap g-read g-legal g-article">
    <?php g_crumbs([['Home', '/'], ['Articles', '/articles'], ['Drafts', null]]); ?>
    <?php include __DIR__ . '/banner.php'; ?>
    <h1>Article drafts</h1>
    <p>Three original articles written from what the website already says. Please check each one for accuracy, then tell the web team which can go live and what to change.</p>
    <ul class="g-drafts-list">
      <?php foreach ($drafts as [$slug, $title, $sum]): ?>
      <li><a href="<?= e(url('/articles/drafts/' . $slug)) ?>"><?= e($title) ?><span><?= e($sum) ?></span></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
