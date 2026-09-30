<?php
/**
 * Articles hub (content guide extension): global healthcare news.
 *
 * The news is real: headlines from independent outlets, fetched from
 * newsdata.io by includes/news.php and linked to the original article. Nothing
 * is written or reworded here. If there is no API key, or the service cannot
 * be reached and nothing is cached, the section says so plainly.
 *
 * The "From Getmeds Vanuatu / Our guides" cards (links to Oncology, Named
 * Patient Supply and How it works) were removed from this page at the owner's
 * request on 2026-09-30.
 */
require __DIR__ . '/../includes/bootstrap.php';
require INC . '/news.php';

$page = [
    'title'     => 'Healthcare News and Guides',
    'seo_title' => 'Global Healthcare News and Guides — Getmeds Vanuatu',
    'desc'      => 'Global healthcare news on pharmaceuticals, medicines, clinical research, biotechnology and global health, from independent news outlets.',
];

$news = news_items();

include INC . '/head.php';
include INC . '/header.php';

?>

<?php /* Full-screen photo hero, same size, photo and style as the home page's hero
         (the navbar sits over it, see-through: includes/header.php, guide.css
         "Home hero"). */ ?>
<section class="g-homehero" aria-labelledby="articles-h">
  <div class="g-homehero__media">
    <img src="<?= e(asset('/assets/img/homeherosection-1200.jpg')) ?>"
         srcset="<?= e(asset('/assets/img/homeherosection-800.jpg')) ?> 800w, <?= e(asset('/assets/img/homeherosection-1200.jpg')) ?> 1200w, <?= e(asset('/assets/img/homeherosection.png')) ?> 1600w"
         sizes="100vw" width="1200" height="628" alt="" loading="eager" fetchpriority="high" decoding="async">
  </div>
  <div class="g-wrap g-homehero__inner">
    <p class="g-homehero__kicker">From the Getmeds Vanuatu team</p>
    <h1 class="g-homehero__title" id="articles-h">Guides and Articles</h1>
    <p class="g-homehero__lede">Healthcare news from around the world, on pharmaceuticals, medicines, clinical research and global health.</p>
    <div class="g-homehero__btns">
      <a class="g-homehero__btn g-homehero__btn--fill" href="<?= e(url('/order')) ?>">Order a Medicine</a>
      <a class="g-homehero__btn g-homehero__btn--line" href="<?= e(tel_url()) ?>">Call <?= e(cfg('phone')) ?></a>
    </div>
  </div>
</section>

<?php /* The search box filters the news: #articles-list wraps it, and the news
         grid is hidden when nothing matches (guide.js, "Live filter"). */ ?>
<div id="articles-list">

