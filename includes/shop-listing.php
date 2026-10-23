<?php
/**
 * Product Range: /product-range (all products) and
 * /<category>/ (one category). Included by
 * product-range/index.php, which sets $shopCat (a category slug, null for
 * all, false for an address that matches nothing) and $cats.
 *
 * Laid out like Getmeds India's Product Range: a short hero, the breadcrumb
 * in its own bar, a Therapeutic Areas sidebar beside a search box with quick
 * filters, then a table whose rows open to show their details and a Send
 * Inquiry menu per product. On a phone the sidebar and the chips become two
 * dropdowns.
 *
 * Search, filters, sort and pages are ?q=, ?quick=, ?sort= and ?page=, worked
 * out here rather than in the browser, so every view is a real address. No
 * sort keeps data/products.json's order: most-requested first.
 *
 * Send Inquiry opens the product's page with ?userType= set, so the right
 * form is already showing there (shop-product.php).
 */

const SHOP_PER_PAGE = 12;

if ($shopCat === false) {
    // Same as condition-page.php: 404.php requires bootstrap itself, so
    // answer with the status and a pointer back instead.
    http_response_code(404);
    $page = [
        'title'   => 'That page is not here',
        'desc'    => 'The page you asked for does not exist on the Getmeds Vanuatu website.',
        'noindex' => true,
    ];
    include INC . '/head.php';
    include INC . '/header.php';
    ?>
    <section class="g-hero g-hero--short g-hero--sky">
      <div class="g-wrap">
        <h1>That page is not here</h1>
        <p class="g-hero__lede">The address you used does not match a medicine or a category.</p>
        <div class="g-btns">
          <?= g_btn('Browse the catalog', url('/medicines/catalog'), 'primary', 'arrow') ?>
          <?= g_btn('Request a Medicine', request_url('medicine'), 'secondary') ?>
        </div>
      </div>
    </section>
    <?php
    include INC . '/footer.php';
    return;
}

$QUICK = ['solid' => 'Solid Tumour', 'hematologic' => 'Hematologic', 'both' => 'Both'];
$SORT  = ['name_asc' => 'Name A-Z', 'name_desc' => 'Name Z-A'];
$AUDIENCES = [
    'patient'  => ['Patient / Caregiver', 'user'],
    'doctor'   => ['Doctor / Healthcare Professional', 'doctor'],
    'pharmacy' => ['Pharmacy Owner / Retail Pharmacy', 'pill'],
    'hospital' => ['Hospital / Institution', 'hospital'],
];

$all      = $shopCat === null;
$basePath = $all ? '/product-range/' : shop_category_path($shopCat);
$catName  = $all ? 'All Products' : $cats[$shopCat][0];

