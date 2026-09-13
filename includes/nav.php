<?php
/**
 * Main navigation. Six items, never more. Dropdowns hold at most five.
 *
 * Works with no JavaScript at all: the panel opens on hover and on focus-within,
 * and every parent label is itself a link to a landing page that lists the same
 * children as body content. Nothing is reachable only through a dropdown.
 *
 * With JavaScript the disclosure buttons appear and the panel becomes
 * click-operated instead of hover-operated, so a tremor or an imprecise pointer
 * cannot close a menu that is being read.
 *
 * The site search sits just before the last item (Contact). On a wide screen it
 * and Contact are pushed to the right end of the row; on a phone the search is
 * a full-width field inside the open menu. It submits to /search with GET, so
 * it works without JavaScript.
 */
$here  = current_path();
$tree  = nav_tree();
$last  = count($tree) - 1;
// Echo the query back into the field on the results page, and only there.
$navQ  = ($here === '/search' && isset($_GET['q']) && is_string($_GET['q'])) ? $_GET['q'] : '';
?>
<nav id="sitenav" class="nav" aria-label="Main">
  <ul class="nav__list">
    <?php foreach ($tree as $i => $item):
        $hasPanel = !empty($item['children']);
        $isHere    = ($here === rtrim($item['url'], '/') || ($item['url'] === '/' && $here === '/'));
        $isSection = in_section($item['url']) && $item['url'] !== '/';
        $panelId   = 'navpanel-' . $i;
        $classes   = 'nav__item' . ($hasPanel ? ' nav__item--parent' : '')
                   . ($isSection || $isHere ? ' is-current' : '');
    ?>
    <?php if ($i === $last): ?>
    <li class="nav__item nav__item--search">
      <form class="navsearch" role="search" method="get" action="<?= e(url('/search/')) ?>">
        <label class="u-hidden" for="navsearch-q">Search this site</label>
        <input class="navsearch__input" type="search" id="navsearch-q" name="q"
               placeholder="How can we help you?" autocomplete="off" maxlength="100"
               value="<?= e($navQ) ?>">
        <button class="navsearch__btn" type="submit"><?= icon('search') ?><span class="u-hidden">Search</span></button>
      </form>
    </li>
    <?php endif; ?>
    <li class="<?= $classes ?>">
      <a class="nav__link" href="<?= e(url($item['url'])) ?>"<?= $isHere ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a>

      <?php if ($hasPanel): ?>
      <button class="nav__disc" type="button" aria-expanded="false" aria-controls="<?= $panelId ?>">
        <span class="u-hidden">Show pages under <?= e($item['label']) ?></span>
        <svg class="icon icon--chev" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
          <path d="M3.5 6L8 10.5 12.5 6"/>
        </svg>
      </button>

      <div class="nav__panel" id="<?= $panelId ?>">
        <ul class="nav__sub">
          <?php foreach ($item['children'] as $child):
              $childHere = ($here === rtrim($child['url'], '/')); ?>
          <li>
            <a href="<?= e(url($child['url'])) ?>"<?= $childHere ? ' aria-current="page"' : '' ?>><?= e($child['label']) ?></a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
</nav>
