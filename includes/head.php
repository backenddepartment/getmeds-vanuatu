<?php
/**
 * Document head. Expects $page:
 *   title    string  page title, sentence case, without the site name
 *   desc     string  meta description, one sentence
 *   ref      string  gazette-style notice reference shown in the footer margin
 *   noindex  bool    optional, true to keep the page out of search results
 *   h1       string  optional, defaults to title
 */
$page = isset($page) && is_array($page) ? $page : [];
$title   = $page['title']   ?? 'Getmeds Vanuatu';
$desc    = $page['desc']    ?? '';
$noindex = !empty($page['noindex']);
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php /* "Page — Getmeds Vanuatu", or the site name alone when that is the title
         (the home page), rather than "Getmeds Vanuatu — Getmeds Vanuatu". */ ?>
<?php /* The content guide gives every page a full SEO title; use it verbatim. */ ?>
<title><?= !empty($page['seo_title']) ? e($page['seo_title']) : ($title === cfg('site_name') ? e($title) : e($title) . ' — ' . e(cfg('site_name'))) ?></title>
<?php if ($desc !== ''): ?>
<meta name="description" content="<?= e($desc) ?>">
<?php endif; ?>
<?php if ($noindex): ?>
<meta name="robots" content="noindex, nofollow">
<?php endif; ?>

<?php /* Share card. The description doubles as the meta description above, so
         a link posted into a WhatsApp group says the same thing the page does. */ ?>
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e(cfg('site_name')) ?>">
<meta property="og:title" content="<?= e($title) ?>">
<?php if ($desc !== ''): ?>
<meta property="og:description" content="<?= e($desc) ?>">
<?php endif; ?>
<meta property="og:image" content="<?= e(abs_url('/assets/img/og.jpg')) ?>">
<meta property="og:image:alt" content="Getmeds Vanuatu, Pacific Chemotherapy Pharmacy. Cancer medicines, supplied in Port Vila.">
<meta property="og:url" content="<?= e(abs_url(current_path())) ?>">
<meta name="twitter:card" content="summary_large_image">

<?php /* Self-hosted, no third-party request anywhere on this page. */ ?>
<link rel="preload" href="<?= e(url('/assets/fonts/poppins-latin-400-normal.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?= e(url('/assets/fonts/inter-latin-400-normal.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset('/assets/css/base.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('/assets/css/guide.css')) ?>">
<?php /* The navbar logo, centred on a square: the tab icon (32px, and 192px for
         high-resolution screens), and the icon a phone uses when the site is
         saved to its home screen. */ ?>
<link rel="icon" href="<?= e(asset('/assets/img/favicon-32.png')) ?>" type="image/png" sizes="32x32">
<link rel="icon" href="<?= e(asset('/assets/img/favicon-192.png')) ?>" type="image/png" sizes="192x192">
<link rel="apple-touch-icon" href="<?= e(asset('/assets/img/apple-touch-icon.png')) ?>" sizes="180x180">

<?php /* Sets the js flag before first paint so nav never flashes open. */ ?>
<?php /* Also applies the remembered language (assets/js/i18n.js): with Bislama
         chosen the page stays hidden until the swap, at most 2.5s. */ ?>
<script>(function(d){var c='js';try{var q=/[?&]lang=(bi|en)/.exec(location.search);if(q){localStorage.setItem('gv-lang',q[1]);}if(localStorage.getItem('gv-lang')==='bi'){c+=' lang-bi lang-pending';d.lang='bi';setTimeout(function(){d.classList.remove('lang-pending');},2500);}}catch(e){}d.className=c;})(document.documentElement);</script>
</head>
<body class="g">
<a class="skip" href="#main">Skip to content</a>
