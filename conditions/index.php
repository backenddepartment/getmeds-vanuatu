<?php
/**
 * Conditions hub (content guide, page 07). URL: /conditions
 *
 * A directory: Sky hero with the H1, intro and a live-filtering search box
 * (guide.js, section 7) with A–Z quick links on desktop; the twelve condition
 * groups as headed columns of links (accordions on a phone, guide.js closes
 * them); then the "Can't find your condition?" mint card. Groups and names
 * come from data/conditions.php, in the guide's order, cancer groups first.
 */
require __DIR__ . '/../includes/bootstrap.php';

$groups = require APP_ROOT . '/data/conditions.php';

$page = [
    'title'     => 'Medicine access by condition',
    'seo_title' => 'Conditions — Medicine Access by Condition — Getmeds Vanuatu',
    'desc'      => 'Find information about medicine access for cancer, blood disorders, diabetes, heart, kidney and other conditions. Getmeds Vanuatu helps you check availability.',
];

// A–Z: each letter links to the first condition (in list order) starting with it.
$az = [];
foreach ($groups as $g) {
    foreach ($g['conditions'] as $c) {
        $l = strtoupper($c['name'][0]);
        if (!isset($az[$l])) {
            $az[$l] = $c['slug'];
        }
    }
}

$gid = static function (string $name): string {
    return 'g-' . trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-');
};

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-hero--sky g-hero--short">
  <div class="g-wrap">
    <?php g_crumbs([['Home', '/'], ['Conditions', null]]); ?>
    <h1>Medicine access by condition</h1>
    <p class="g-hero__lede">Choose a condition to learn how Getmeds can help you access medicines. These pages give general information only. Your doctor decides which medicine is right for you.</p>
    <div class="g-bigsearch g-mt">
      <?= gi('search') ?>
      <input type="search" data-filter="#cond-list" placeholder="Search a condition, for example &quot;breast cancer&quot;" aria-label="Search a condition" autocomplete="off">
    </div>
    <nav class="g-az" aria-label="Conditions A to Z">
      <?php foreach (range('A', 'Z') as $L): ?>
        <?php if (isset($az[$L])): ?>
        <a href="#c-<?= e($az[$L]) ?>"><?= $L ?></a>
        <?php else: ?>
        <span aria-hidden="true"><?= $L ?></span>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>
  </div>
</section>

<section class="g-sec g-bg-white" aria-label="Conditions">
  <div class="g-wrap" id="cond-list">
    <div class="g-cgroups">
      <?php foreach ($groups as $g): ?>
      <details class="g-cgroup g-cgroup--<?= e($g['accent']) ?>" open data-filter-group id="<?= e($gid($g['name'])) ?>">
        <summary><h2><?= gi($g['icon']) ?><span><?= e($g['name']) ?> <span class="g-count"><?= count($g['conditions']) ?></span></span></h2></summary>
        <ul role="list">
          <?php foreach ($g['conditions'] as $c): ?>
          <li data-filter-item id="c-<?= e($c['slug']) ?>"><a href="<?= e(url('/conditions/' . $c['slug'] . '/')) ?>"><span><?= e($c['name']) ?></span><?= gi('arrow') ?></a></li>
          <?php endforeach; ?>
        </ul>
      </details>
      <?php endforeach; ?>
    </div>
    <p class="g-hidden g-cond-empty" data-filter-empty role="status">No conditions match</p>
  </div>
</section>

<section class="g-sec g-bg-white g-cond-help">
  <div class="g-wrap g-narrow">
    <div class="g-card g-card--mint g-center">
      <h2>Can't find your condition?</h2>
      <p class="g-mt-sm">We supply medicines for many more conditions. Send us your prescription and we will check what we can supply.</p>
      <div class="g-btns g-btns--center">
        <?= g_btn('Request a Medicine', request_url('medicine'), 'primary') ?>
        <?= g_call_btn('Talk to Our Team', 'secondary') ?>
      </div>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
