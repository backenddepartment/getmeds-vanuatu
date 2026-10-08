<?php
/**
 * Global header (content guide, page 00 "Global header").
 *
 *  - No top strip: the guide's Soft Mint strip (prescription rule, hours,
 *    phone) is left out at the owner's request. The phone is in the header.
 *  - Desktop: logo left, the seven-item menu centre, the phone number right.
 *    The guide's green "Request a Medicine" header button is left out at the
 *    owner's request; it appears in the page bodies and the mobile bar.
 *  - Phone: logo left, a call icon and the menu button right, and a sticky
 *    bottom bar with "Request a Medicine" and "Call".
 *  - Sticky, and compact (64px) once the page has scrolled 100px (guide.js).
 *
 * Opens <main>; includes/footer.php closes it.
 */
?>
<?php /* Home, About, Articles and Contact pages: the header sits see-through
         over the hero photo. guide.js turns data-overlay into the class, so
         without JS it stays solid. */
$homeOverlay = in_array(current_path(), ['/', '/about', '/articles', '/contact'], true); ?>

<header class="g-header" id="g-header"<?= $homeOverlay ? ' data-overlay' : '' ?>>
  <div class="g-wrap g-header__inner">
    <a class="g-logo" href="<?= e(url('/')) ?>">
      <img src="<?= e(asset('/assets/img/logo-getmeds-vanuatu.png')) ?>" width="200" height="123" alt="Getmeds Vanuatu-Pacific, home">
      <?php if ($homeOverlay): ?>
      <img class="g-logo__light" src="<?= e(asset('/assets/img/logo-getmeds-vanuatu-white.png')) ?>" width="200" height="125" alt="" aria-hidden="true">
      <?php endif; ?>
    </a>

    <?php include INC . '/nav.php'; ?>

    <div class="g-header__phone">
      <span>Call the pharmacy</span>
      <a href="<?= e(tel_url()) ?>"><?= gi('phone') ?><?= e(cfg('phone')) ?></a>
    </div>

    <div class="g-header__tools">
      <a class="g-iconbtn" href="<?= e(tel_url()) ?>" aria-label="Call <?= e(cfg('phone')) ?>"><?= gi('phone') ?></a>
      <button class="g-iconbtn g-menubtn" type="button" aria-expanded="false" aria-controls="g-nav" aria-label="Menu">
        <?= gi('menu', 'g-menubtn__open') ?><?= gi('close', 'g-menubtn__close g-hidden') ?>
      </button>
    </div>
  </div>
</header>

<?php /* Phone only: the two actions every page needs, always in reach. */ ?>
<div class="g-mbar" aria-label="Quick actions">
  <?= g_btn('Request a Medicine', request_url('medicine'), 'primary') ?>
  <?= g_btn('Call', tel_url(), 'secondary', 'phone') ?>
</div>

<main id="main" class="main">
