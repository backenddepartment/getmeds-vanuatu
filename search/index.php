<?php
/**
 * Search results (GET ?q=). A copy of the Getmeds India search page: the
 * same layout, wording, highlighting and ranking (includes/search.php,
 * srch_all()), written in this site's CSS (guide.css, "Search page") rather
 * than India's Tailwind, which the CSP here would block.
 *
 * Two groups: Products (every molecule in data/products.json) and Pages
 * (this site's own pages, read from the pages themselves). Every word typed
 * must match; the group with the best match is listed first, twelve of
 * each. With no query: "Popular starting points". With no results: tips
 * and two buttons. Both also carry the site's animated illustrations.
 * noindex: a results page has no content of its own.
 *
 * ?format=json answers the header search's suggestions as you type
 * (assets/js/search-suggest.js), ranked the same way.
 */
require dirname(__DIR__) . '/includes/bootstrap.php';
require_once INC . '/search.php';

$query = (isset($_GET['q']) && is_string($_GET['q'])) ? trim(mb_substr($_GET['q'], 0, 100, 'UTF-8')) : '';
$results = srch_all($query, 12);
$terms   = $results['terms'];

/** "Popular starting points" with no query: real pages of this site. */
$popular = [
    ['Home', '/'],
    ['About Us', '/about'],
    ['Product Range', '/product-range/'],
    ['Catalog', '/medicines/catalog'],
    ['How It Works', '/how-it-works'],
    ['Contact Us', '/contact'],
];

