<?php
/**
 * /medicines/catalog — the top of the medicines flow, where the Medicines
 * menu's "View Full Catalog" button goes.
 *
 * Laid out like the standalone Product Range catalog: a short hero, the
 * breadcrumb in its own bar, an "All Categories" heading with a "View all"
 * button, then every category as an underlined column (the condition groups,
 * then the shop's extras), its heading linking to the category's page with
 * its count on the right, and its products listed below. A long column shows
 * its first six and "More +" (guide.js, section 7c); the rest stay in the
 * HTML, so search engines still see every link.
 */
require __DIR__ . '/../../includes/bootstrap.php';

const CATALOG_MORE_AFTER = 6;

$cats   = shop_categories();
$byCat  = [];
foreach (shop_products() as $p) {
    $byCat[$p['cat']][] = $p;
}
$total = count(shop_products());

$page = [
    'title'     => 'Catalog',
    'seo_title' => 'Catalog — All Categories — Getmeds Vanuatu',
    'desc'      => 'Browse the full Getmeds Vanuatu catalog by category: ' . $total . ' products across ' . count($cats) . ' categories: targeted therapy, chemotherapy, immunotherapy, hormonal therapy and supportive care.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-bg-white g-conds-hero g-catalog__hero">
  <div class="g-wrap">
    <h1>Catalog</h1>
    <p class="g-hero__lede"><?= $total ?> products across <?= count($cats) ?> categories. Choose a category to see every product in it.</p>
  </div>
</section>

<div class="g-catalog__crumbbar"><div class="g-wrap"><?php g_crumbs([['Home', '/'], ['Catalog', null]]); ?></div></div>

<section class="g-sec g-bg-white g-catalog" aria-labelledby="catalog-h">
  <div class="g-wrap" data-catalog>
    <div class="g-catalog__head">
      <h2 id="catalog-h">All Categories</h2>
      <?= g_btn('View all ' . $total . ' products', url('/medicines/shop'), 'primary', 'arrow') ?>
    </div>

    <div class="g-catalog__grid">
      <?php foreach ($cats as $slug => [$name, , $n]): $listId = 'cat-' . $slug; ?>
      <div class="g-catalog__grp">
        <h3><a href="<?= e(url(shop_category_path($slug))) ?>"><?= e($name) ?></a><small><?= $n ?></small></h3>
        <ul role="list" id="<?= e($listId) ?>">
          <?php foreach ($byCat[$slug] as $i => $p): ?>
          <?php /* Hidden items stay in the HTML, so search engines still see every link. */ ?>
          <li<?= $i >= CATALOG_MORE_AFTER ? ' class="g-catalog__extra"' : '' ?>><a href="<?= e(url(shop_product_path($p))) ?>"><span><?= e($p['name']) ?></span><?= gi('chev-right') ?></a></li>
          <?php endforeach; ?>
        </ul>
        <?php if ($n > CATALOG_MORE_AFTER): ?>
        <button type="button" class="g-catalog__more" aria-expanded="false" aria-controls="<?= e($listId) ?>">More +</button>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
