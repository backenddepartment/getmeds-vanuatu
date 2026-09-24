<?php
/**
 * Search results. Reached from the search field in the nav (GET ?q=).
 *
 * Kept out of search engines (noindex): a results page is a view of other
 * pages, not a page in its own right. When nothing matches, the most likely
 * reason on this site is someone typing a medicine name, and we do not publish
 * a stock list, so the empty state hands that word straight to the enquiry form.
 */
require dirname(__DIR__) . '/includes/bootstrap.php';
require_once INC . '/search.php';

$q = (isset($_GET['q']) && is_string($_GET['q'])) ? trim(mb_substr($_GET['q'], 0, 100, 'UTF-8')) : '';
$results = $q !== '' ? site_search($q) : [];

$page = [
    'title'   => $q !== '' ? 'Search results for “' . $q . '”' : 'Search',
    'desc'    => 'Search the Getmeds Vanuatu website.',
    'ref'     => 'GV-SEARCH',
    'noindex' => true,
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="section shell search" aria-labelledby="search-head">
  <h1 id="search-head">Search</h1>

  <form class="search__form" role="search" method="get" action="<?= e(url('/search/')) ?>">
    <label for="search-q">Search this site</label>
    <div class="search__row">
      <input type="search" id="search-q" name="q" value="<?= e($q) ?>" maxlength="100" autocomplete="off">
      <button class="btn btn--primary" type="submit">Search</button>
    </div>
  </form>

  <?php if ($q === ''): ?>
  <p class="search__status quiet">Type a word or two, for example “delivery”, “prescription” or “cold chain”.</p>

  <?php elseif (!$results): ?>
  <p class="search__status" role="status">No pages matched <strong>“<?= e($q) ?>”</strong>.</p>
  <div class="prose">
    <p>
      Try fewer words, or a different spelling. Looking for a particular medicine? We do
      not publish a stock list, but a pharmacist will tell you whether we can supply it.
    </p>
  </div>
  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/order') . '?medicine=' . rawurlencode($q)) ?>">Ask about “<?= e($q) ?>”</a>
  </div>

  <?php else: $n = count($results); ?>
  <p class="search__status" role="status"><?= $n ?> <?= $n === 1 ? 'page' : 'pages' ?> matched <strong>“<?= e($q) ?>”</strong>.</p>
  <ul class="chunks search__results">
    <?php foreach ($results as $r): ?>
    <li>
      <a href="<?= e(url($r['url'])) ?>">
        <span class="chunk__name"><?= e($r['title']) ?></span>
        <?php /* Already escaped by search_snippet(), with <mark> around each match. */ ?>
        <span class="chunk__blurb"><?= $r['snippet'] ?></span>
        <?= icon('arrow', 'chunk__go') ?>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</section>

<?php include INC . '/footer.php'; ?>
