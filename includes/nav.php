<?php
/**
 * Main navigation: the items from nav_tree(). About Us and Medicines open a
 * dropdown on hover or focus without JavaScript; guide.js adds click and
 * Escape handling. Both also carry their own link (a "linkable" parent): the
 * label goes straight to the page, and a separate caret button opens the
 * dropdown. Dropdown links are page names only. Medicines lays its
 * categories out in a grid ('grid') and puts a button under its intro
 * ('button'). On a phone the list drops open under the header.
 */
$here = current_path();
$tree = nav_tree();
$last = count($tree) - 1;
// Echo the query back into the field on the results page, and only there.
$navQ = ($here === '/search' && isset($_GET['q']) && is_string($_GET['q'])) ? $_GET['q'] : '';

/* The wider Getmeds family, for the Global Network dropdown. null = this
   site (links home, marked current, opens in the same tab). */
$network = [
    ['Getmeds Philippines',          'https://getmeds.ph'],
    ['Getmeds India',                'https://getmedshealthcare.com'],
    ['Getmeds Vanuatu',              null],
    ['Getmeds South East Asia',      'https://getmedshealthcare.com/coming-soon?site=Getmeds%20South%20East%20Asia'],
    ['Getmeds Latin',                'https://getmedshealthcare.com/coming-soon?site=Getmeds%20Latin'],
    ['Bishnoi Omniverse India',      'https://getmedshealthcare.com/coming-soon?site=Bishnoi%20Omniverse%20India'],
    ['Bishnoi Omniverse Philippines','https://getmedshealthcare.com/coming-soon?site=Bishnoi%20Omniverse%20Philippines'],
    ['Naresh Bishnoi Foundation',    'https://getmedshealthcare.com/coming-soon?site=Naresh%20Bishnoi%20Foundation'],
    ['Naresh Bishnoi',               'https://getmedshealthcare.com/coming-soon?site=Naresh%20Bishnoi'],
    ['UNGC',                         'https://getmedshealthcare.com/coming-soon?site=UNGC'],
    ['2MG Incorporated',             'https://2mginc.com/'],
];
?>
<nav class="g-nav" id="g-nav" aria-label="Main">
  <ul class="g-nav__list">
    <?php foreach ($tree as $i => $item):
        $kids   = $item['children'] ?? [];
        $isHere = $item['url'] === '/' ? $here === '/' : in_section($item['url']);
        $btn    = $item['menu_intro']['button'] ?? null;
        if ($btn) {
            $isHere = $isHere || in_section($btn[1]);
        }
        foreach ($kids as $k) {
            $kPath  = (string) parse_url($k['url'], PHP_URL_PATH);
            $isHere = $isHere || (!empty($k['exact']) ? $here === $kPath : in_section($kPath));
        }
    ?>
    <?php if ($i === $last): /* Site search, GET to /search, so it works without JS. */ ?>
    <li class="g-nav__item g-nav__item--search">
      <?php /* Suggestions as you type (assets/js/search-suggest.js): medicines,
               their categories and pages, from /search/?format=json. Enter
               opens the full results page. */ ?>
      <form class="g-navsearch" role="search" method="get" action="<?= e(url('/search/')) ?>" data-suggest="<?= e(url('/search/')) ?>">
        <label class="g-sr" for="g-navsearch-q">Search medicines and pages</label>
        <input class="g-navsearch__input" type="search" id="g-navsearch-q" name="q"
               placeholder="How can we help you?" autocomplete="off" maxlength="100" value="<?= e($navQ) ?>"
               role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="g-suggest">
        <button class="g-navsearch__btn" type="submit" aria-label="Search"><?= gi('search') ?></button>
        <div class="g-suggest" id="g-suggest" hidden></div>
        <template id="g-suggest-empty"><?= g_noresult('g-noresult--sm') ?></template>
        <template id="g-suggest-icons"><span data-i="med"><?= gi('pill') ?></span><span data-i="cat"><?= gi('box') ?></span><span data-i="page"><?= gi('clipboard') ?></span><span data-i="go"><?= gi('arrow') ?></span></template>
      </form>
    </li>
    <?php endif; ?>
    <li class="g-nav__item<?= $kids ? ' g-nav__item--drop' : '' ?><?= !empty($item['linkable']) ? ' g-nav__item--linkable' : '' ?><?= $isHere ? ' is-current' : '' ?>">
      <?php if ($kids && !empty($item['linkable'])): ?>
      <a class="g-nav__link" href="<?= e(url($item['url'])) ?>"<?= $here === $item['url'] ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a>
      <button class="g-nav__caret" type="button" aria-expanded="false" aria-controls="g-drop-<?= $i ?>" aria-label="<?= e($item['label']) ?> menu">
        <?= gi('chev-down') ?>
      </button>
      <div class="g-nav__drop g-nav__drop--mega" id="g-drop-<?= $i ?>">
        <?php if (!empty($item['menu_intro'])): ?>
        <div class="g-nav__drop__intro">
          <h2><?= e($item['menu_intro']['heading']) ?></h2>
          <p><?= e($item['menu_intro']['text']) ?></p>
          <?php if ($btn): ?>
          <a class="g-nav__drop__btn" href="<?= e(url($btn[1])) ?>"><?= e($btn[0]) ?></a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <ul class="g-nav__drop__links<?= !empty($item['grid']) ? ' g-nav__drop__links--grid' : '' ?>">
          <?php foreach ($kids as $k): ?>
          <li>
            <a href="<?= e(url($k['url'])) ?>"<?= $here === $k['url'] ? ' aria-current="page"' : '' ?>>
              <span class="g-nav__drop__label"><?= e($k['label']) ?></span><?php if (empty($item['grid'])): ?><?= gi('arrow-ur', 'g-nav__drop__go') ?><?php endif; ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php elseif ($kids): ?>
      <button class="g-nav__link" type="button" aria-expanded="false" aria-controls="g-drop-<?= $i ?>">
        <?= e($item['label']) ?><?= gi('chev-down') ?>
      </button>
      <ul class="g-nav__drop" id="g-drop-<?= $i ?>">
        <?php foreach ($kids as $k): ?>
        <li><a href="<?= e(url($k['url'])) ?>"<?= $here === $k['url'] ? ' aria-current="page"' : '' ?>><?= e($k['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
      <a class="g-nav__link" href="<?= e(url($item['url'])) ?>"<?= $here === $item['url'] ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
    <?php /* Language: English / Bislama, switched in the browser (assets/js/i18n.js). */ ?>
    <li class="g-nav__item g-nav__item--drop g-nav__item--lang" data-no-i18n>
      <button class="g-nav__link" type="button" aria-expanded="false" aria-controls="g-drop-lang" aria-label="Language">
        <img class="g-lang__flag" data-lang-flag width="22" height="14" alt=""
             src="<?= e(asset('/assets/img/flag-en.svg')) ?>"
             data-flag-en="<?= e(asset('/assets/img/flag-en.svg')) ?>"
             data-flag-bi="<?= e(asset('/assets/img/flag-bi.svg')) ?>">
        <span data-lang-current>English</span><?= gi('chev-down') ?>
      </button>
      <ul class="g-nav__drop g-nav__drop--lang" id="g-drop-lang">
        <li class="g-lang__head" aria-hidden="true">Language</li>
        <li><button type="button" class="g-lang__opt" data-lang="en" lang="en">
          <img class="g-lang__flag" src="<?= e(asset('/assets/img/flag-en.svg')) ?>" width="20" height="13" alt="">English</button></li>
        <li><button type="button" class="g-lang__opt" data-lang="bi" lang="bi">
          <img class="g-lang__flag" src="<?= e(asset('/assets/img/flag-bi.svg')) ?>" width="20" height="13" alt="">Bislama</button></li>
      </ul>
    </li>
    <?php /* Global Network: the wider Getmeds family. The logo is the trigger
             on desktop; in the phone menu it reads as a plain labelled row.
             A standard --drop item, so guide.js gives it the same hover,
             click, Escape and header-solid behaviour as the mega menus. */ ?>
    <li class="g-nav__item g-nav__item--drop g-nav__item--gn" data-no-i18n>
      <button class="g-nav__link" type="button" aria-expanded="false" aria-controls="g-drop-gn" aria-label="Global Network">
        <img class="g-gn__logo" src="<?= e(asset('/assets/img/globalnetworklogo.png')) ?>" alt="" height="34">
        <span class="g-gn__name">Global Network</span><?= gi('chev-down') ?>
      </button>
      <div class="g-nav__drop g-nav__drop--mega g-nav__drop--gn" id="g-drop-gn">
        <div class="g-nav__drop__intro">
          <h2>Global Network</h2>
          <p>Getmeds Healthcare&rsquo;s growing footprint across the world &mdash; connect with any of our regional sites and trusted partners.</p>
        </div>
        <ul class="g-nav__drop__links g-gn__links">
          <?php foreach ($network as [$gnLabel, $gnHref]): ?>
          <li>
            <?php if ($gnHref === null): ?>
            <a href="<?= e(url('/')) ?>" aria-current="page"><span class="g-nav__drop__label"><?= e($gnLabel) ?></span></a>
            <?php else: ?>
            <a href="<?= e($gnHref) ?>" target="_blank" rel="noopener"><span class="g-nav__drop__label"><?= e($gnLabel) ?></span></a>
            <?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php /* The world map, as on Getmeds India: desktop only. The blue
                 location dots are part of the picture. AVIF, then WebP; the
                 902 KB PNG it was made from is not served. */ ?>
        <div class="g-gn__map" aria-hidden="true">
          <picture>
            <source type="image/avif" srcset="<?= e(asset('/assets/img/countries.avif')) ?>">
            <img src="<?= e(asset('/assets/img/countries.webp')) ?>" alt="" width="1672" height="941" decoding="async" loading="lazy">
          </picture>
        </div>
      </div>
    </li>
  </ul>
</nav>
