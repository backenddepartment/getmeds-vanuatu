<?php
/**
 * Page not found (content guide, page 21).
 *
 * Friendly, centred: a small island illustration, the guide's copy, four link
 * cards and the phone number. Reached through .htaccess (unknown addresses
 * are rewritten here) and include-safe: a page that has already loaded
 * bootstrap (for example includes/condition-page.php for an unknown slug) can
 * include this file before it sends any output.
 */
require_once __DIR__ . '/includes/bootstrap.php';

if (!headers_sent()) {
    http_response_code(404);
}

$page = [
    'title'     => 'That page is not here',
    'seo_title' => 'Page not found — Getmeds Vanuatu',
    'desc'      => '',
    'noindex'   => true,
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-notfound">
  <div class="g-wrap g-narrow g-center">
    <svg class="g-notfound__art" viewBox="0 0 320 180" role="img" aria-label="Illustration of a small island with a palm tree in the sea">
      <circle cx="248" cy="46" r="22" fill="#F2A900" opacity=".85"/>
      <path d="M40 132c26-26 60-38 110-38s86 12 112 38z" fill="#6BB33F"/>
      <path d="M58 132c22-18 52-26 92-26s74 8 96 26z" fill="#EEF7F2" opacity=".5"/>
      <path d="M150 128c2-24 0-46-8-66" fill="none" stroke="#0B2A5B" stroke-width="6" stroke-linecap="round"/>
      <path d="M142 62c-14-10-34-10-48 2 16-2 30 2 40 10" fill="#2FB5A6"/>
      <path d="M142 62c6-16 22-24 40-22-14 6-24 14-30 26" fill="#2FB5A6"/>
      <path d="M142 62c16-6 34 0 44 14-14-6-28-6-40-2" fill="#1E9BD7"/>
      <path d="M142 62c-6-14-20-22-36-20 12 6 22 14 26 24" fill="#1E9BD7"/>
      <path d="M0 140c20-8 40-8 60 0s40 8 60 0 40-8 60 0 40 8 60 0 40-8 60 0 40 8 60 0v40H0z" fill="#1E9BD7" opacity=".85"/>
      <path d="M0 158c20-8 40-8 60 0s40 8 60 0 40-8 60 0 40 8 60 0 40-8 60 0 40 8 60 0v22H0z" fill="#0B2A5B" opacity=".9"/>
    </svg>

    <h1 class="g-mt">That page is not here</h1>
    <p class="g-lead g-mt-sm g-notfound__text">The address you used does not match a page on this site. Nothing has gone wrong with your medicine or your enquiry.</p>

    <div class="g-grid g-grid--4 g-mt-lg g-notfound__cards">
      <a class="g-card g-card--link g-card--compact" href="<?= e(request_url('medicine')) ?>">
        <span class="g-card__icon"><?= gi('pill') ?></span>
        <h2 class="g-h3">Request a Medicine</h2>
        <span class="g-card__go" aria-hidden="true"><?= gi('arrow') ?></span>
      </a>
      <a class="g-card g-card--link g-card--compact" href="<?= e(url('/patients')) ?>">
        <span class="g-card__icon"><?= gi('users') ?></span>
        <h2 class="g-h3">For Patients</h2>
        <span class="g-card__go" aria-hidden="true"><?= gi('arrow') ?></span>
      </a>
      <a class="g-card g-card--link g-card--compact" href="<?= e(url('/medicines')) ?>">
        <span class="g-card__icon"><?= gi('bottle') ?></span>
        <h2 class="g-h3">Medicines</h2>
        <span class="g-card__go" aria-hidden="true"><?= gi('arrow') ?></span>
      </a>
      <a class="g-card g-card--link g-card--compact" href="<?= e(url('/contact')) ?>">
        <span class="g-card__icon"><?= gi('phone') ?></span>
        <h2 class="g-h3">Contact</h2>
        <span class="g-card__go" aria-hidden="true"><?= gi('arrow') ?></span>
      </a>
    </div>

    <p class="g-mt-lg g-notfound__call">Or call: <a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a>, Monday to Friday, 8am to 5pm.</p>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
