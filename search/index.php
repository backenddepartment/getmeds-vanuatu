<?php
/**
 * Search results (GET ?q=). Not in the content guide and not in the nav; kept
 * working and restyled to the guide's design system. noindex: a results page
 * is a view of other pages. When nothing matches, the most likely reason is a
 * medicine name, and we do not publish a stock list, so the empty state hands
 * that word straight to the enquiry form.
 */
require dirname(__DIR__) . '/includes/bootstrap.php';
require_once INC . '/search.php';

$q = (isset($_GET['q']) && is_string($_GET['q'])) ? trim(mb_substr($_GET['q'], 0, 100, 'UTF-8')) : '';
$results = $q !== '' ? site_search($q) : [];

$page = [
    'title'   => $q !== '' ? 'Search results for “' . $q . '”' : 'Search',
    'desc'    => 'Search the Getmeds Vanuatu website.',
    'noindex' => true,
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-hero--sky g-hero--short">
  <div class="g-wrap g-narrow">
    <?php g_crumbs([['Home', '/'], ['Search', null]]); ?>
    <h1>Search</h1>
    <form class="g-bigsearch g-mt" role="search" method="get" action="<?= e(url('/search/')) ?>">
      <?= gi('search') ?>
      <label class="g-sr" for="search-q">Search this site</label>
      <input type="search" id="search-q" name="q" value="<?= e($q) ?>" maxlength="100" autocomplete="off" placeholder="Search this site">
      <button class="g-btn g-btn--primary g-search__go" type="submit">Search</button>
    </form>
  </div>
</section>

<section class="g-sec g-bg-white g-sec--tight">
  <div class="g-wrap g-narrow">
    <?php if ($q === ''): ?>
    <p class="g-muted">Type a word or two, for example “delivery”, “prescription” or “cold chain”.</p>

    <?php elseif (!$results): ?>
    <p role="status">No pages matched <strong>“<?= e($q) ?>”</strong>.</p>
    <p class="g-mt-sm">Try fewer words, or a different spelling. Looking for a particular medicine? We do not publish a stock list, but a pharmacist will tell you whether we can supply it.</p>
    <div class="g-btns g-mt">
      <?= g_btn('Ask about “' . $q . '”', request_url('medicine', '&medicine=' . rawurlencode($q)), 'primary') ?>
      <?= g_call_btn('Call Us') ?>
    </div>

    <?php else: $n = count($results); ?>
    <p role="status"><?= $n ?> <?= $n === 1 ? 'page' : 'pages' ?> matched <strong>“<?= e($q) ?>”</strong>.</p>
    <ul class="g-results g-mt" role="list">
      <?php foreach ($results as $r): ?>
      <li>
        <a class="g-card g-card--link g-card--compact" href="<?= e(url($r['url'])) ?>">
          <h2 class="g-results__title"><?= e($r['title']) ?></h2>
          <?php /* Already escaped by search_snippet(), with <mark> around each match. */ ?>
          <p class="g-results__snip"><?= $r['snippet'] ?></p>
          <span class="g-card__go"><?= e($r['url']) ?> <?= gi('arrow') ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