<section class="g-sec g-bg-white g-news-sec" aria-labelledby="news-h">
  <div class="g-wrap g-news-head">
    <h2 id="news-h">Global<br>Healthcare News</h2>
    <div class="g-news-head__side">
      <p>Curated, worldwide headlines on pharmaceuticals, medicines, clinical research, biotechnology, and global health — sourced from independent news outlets and linked back to the original article.</p>
      <div class="g-articles-search">
        <?= gi('search') ?>
        <label class="g-sr" for="articles-q">Search news</label>
        <input class="g-articles-search__input" type="search" id="articles-q" data-filter="#articles-list" placeholder="Search news&hellip;" autocomplete="off" maxlength="80">
      </div>
    </div>
  </div>

  <div class="g-wrap g-news-body" data-filter-group>
    <?php if ($news): ?>
    <?php /* Headlines are the outlets' own words: left out of the Bislama swap. */ ?>
    <?php /* Split into pages of news_per_page by guide.js ("Pages"). Without
             JavaScript every headline shows on one page. */ ?>
    <div class="g-articles-grid" id="news-grid" data-no-i18n data-paginate="<?= (int) cfg('news_per_page', 9) ?>" data-pager="news-pager">
      <?php foreach ($news as $n): ?>
      <a class="g-article-card" data-filter-item href="<?= e($n['link']) ?>" target="_blank" rel="noopener noreferrer nofollow">
        <div class="g-article-card__media<?= $n['image'] === '' ? ' g-article-card__media--none' : '' ?>">
          <?php /* The stand-in picture, behind the outlet's photo: it shows when
                   an article has no photo, or its photo fails to load (guide.js
                   removes a broken one). Resized copies of
                   assets/img/articlenoimagefallback.png. */ ?>
          <picture class="g-article-card__ph" aria-hidden="true">
            <source type="image/webp" srcset="<?= e(asset('/assets/img/articlenoimagefallback-800.webp')) ?> 800w, <?= e(asset('/assets/img/articlenoimagefallback-1200.webp')) ?> 1200w" sizes="(max-width: 47.99em) 100vw, 400px">
            <img src="<?= e(asset('/assets/img/articlenoimagefallback-800.jpg')) ?>" srcset="<?= e(asset('/assets/img/articlenoimagefallback-800.jpg')) ?> 800w, <?= e(asset('/assets/img/articlenoimagefallback-1200.jpg')) ?> 1200w" sizes="(max-width: 47.99em) 100vw, 400px" alt="" width="800" height="450" loading="lazy" decoding="async">
          </picture>
          <?php if ($n['image'] !== ''): ?>
          <img src="<?= e($n['image']) ?>" alt="" width="800" height="500" loading="lazy" decoding="async" referrerpolicy="no-referrer" data-fallback>
          <?php endif; ?>
          <?php if ($n['category'] !== ''): ?><span class="g-article-card__badge"><?= e($n['category']) ?></span><?php endif; ?>
          <?php if ($n['time']): ?><span class="g-article-card__tag"><?= e(gmdate('j M Y', $n['time'])) ?></span><?php endif; ?>
        </div>
        <h3 class="g-article-card__title"><?= e($n['title']) ?></h3>
        <?php if ($n['excerpt'] !== ''): ?><p class="g-article-card__excerpt"><?= e($n['excerpt']) ?></p><?php endif; ?>
        <div class="g-article-card__foot">
          <?php /* The outlet's own icon. The globe behind it shows when the
                   outlet has none, or its icon fails to load (guide.js). */ ?>
          <span class="g-article-card__source">
            <?php if (($n['icon'] ?? '') !== ''): ?><img class="g-article-card__favicon" src="<?= e($n['icon']) ?>" alt="" width="20" height="20" loading="lazy" decoding="async" referrerpolicy="no-referrer" data-fallback><?php endif; ?><?= gi('globe') ?>
            <?= e($n['source'] !== '' ? $n['source'] : 'News outlet') ?>
          </span>
          <span class="g-article-card__go">Read article <?= gi('arrow') ?><span class="g-sr"> (opens in a new tab)</span></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <nav class="g-pager" id="news-pager" aria-label="News pages" hidden></nav>
    <p class="g-news-note">These headlines come from independent news outlets and open on their websites. They are general news, not medical advice, and Getmeds Vanuatu does not endorse the products or treatments they mention. Ask your doctor or pharmacist about your own treatment.</p>
    <?php else: ?>
    <p class="g-news-empty" role="status">There are no headlines to show right now. Please check back later.</p>
    <?php endif; ?>
  </div>
</section>