$q     = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
$quick = isset($_GET['quick']) && is_string($_GET['quick']) && isset($QUICK[$_GET['quick']]) ? $_GET['quick'] : '';
$sort  = isset($_GET['sort']) && is_string($_GET['sort']) && isset($SORT[$_GET['sort']]) ? $_GET['sort'] : '';
$pg    = isset($_GET['page']) && is_string($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$current  = ['q' => $q, 'quick' => $quick, 'sort' => $sort];   // kept when moving around
$filtered = $q !== '' || $quick !== '' || $sort !== '';

$rows = array_values(array_filter(shop_products(), static function ($p) use ($shopCat, $q, $quick) {
    if ($shopCat !== null && $p['cat'] !== $shopCat) {
        return false;
    }
    if ($q !== '' && stripos($p['name'] . ' ' . $p['drug_class'], $q) === false) {
        return false;
    }
    return $quick === '' || shop_oncology_group($p) === $quick;
}));
if ($sort !== '') {
    usort($rows, static function ($a, $b) use ($sort) {
        $c = strcasecmp($a['name'], $b['name']);
        return $sort === 'name_desc' ? -$c : $c;
    });
}
$total = count($rows);
$pages = max(1, (int) ceil($total / SHOP_PER_PAGE));
$pg    = min($pg, $pages);
$rows  = array_slice($rows, ($pg - 1) * SHOP_PER_PAGE, SHOP_PER_PAGE);
$count = $all ? count(shop_products()) : $cats[$shopCat][2];

/** A listing URL with these parameters; empty ones and page 1 are dropped. */
$href = static function (string $path, array $params): string {
    $params = array_filter($params, static function ($v) {
        return $v !== '' && $v !== null && $v !== 1;
    });
    return url($path) . ($params ? '?' . http_build_query($params) : '');
};

// Home / Catalog / Product Range [/ Category]
$trail = [['Home', '/'], ['Catalog', '/medicines/catalog'], ['Product Range', $all ? null : '/product-range/']];
if (!$all) {
    $trail[] = [$catName, null];
}

$page = $all ? [
    'title'     => 'Product Range',
    'seo_title' => 'Product Range — Getmeds Vanuatu',
    'desc'      => 'Browse our oncology portfolio of ' . $count . ' molecules: targeted therapy, chemotherapy, immunotherapy, hormonal therapy and supportive care. Send an inquiry for any product.',
] : [
    'title'     => $catName,
    'seo_title' => $catName . ' — Product Range — Getmeds Vanuatu',
    'desc'      => 'Browse ' . $count . ' ' . $catName . ' molecules at Getmeds Vanuatu. Send an inquiry for any product.',
];
// A filtered or sorted view repeats the plain listing: keep it out of search results.
if ($filtered) {
    $page['noindex'] = true;
}

$headerOverlay = true;
include INC . '/head.php';
include INC . '/header.php';
?>

<?php /* The medicinebg photo runs from the top of the page, behind the
         see-through header, down to the breadcrumb bar's line (guide.css
         "Product Range hero"). */ ?>
<section class="g-hero g-conds-hero g-catalog__hero g-pr-hero">
  <picture class="g-pr-hero__img" aria-hidden="true">
    <source type="image/webp" srcset="<?= e(asset('/assets/img/medicinebg-1024.webp')) ?> 1024w, <?= e(asset('/assets/img/medicinebg-full.webp')) ?> 2048w" sizes="100vw">
    <img src="<?= e(asset('/assets/img/medicinebg-full.jpg')) ?>" alt="" loading="eager" fetchpriority="high" decoding="async">
  </picture>
  <div class="g-pr-hero__shade" aria-hidden="true"></div>
  <div class="g-wrap">
    <h1><?= e($all ? 'Products' : $catName) ?></h1>
    <p class="g-hero__lede"><?= $all
        ? 'Comprehensive catalog of pharmaceutical solutions. Browse categories and send inquiries directly.'
        : $count . ' molecule' . ($count === 1 ? '' : 's') . ' in this therapeutic area. Open any product for its details, or send an inquiry directly.' ?></p>
  </div>
</section>

<div class="g-catalog__crumbbar"><div class="g-wrap"><?php g_crumbs($trail); ?></div></div>

<div class="g-wrap g-pr">

  <aside class="g-pr__side" aria-labelledby="pr-side-h">
    <h2 id="pr-side-h">Therapeutic<br>Areas</h2>
    <nav aria-labelledby="pr-side-h" id="pr-side">
      <a href="<?= e($href('/product-range/', $current)) ?>"<?= $all ? ' class="is-active" aria-current="page"' : '' ?>>All Products</a>
      <?php foreach ($cats as $slug => [$name]): ?>
      <a href="<?= e($href(shop_category_path($slug), $current)) ?>"<?= $slug === $shopCat ? ' class="is-active" aria-current="page"' : '' ?>><?= e($name) ?></a>
      <?php endforeach; ?>
    </nav>
  </aside>

  <div class="g-pr__main">
    <h2 class="g-pr__tab" id="pr-tab"><?= e($catName) ?> <span>(<?= $total ?>)</span></h2>

    <div class="g-pr__filters">
      <form class="g-pr__search" method="get" action="<?= e(url($basePath)) ?>" role="search">
        <?php if ($quick !== ''): ?><input type="hidden" name="quick" value="<?= e($quick) ?>"><?php endif; ?>
        <?php if ($sort !== ''): ?><input type="hidden" name="sort" value="<?= e($sort) ?>"><?php endif; ?>
        <?php /* Filters as you type (script below); Enter searches without it.
                 The x clears the words. */ ?>
        <div class="g-pr__field">
          <?= gi('search') ?>
          <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search product or ingredient..." aria-label="Search product or ingredient" autocomplete="off" id="pr-q">
          <a class="g-pr__x" id="pr-x" href="<?= e($href($basePath, ['quick' => $quick, 'sort' => $sort])) ?>" aria-label="Clear search"<?= $q === '' ? ' hidden' : '' ?>><?= gi('close') ?></a>
        </div>
      </form>

      <?php /* Dropdowns. Therapeutic Areas shows on phones only: the
               sidebar does its job on larger screens. */ ?>
      <div class="g-pr__selects" id="pr-selects">
        <label class="g-pr__areas"><span>Therapeutic Areas</span>
          <select data-pr-nav>
            <option data-url="<?= e($href('/product-range/', $current)) ?>"<?= $all ? ' selected' : '' ?>>All Products</option>
            <?php foreach ($cats as $slug => [$name]): ?>
            <option data-url="<?= e($href(shop_category_path($slug), $current)) ?>"<?= $slug === $shopCat ? ' selected' : '' ?>><?= e($name) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label><span>Quick filters</span>
          <select data-pr-nav>
            <option data-url="<?= e($href($basePath, array_merge($current, ['quick' => '']))) ?>"<?= $quick === '' ? ' selected' : '' ?>>All types</option>
            <?php foreach ($QUICK as $key => $label): ?>
            <option data-url="<?= e($href($basePath, array_merge($current, ['quick' => $key]))) ?>"<?= $quick === $key ? ' selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label><span>Sort by</span>
          <select data-pr-nav>
            <option data-url="<?= e($href($basePath, array_merge($current, ['sort' => '']))) ?>"<?= $sort === '' ? ' selected' : '' ?>>Most requested</option>
            <?php foreach ($SORT as $key => $label): ?>
            <option data-url="<?= e($href($basePath, array_merge($current, ['sort' => $key]))) ?>"<?= $sort === $key ? ' selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
      </div>
    </div>

    <?php /* Everything below is swapped in place as the search changes. */ ?>
    <div id="pr-results">
    <?php if (!$rows): ?>
    <div class="g-pr__empty" role="status">
      <?= g_noresult() ?>
      <p><strong>No products match these filters.</strong></p>
      <p class="g-pr__empty-sub">Try another name or ingredient, or clear the filters.</p>
      <div class="g-pr__empty-btns">
        <a class="g-pr__btn" href="<?= e(url($basePath)) ?>">Clear all filters</a>
        <?php if (!$all && $q !== ''): ?>
        <a class="g-pr__btn g-pr__btn--ghost" href="<?= e($href('/product-range/', ['q' => $q])) ?>">Search all products</a>
        <?php endif; ?>
        <a class="g-pr__btn g-pr__btn--ghost" href="<?= e(request_url('medicine', $q !== '' ? '&medicine=' . rawurlencode($q) : '')) ?>">Ask us to source it</a>
      </div>
    </div>
    <?php else: ?>
    <table class="g-pr__table">
      <thead>
        <tr><th scope="col">Molecule</th><th scope="col" class="g-pr__md">Drug Class / Mechanism</th><th scope="col" class="g-pr__md">Oncology Type</th><th scope="col">Inquiry</th><th scope="col"><span class="g-sr">Details</span></th></tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $i => $p): $purl = url(shop_product_path($p)); ?>
        <tr class="g-pr__row">
          <td>
            <h3><a href="<?= e($purl) ?>"><?= e($p['name']) ?></a></h3>
            <span class="g-pr__cat"><?= e($p['category']) ?></span>
          </td>
          <td class="g-pr__md"><?= e($p['drug_class'] ?: '—') ?></td>
          <td class="g-pr__md g-pr__blue"><?= e($p['oncology_type'] ?: '—') ?></td>
          <td>
            <button type="button" class="g-pr__inq" data-url="<?= e($purl) ?>" aria-haspopup="true" aria-expanded="false">
              <?= gi('send', 'g-pr__inq-send') ?>Send Inquiry<?= gi('chev-down', 'g-pr__inq-chev') ?>
            </button>
          </td>
          <td class="g-pr__chev-cell">
            <button type="button" class="g-pr__chev" aria-expanded="false" aria-controls="pr-detail-<?= $i ?>" aria-label="Show details for <?= e($p['name']) ?>"><?= gi('chev-right') ?></button>
          </td>
        </tr>
        <tr class="g-pr__detail" id="pr-detail-<?= $i ?>" hidden>
          <td colspan="5">
            <dl class="g-pr__grid">
              <div><dt>Molecule</dt><dd><?= e($p['name']) ?></dd></div>
              <div><dt>Therapeutic Category</dt><dd><?= e($p['category']) ?></dd></div>
              <div><dt>Oncology Type</dt><dd><?= e($p['oncology_type'] ?: '—') ?></dd></div>
              <div><dt>Drug Class / Mechanism</dt><dd><?= e($p['drug_class'] ?: '—') ?></dd></div>
            </dl>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php if ($pages > 1): ?>
    <nav class="g-pr__pages" aria-label="Pages">
      <?php if ($pg > 1): ?>
      <a href="<?= e($href($basePath, array_merge($current, ['page' => $pg - 1]))) ?>" aria-label="Previous page">&lsaquo;</a>
      <?php else: ?>
      <span class="is-disabled" aria-hidden="true">&lsaquo;</span>
      <?php endif; ?>
      <?php $dots = false; for ($n = 1; $n <= $pages; $n++): ?>
        <?php if ($n === 1 || $n === $pages || abs($n - $pg) <= 2): $dots = false; ?>
        <a href="<?= e($href($basePath, array_merge($current, ['page' => $n]))) ?>"<?= $n === $pg ? ' class="is-active" aria-current="page"' : '' ?>><?= $n ?></a>
        <?php elseif (!$dots): $dots = true; ?>
        <span aria-hidden="true">…</span>
        <?php endif; ?>
      <?php endfor; ?>
      <?php if ($pg < $pages): ?>
      <a href="<?= e($href($basePath, array_merge($current, ['page' => $pg + 1]))) ?>" aria-label="Next page">&rsaquo;</a>
      <?php else: ?>
      <span class="is-disabled" aria-hidden="true">&rsaquo;</span>
      <?php endif; ?>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
    </div>
  </div>
