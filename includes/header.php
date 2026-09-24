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
require_once INC . '/icons.php';
?>
<?php /* On the home page the masthead sits over the hero photograph: transparent,
         with the white logo and white nav. site.js turns it solid once the hero
         scrolls away or the phone menu opens; without JS it stays solid. */
$overlay = (current_path() === '/'); ?>
<header class="masthead<?= $overlay ? ' masthead--overlay' : '' ?>">
  <div class="masthead__inner shell">

    <a class="wordmark" href="<?= e(url('/')) ?>">
      <img class="wordmark__logo" src="<?= e(asset('/assets/img/logo-getmeds-vanuatu.png')) ?>" width="200" height="123" alt="<?= e(cfg('site_name') . ', ' . cfg('site_descriptor')) ?>">
      <?php if ($overlay): /* Shown instead of the colour logo while over the hero. */ ?>
      <img class="wordmark__logo wordmark__logo--light" src="<?= e(asset('/assets/img/logo-getmeds-vanuatu-white.png')) ?>" width="200" height="125" alt="" aria-hidden="true">
      <?php endif; ?>
    </a>

    <?php /* The order button. On every page, at every width, in the brand row
             rather than inside the menu, because "where do I put my order" was
             the question the old masthead could not answer. */ ?>
    <a class="btn btn--primary masthead__cta" href="<?= e(url('/order')) ?>">
      <?= icon('script') ?><span>Order<span class="masthead__cta-long"> a Medicine</span></span>
    </a>

    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="sitenav">
      <span class="menu-toggle__bars" aria-hidden="true"><span></span><span></span><span></span></span>
      <span class="menu-toggle__text">Menu</span>
    </button>

    <?php include INC . '/nav.php'; ?>

  </div>
</header>

<main id="main" class="main">