if (($_GET['format'] ?? '') === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    header('X-Robots-Tag: noindex');
    $meds  = $results['groups']['products']['all'] ?? [];
    $pages = $results['groups']['pages']['all'] ?? [];
    $cats  = $query !== '' ? search_medicine_cats($query) : [];
    echo json_encode([
        'q'          => $query,
        'medicines'  => array_map(static function ($m) {
            return ['name' => $m['title'], 'url' => url($m['path']), 'meta' => $m['summary']];
        }, array_slice($meds, 0, 6)),
        'categories' => array_map(static function ($c) {
            return ['name' => $c['name'], 'url' => url($c['url']), 'count' => $c['count']];
        }, array_slice($cats, 0, 3)),
        'pages'      => array_map(static function ($r) {
            return ['title' => $r['title'], 'url' => url($r['path'])];
        }, array_slice($pages, 0, 4)),
        'totals'     => ['medicines' => count($meds), 'categories' => count($cats), 'pages' => count($pages)],
        'all'        => url('/search/') . '?q=' . rawurlencode($query),
        'ask'        => request_url('medicine', '&medicine=' . rawurlencode($query)),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

$page = [
    'title'     => $query !== '' ? 'Search: ' . $query : 'Search',
    'seo_title' => ($query !== '' ? 'Search: ' . $query : 'Search') . ' — Getmeds Vanuatu',
    'desc'      => 'Search the Getmeds Vanuatu catalogue and pages.',
    'noindex'   => true,
];

include INC . '/head.php';
include INC . '/header.php';
?>

<div class="g-srp">
  <div class="g-srp__wrap">

    <h1><?php if ($query !== ''): ?>Results for &ldquo;<?= e($query) ?>&rdquo;<?php else: ?>Search<?php endif; ?></h1>

    <?php if ($query !== ''): ?>
    <p class="g-srp__sum" role="status">
      <?php if ($results['total'] > 0): ?>
      <?= (int) $results['total'] ?> result<?= $results['total'] === 1 ? '' : 's' ?>
      across products and pages<?= $results['shown'] < $results['total'] ? ' — showing the top ' . (int) $results['shown'] : '' ?>.
      <?php else: ?>
      Nothing matched that.
      <?php endif; ?>
    </p>
    <?php else: ?>
    <p class="g-srp__sum">Search the catalogue and the rest of the site.</p>
    <?php endif; ?>

    <?php if ($query === ''): ?>

    <div class="g-search-start">
      <?php /* A search bar typing itself, with a capsule, a document and a
               question mark floating above it. Still when motion is reduced. */ ?>
      <svg class="g-ss" viewBox="0 0 260 150" aria-hidden="true" focusable="false">
        <ellipse class="g-ss-shadow" cx="130" cy="138" rx="96" ry="6"/>
        <g class="g-ss-float g-ss-f1"><rect x="44" y="30" width="40" height="18" rx="9" transform="rotate(-24 64 39)" class="g-ss-pill"/><path d="M64 31v17" transform="rotate(-24 64 39)" class="g-ss-pillmid"/></g>
        <g class="g-ss-float g-ss-f2"><rect x="114" y="12" width="30" height="38" rx="5" class="g-ss-doc"/><path d="M121 24h16M121 31h16M121 38h10" class="g-ss-doclines"/></g>
        <text class="g-ss-float g-ss-f3 g-ss-q" x="186" y="44">?</text>
        <rect class="g-ss-bar" x="20" y="74" width="220" height="46" rx="23"/>
        <circle class="g-ss-lens" cx="46" cy="96" r="8"/><path class="g-ss-lens" d="M52 102l6 6"/>
        <rect class="g-ss-type" x="68" y="91" width="118" height="10" rx="5"/>
        <rect class="g-ss-caret" x="190" y="86" width="2.5" height="20" rx="1"/>
      </svg>
      <p>Type a medicine or a word or two, for example “pembrolizumab”, “prescription” or “delivery”.</p>
    </div>

    <div class="g-srp__popular">
      <p>Popular starting points</p>
      <div class="g-srp__chips">
        <?php foreach ($popular as [$label, $path]): ?>
        <a class="g-srp__chip" href="<?= e(url($path)) ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
      </div>
    </div>

    <?php elseif ($results['total'] === 0): ?>

    <div class="g-srp__none">
      <?= g_noresult('g-noresult--sm') ?>
      <p class="g-srp__nonehead">No results for &ldquo;<?= e($query) ?>&rdquo;.</p>
      <ul>
        <li>Check the spelling — molecule names are long and easy to mistype.</li>
        <li>Try the molecule name on its own rather than a brand or a combination.</li>
        <li>Try a broader word, such as a drug class (&ldquo;checkpoint inhibitor&rdquo;) or a therapy area.</li>
      </ul>
      <div class="g-srp__chips g-srp__nonebtns">
        <a class="g-srp__chip g-srp__chip--blue" href="<?= e(url('/product-range/')) ?>">Browse all products</a>
        <a class="g-srp__chip" href="<?= e(request_url('medicine', '&medicine=' . rawurlencode($query))) ?>">Ask us directly</a>
      </div>
    </div>

    <?php else: ?>

    <?php foreach ($results['groups'] as $key => $group): ?>
    <section class="g-srp__grp" aria-labelledby="srp-<?= e($key) ?>">
      <h2 id="srp-<?= e($key) ?>"><?= e($group['label']) ?>
        <span>(<?= count($group['items']) < $group['total'] ? count($group['items']) . ' of ' . (int) $group['total'] : (int) $group['total'] ?>)</span></h2>
      <ul class="g-srp__list" role="list">
        <?php foreach ($group['items'] as $item): $summary = srch_trim($item['summary'], 200); ?>
        <li>
          <a href="<?= e(url($item['path'])) ?>">
            <div>
              <p class="g-srp__title"><?= srch_highlight(e($item['title']), $terms) ?></p>
              <?php if ($summary !== ''): ?><p class="g-srp__summary"><?= srch_highlight(e($summary), $terms) ?></p><?php endif; ?>
              <?php if (!empty($item['meta'])): ?><p class="g-srp__meta"><?= e($item['meta']) ?></p><?php endif; ?>
            </div>
            <svg class="g-srp__chev" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php if ($key === 'products' && $group['total'] > count($group['items'])): ?>
      <a class="g-srp__all" href="<?= e(url('/product-range/') . '?q=' . rawurlencode($query)) ?>">
        See all <?= (int) $group['total'] ?> matching products
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"/><path d="M7 7h10v10"/></svg>
      </a>
      <?php endif; ?>
    </section>
    <?php endforeach; ?>

    <?php endif; ?>

  </div>
</div>

<?php include INC . '/footer.php'; ?>