</div>

<?php /* One shared Send Inquiry menu, moved next to whichever button was
         clicked. Each choice opens the product page on that profile. */ ?>
<div id="pr-inq-panel" class="g-pr__panel" hidden>
  <?php foreach ($AUDIENCES as $key => [$label, $icon]): ?>
  <a href="#" data-audience="<?= e($key) ?>"><?= gi($icon) ?><span><?= e($label) ?></span></a>
  <?php endforeach; ?>
</div>

<script>
(function () {
  // Rows, dropdowns and Send Inquiry are handled from the document, so they
  // keep working after a search swaps the results in.

  // Open or close a row's details: the row itself or its arrow.
  function toggle(row) {
    var btn = row.querySelector('.g-pr__chev');
    var open = btn.getAttribute('aria-expanded') !== 'true';
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.getElementById(btn.getAttribute('aria-controls')).hidden = !open;
    row.classList.toggle('is-open', open);
  }

  // Send Inquiry menu: one panel, moved next to the button clicked.
  var panel = document.getElementById('pr-inq-panel');
  var active = null;
  function close() {
    panel.hidden = true;
    if (active) { active.setAttribute('aria-expanded', 'false'); }
    active = null;
  }
  function openMenu(btn) {
    if (active === btn) { close(); return; }
    close();
    var u = btn.getAttribute('data-url');
    panel.querySelectorAll('a').forEach(function (a) {
      a.href = u + '?userType=' + encodeURIComponent(a.getAttribute('data-audience'));
    });
    panel.hidden = false;
    var r = btn.getBoundingClientRect();
    panel.style.left = Math.max(16, Math.min(r.left, window.innerWidth - panel.offsetWidth - 16)) + 'px';
    panel.style.top = (r.bottom + 8) + 'px';
    btn.setAttribute('aria-expanded', 'true');
    active = btn;
    panel.querySelector('a').focus();
  }

  document.addEventListener('click', function (e) {
    if (panel.contains(e.target)) { return; }
    var inq = e.target.closest('.g-pr__inq');
    if (inq) { e.stopPropagation(); openMenu(inq); return; }
    if (active) { close(); }
    var row = e.target.closest('.g-pr__row');
    if (row && !e.target.closest('a')) { toggle(row); }   // links keep their own job
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && active) { var b = active; close(); b.focus(); }
  });
  window.addEventListener('scroll', function () { if (active) { close(); } }, true);
  window.addEventListener('resize', function () { if (active) { close(); } });

  // Dropdowns go to the chosen option.
  document.addEventListener('change', function (e) {
    var sel = e.target.closest('select[data-pr-nav]');
    if (!sel) { return; }
    var u = sel.options[sel.selectedIndex].getAttribute('data-url');
    if (u) { window.location.href = u; }
  });

  // Search as you type. A short pause after the last key, then this same
  // page is fetched with ?q= and its count, dropdowns, sidebar links and
  // results replace the ones here. The address follows, so a refresh or a
  // shared link shows the same list. The x clears the words.
  var form  = document.querySelector('.g-pr__search');
  var input = document.getElementById('pr-q');
  var clear = document.getElementById('pr-x');
  if (!form || !input || !window.fetch || !window.DOMParser) { return; }
  var timer = null, last = input.value.trim(), ctrl = null;

  function swap(id, doc) {
    var mine = document.getElementById(id), theirs = doc.getElementById(id);
    if (mine && theirs) { mine.innerHTML = theirs.innerHTML; }
  }
  function search(q) {
    if (q === last) { return; }
    last = q;
    var params = new URLSearchParams(new FormData(form));
    if (q === '') { params.delete('q'); } else { params.set('q', q); }
    var u = form.getAttribute('action') + (params.toString() ? '?' + params.toString() : '');
    if (ctrl) { ctrl.abort(); }
    ctrl = window.AbortController ? new AbortController() : null;
    document.getElementById('pr-results').classList.add('is-loading');
    fetch(u, { credentials: 'same-origin', signal: ctrl ? ctrl.signal : undefined })
      .then(function (res) { return res.text(); })
      .then(function (html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        close();
        ['pr-tab', 'pr-selects', 'pr-side', 'pr-results'].forEach(function (id) { swap(id, doc); });
        document.getElementById('pr-results').classList.remove('is-loading');
        history.replaceState(null, '', u);
      })
      .catch(function (err) {
        if (err && err.name === 'AbortError') { return; }
        window.location.href = u;   // something went wrong: load the page the usual way
      });
  }

  input.addEventListener('input', function () {
    clear.hidden = input.value === '';
    clearTimeout(timer);
    timer = setTimeout(function () { search(input.value.trim()); }, 300);
  });
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    clearTimeout(timer);
    search(input.value.trim());
  });
  clear.addEventListener('click', function (e) {
    e.preventDefault();
    input.value = '';
    clear.hidden = true;
    clearTimeout(timer);
    search('');
    input.focus();
  });
})();
</script>

<?php include INC . '/footer.php'; ?>
