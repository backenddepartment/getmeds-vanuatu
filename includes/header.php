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
<?php /* Home, About, Articles and Contact pages: the header
         sits see-through over the hero photo. guide.js turns data-overlay
         into the class, so without JS it stays solid. */
$homeOverlay = in_array(current_path(), ['/', '/about', '/articles', '/contact'], true)
    || !empty($headerOverlay); /* set by a page, e.g. the Product Range */ ?>

<header class="g-header" id="g-header"<?= $homeOverlay ? ' data-overlay' : '' ?>>
  <div class="g-wrap g-header__inner">
    <?php /* The label is on the link itself: over the home hero the coloured
             logo (which carries the alt) is hidden and the white one shown, and
             that one is decorative, so the link had no name to read out. */ ?>
    <a class="g-logo" href="<?= e(url('/')) ?>" aria-label="Getmeds Vanuatu, home">
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

<?php /* Phone and tablet: the actions every page needs, always in reach.
         WhatsApp sits beside Request a Medicine because a photo of the
         prescription sent there is the quickest way to ask. The opening
         hours sit above, so nobody wonders when they will hear back.
         guide.js measures the bar into --g-mbar-h (the body's bottom
         padding) and hides it while a form field has focus. */ ?>
<div class="g-mbar" id="g-mbar" aria-label="Quick actions">
  <p class="g-mbar__hours"><?= gi('clock') ?><?= e(cfg('reply_time_short')) ?> · <?= e(cfg('hours_short')) ?></p>
  <div class="g-mbar__btns">
    <?= g_btn('Request a Medicine', request_url('medicine'), 'primary') ?>
    <a class="g-btn g-btn--secondary g-mbar__wa" href="<?= e(whatsapp_url('Hello Getmeds, I would like to request a medicine. I will send a photo of my prescription.')) ?>" target="_blank" rel="noopener">
      <svg class="g-i" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor" stroke="none"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.87 9.87 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.18 8.18 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.25-8.23 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.82c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.24-.64.8-.79.97-.14.16-.29.18-.54.06-.25-.13-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.13-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.47-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/></svg><span>WhatsApp</span>
    </a>
    <a class="g-btn g-btn--secondary g-mbar__call" href="<?= e(tel_url()) ?>" aria-label="Call <?= e(cfg('phone')) ?>"><?= gi('phone') ?><span>Call</span></a>
  </div>
</div>

<main id="main" class="main">
