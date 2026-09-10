<?php
/**
 * Masthead. Carries the phone number on every page, because phone is the first
 * contact channel for most patients.
 *
 * Hick's Law rule 3 — exactly one visually dominant action per page — is enforced
 * here rather than page by page. The brief mandates the "Ask About a Medicine"
 * button in the header on every page, so this is the site's default primary
 * action, and any body-level button pointing at the same place is the outline
 * variant. Two exceptions:
 *
 *   - On /enquire the button points at the page you are already reading, so it
 *     renders as the outline variant and carries aria-current="page". It is NOT
 *     removed: .masthead__cta occupies a content-sized grid column, and dropping
 *     the element collapses that column and shifts the phone number sideways.
 *     The phone number is the one thing on this site that must never move
 *     between pages.
 *   - A page that owns a more specific primary action sets $page['own_primary'],
 *     and this button steps down to the outline variant so it does not compete.
 *     That covers the medicine lookup, the quote form, and the /providers gate.
 *
 * Opens <main>; includes/footer.php closes it.
 */
$here       = current_path();
$onEnquire  = ($here === '/enquire');
$ownPrimary = !empty($page['own_primary']);
// Filled by default; outline when the page owns a more specific primary action,
// and outline plus aria-current when it points at the current page.
$ctaClass   = ($onEnquire || $ownPrimary) ? 'btn btn--secondary' : 'btn btn--primary';
?>
<header class="masthead">
  <div class="masthead__inner shell">

    <a class="wordmark" href="<?= e(url('/')) ?>">
      <?= brand_mark() ?>
      <span class="wordmark__text">
        <span class="wordmark__name"><?= e(cfg('site_name')) ?></span>
        <span class="wordmark__descriptor"><?= e(cfg('site_descriptor')) ?></span>
      </span>
    </a>

    <div class="masthead__reach">
      <a class="callnow" href="tel:<?= e(cfg('phone_href')) ?>">
        <span class="callnow__label"><?= icon('phone') ?>Call the pharmacy</span>
        <span class="callnow__number"><?= e(cfg('phone')) ?></span>
      </a>
      <p class="masthead__hours"><?= e(cfg('hours_short')) ?></p>
    </div>

    <a class="<?= $ctaClass ?> masthead__cta" href="<?= e(url('/enquire')) ?>"<?= $onEnquire ? ' aria-current="page"' : '' ?>>Ask About a Medicine</a>

    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="sitenav">
      <span class="menu-toggle__bars" aria-hidden="true"><span></span><span></span><span></span></span>
      <span class="menu-toggle__text">Menu</span>
    </button>

  </div>
  <?php include INC . '/nav.php'; ?>
</header>

<main id="main" class="main">
