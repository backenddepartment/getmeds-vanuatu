<?php
/**
 * Articles / guides hub (content guide extension).
 *
 * Replaced the short "coming soon" placeholder with a proper hub once we had
 * a design for it. There is no live third-party news feed here — that would
 * mean fabricating headlines and attributing them to outlets we never
 * sourced from. Instead this lists the site's own real guides (Oncology,
 * Named Patient Supply, How it works) in the same card-grid layout, each
 * genuinely linking to that page.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Guides and Articles',
    'seo_title' => 'Guides and Articles — Getmeds Vanuatu',
    'desc'      => 'Practical guides from Getmeds Vanuatu on cancer medicines, Named Patient Supply and how ordering works.',
];

include INC . '/head.php';
include INC . '/header.php';

$articles = [
    [
        'tag'     => 'Cancer Care',
        'title'   => 'Chemotherapy and Cancer Medicines in Port Vila',
        'excerpt' => 'Getmeds Vanuatu is the first chemotherapy pharmacy in the Pacific. See how we supply, check and plan a treatment cycle.',
        'url'     => '/oncology',
        'img'     => '/assets/img/oncology-hero-720.jpg',
    ],
    [
        'tag'     => 'Patient Access',
        'title'   => 'How Named Patient Supply Works',
        'excerpt' => "When a medicine isn't normally available in Vanuatu, Named Patient Supply lets us source it for one named patient.",
        'url'     => '/named-patient-supply',
        'img'     => '/assets/img/photo/ampoules-800.jpg',
    ],
    [
        'tag'     => 'How It Works',
        'title'   => 'From Prescription to Pick-up',
        'excerpt' => 'The five steps every order follows, from sending your prescription to collecting your medicine.',
        'url'     => '/how-it-works',
        'img'     => '/assets/img/photo/color/script-800.jpg',
    ],
];
?>

<section class="g-sec g-bg-white g-articles-hero" aria-labelledby="articles-h">
  <div class="g-wrap g-articles-head">
    <h1 id="articles-h">Guides and<br>Articles</h1>
    <p>Practical guides from the Getmeds Vanuatu team on cancer medicines, Named Patient Supply and how ordering works — in plain language, with a link through to the full page.</p>
  </div>
  <div class="g-wrap">
    <div class="g-articles-search">
      <?= gi('search') ?>
      <label class="g-sr" for="articles-q">Search articles</label>
      <input class="g-articles-search__input" type="search" id="articles-q" data-filter="#articles-list" placeholder="Search articles&hellip;" autocomplete="off" maxlength="80">
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-sec--flushtop">
  <div class="g-wrap">
    <div class="g-articles-grid" id="articles-list">
      <?php foreach ($articles as $a): ?>
      <a class="g-article-card" data-filter-item href="<?= e(url($a['url'])) ?>">
        <div class="g-article-card__media">
          <img src="<?= e(asset($a['img'])) ?>" alt="" width="800" height="500" loading="lazy" decoding="async">
          <span class="g-article-card__badge"><?= e($a['tag']) ?></span>
          <span class="g-article-card__tag">Guide</span>
        </div>
        <h2 class="g-article-card__title"><?= e($a['title']) ?></h2>
        <p class="g-article-card__excerpt"><?= e($a['excerpt']) ?></p>
        <div class="g-article-card__foot">
          <span class="g-article-card__source"><?= gi('shield') ?> Getmeds Vanuatu</span>
          <span class="g-article-card__go">Read more <?= gi('arrow') ?></span>
        </div>
      </a>
      <?php endforeach; ?>
      <p class="g-hidden g-muted g-articles-empty" data-filter-empty role="status">No articles match your search.</p>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
