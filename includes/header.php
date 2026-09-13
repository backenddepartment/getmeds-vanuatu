<?php
/**
 * Masthead: brand and nav. The nav carries the site search and, last and at
 * the far right, Contact. The phone number and hours sit in the footer on
 * every page and in the home hero; "Ask About a Medicine" is reached from the
 * page body (the hero, and each page's own call to action).
 *
 * Source order is brand, Menu button, nav. On a wide screen that is also the
 * left-to-right order of the header row, so keyboard focus travels the way the
 * eye does, and at every width the nav follows directly after the button that
 * opens it. The layouts themselves live in site.css §9.
 *
 * Opens <main>; includes/footer.php closes it.
 */
?>
<header class="masthead">
  <div class="masthead__inner shell">

    <a class="wordmark" href="<?= e(url('/')) ?>">
      <img class="wordmark__logo" src="<?= e(asset('/assets/img/logo-getmeds-vanuatu.png')) ?>" width="200" height="123" alt="<?= e(cfg('site_name') . ', ' . cfg('site_descriptor')) ?>">
    </a>

    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="sitenav">
      <span class="menu-toggle__bars" aria-hidden="true"><span></span><span></span><span></span></span>
      <span class="menu-toggle__text">Menu</span>
    </button>

    <?php include INC . '/nav.php'; ?>

  </div>
</header>

<main id="main" class="main">
