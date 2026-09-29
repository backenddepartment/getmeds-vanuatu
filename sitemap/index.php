<?php
/**
 * HTML sitemap: every public page, grouped, for people rather than crawlers.
 *
 * Built from the same list as sitemap.xml (includes/sitemap.php), so the two
 * never disagree. Conditions are grouped the way the /conditions hub groups
 * them. Linked from the footer's legal strip.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/sitemap.php';

$page = [
    'title'     => 'Sitemap',
    'seo_title' => 'Sitemap — Getmeds Vanuatu',
    'desc'      => 'Every page on the Getmeds Vanuatu website, in one list.',
];

$byPath = [];
foreach (sitemap_pages() as $p) {
    $byPath[$p['path']] = $p;
}

$main = $more = $info = [];
foreach ($byPath as $path => $p) {
    if (strpos($path, '/conditions/') === 0) {
        continue;                      // listed under their groups below
    }
    if ($p['tier'] === 0)     { $main[] = $p; }
    elseif ($p['tier'] === 2) { $info[] = $p; }
    else                      { $more[] = $p; }
}

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-sec--tight">
  <div class="g-wrap g-sitemap">
    <?php g_crumbs([['Home', '/'], ['Sitemap', null]]); ?>
    <h1>Sitemap</h1>
    <p class="g-sitemap__lede">Every page on this website. Search engines can use the <a href="<?= e(url('/sitemap.xml')) ?>">XML sitemap</a> and the <a href="<?= e(url('/sitemap-images.xml')) ?>">image sitemap</a>.</p>

    <div class="g-sitemap__cols">
      <div>
        <h2>Main pages</h2>
        <ul>
          <?php foreach ($main as $p): ?>
          <li><a href="<?= e(url($p['path'])) ?>"><?= e($p['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>

        <?php if ($more): ?>
        <h2>Services and access</h2>
        <ul>
          <?php foreach ($more as $p): ?>
          <li><a href="<?= e(url($p['path'])) ?>"><?= e($p['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <h2>About and policies</h2>
        <ul>
          <?php foreach ($info as $p): ?>
          <li><a href="<?= e(url($p['path'])) ?>"><?= e($p['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div>
        <h2>Conditions</h2>
        <?php foreach (sitemap_condition_groups() as $group):
            $items = array_filter($group['conditions'] ?? [], fn($c) => isset($byPath['/conditions/' . $c['slug']]));
            if (!$items) { continue; } ?>
        <h3><?= e($group['name']) ?></h3>
        <ul>
          <?php foreach ($items as $c): ?>
          <li><a href="<?= e(url('/conditions/' . $c['slug'])) ?>"><?= e($c['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