<div class="g-wrap">
  <?php /* Shown by guide.js ("Live filter") when a search matches nothing;
           it also writes the search words into [data-filter-query]. */ ?>
  <div class="g-hidden g-articles-empty" data-filter-empty role="status">
    <svg class="g-articles-empty__art" viewBox="0 0 440 300" width="440" height="300" aria-hidden="true" focusable="false">
      <ellipse cx="220" cy="272" rx="190" ry="14" fill="#E8F4FB"/>
      <circle cx="96" cy="208" r="5" fill="#BFE3F5"/><circle cx="176" cy="34" r="4" fill="#BFE3F5"/>
      <circle cx="352" cy="54" r="5" fill="#BFE3F5"/><circle cx="392" cy="164" r="4" fill="#BFE3F5"/>
      <g transform="translate(110 78)" fill="#1D9FDA">
        <circle r="17"/><circle r="7" fill="#fff"/>
        <g><rect x="-5" y="-26" width="10" height="11" rx="2"/><rect x="-5" y="15" width="10" height="11" rx="2"/><rect x="-26" y="-5" width="11" height="10" rx="2"/><rect x="15" y="-5" width="11" height="10" rx="2"/></g>
        <g transform="rotate(45)"><rect x="-5" y="-26" width="10" height="11" rx="2"/><rect x="-5" y="15" width="10" height="11" rx="2"/><rect x="-26" y="-5" width="11" height="10" rx="2"/><rect x="15" y="-5" width="11" height="10" rx="2"/></g>
      </g>
      <g transform="translate(152 44)" fill="#BFE3F5">
        <circle r="9"/><circle r="4" fill="#fff"/>
        <rect x="-3" y="-14" width="6" height="6" rx="1"/><rect x="-3" y="8" width="6" height="6" rx="1"/><rect x="-14" y="-3" width="6" height="6" rx="1"/><rect x="8" y="-3" width="6" height="6" rx="1"/>
      </g>
      <g transform="translate(326 64) rotate(12)">
        <rect x="-26" y="-32" width="52" height="64" rx="8" fill="#fff" stroke="#8ACDEB" stroke-width="3"/>
        <path d="M-13 -14h26M-13 -2h26M-13 10h16" stroke="#8ACDEB" stroke-width="4" stroke-linecap="round"/>
      </g>
      <rect x="92" y="112" width="220" height="150" rx="12" fill="#fff" stroke="#8ACDEB" stroke-width="3"/>
      <path d="M92 124a12 12 0 0 1 12-12h196a12 12 0 0 1 12 12v16H92z" fill="#E8F4FB"/>
      <circle cx="112" cy="126" r="4.5" fill="#8ACDEB"/><circle cx="126" cy="126" r="4.5" fill="#8ACDEB"/><circle cx="140" cy="126" r="4.5" fill="#8ACDEB"/>
      <rect x="156" y="120" width="140" height="12" rx="6" fill="#fff"/>
      <rect x="110" y="156" width="72" height="9" rx="4.5" fill="#BFE3F5"/>
      <rect x="110" y="173" width="58" height="6" rx="3" fill="#E8F4FB"/><rect x="110" y="185" width="66" height="6" rx="3" fill="#E8F4FB"/>
      <rect x="110" y="204" width="40" height="11" rx="5.5" fill="#1D9FDA"/>
      <rect x="198" y="152" width="96" height="62" rx="4" fill="#F5FAFD" stroke="#8ACDEB" stroke-width="2" stroke-dasharray="6 5"/>
      <rect x="110" y="226" width="54" height="24" rx="6" fill="#E8F4FB"/><rect x="172" y="226" width="54" height="24" rx="6" fill="#E8F4FB"/><rect x="234" y="226" width="54" height="24" rx="6" fill="#E8F4FB"/>
      <path d="M318 238l38 38" stroke="#0B2A5B" stroke-width="16" stroke-linecap="round"/>
      <circle cx="286" cy="206" r="46" fill="#fff" fill-opacity=".92" stroke="#0B2A5B" stroke-width="9"/>
      <path d="M262 190a30 30 0 0 1 16-14" stroke="#BFE3F5" stroke-width="5" stroke-linecap="round" fill="none"/>
      <rect x="272" y="188" width="30" height="34" rx="5" fill="#E8F4FB"/>
      <path d="M279 198h16M279 206h16M279 214h10" stroke="#1D9FDA" stroke-width="3" stroke-linecap="round"/>
      <circle cx="310" cy="226" r="12" fill="#E5484D" stroke="#fff" stroke-width="3"/>
      <path d="M305 221l10 10M315 221l-10 10" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
    </svg>
    <h3 class="g-articles-empty__title">No articles found</h3>
    <p class="g-articles-empty__text">Nothing matches &ldquo;<span data-filter-query data-no-i18n></span>&rdquo;. Try a different word, or clear the search to see every article.</p>
  </div>
</div>

</div>

<?php include INC . '/footer.php'; ?>
