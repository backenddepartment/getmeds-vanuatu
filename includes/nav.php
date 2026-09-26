<?php
/**
 * Main navigation: the seven items from nav_tree(). Medicines opens a small
 * dropdown on hover or focus without JavaScript; guide.js adds click and
 * Escape handling. On a phone the list drops open under the header.
 */
$here = current_path();
$tree = nav_tree();
$last = count($tree) - 1;
// Echo the query back into the field on the results page, and only there.
$navQ = ($here === '/search' && isset($_GET['q']) && is_string($_GET['q'])) ? $_GET['q'] : '';
?>
<nav class="g-nav" id="g-nav" aria-label="Main">
  <ul class="g-nav__list">
    <?php foreach ($tree as $i => $item):
        $kids   = $item['children'] ?? [];
        $isHere = $item['url'] === '/' ? $here === '/' : in_section($item['url']);
        if ($kids) {
            foreach ($kids as $k) {
                $isHere = $isHere || in_section($k['url']);
            }
        }
    ?>
    <?php if ($i === $last): /* Site search, GET to /search, so it works without JS. */ ?>
    <li class="g-nav__item g-nav__item--search">
      <form class="g-navsearch" role="search" method="get" action="<?= e(url('/search/')) ?>">
        <label class="g-sr" for="g-navsearch-q">Search this site</label>
        <input class="g-navsearch__input" type="search" id="g-navsearch-q" name="q"
               placeholder="How can we help you?" autocomplete="off" maxlength="100" value="<?= e($navQ) ?>">
        <button class="g-navsearch__btn" type="submit" aria-label="Search"><?= gi('search') ?></button>
      </form>
    </li>
    <?php endif; ?>
    <li class="g-nav__item<?= $kids ? ' g-nav__item--drop' : '' ?><?= $isHere ? ' is-current' : '' ?>">
      <?php if ($kids): ?>
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
        <?= gi('globe') ?><span data-lang-current>English</span><?= gi('chev-down') ?>
      </button>
      <ul class="g-nav__drop" id="g-drop-lang">
        <li><button type="button" class="g-lang__opt" data-lang="en" lang="en">English</button></li>
        <li><button type="button" class="g-lang__opt" data-lang="bi" lang="bi">Bislama</button></li>
      </ul>
    </li>
  </ul>
</nav>
