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
<html lang="en-VU" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> — <?= e(cfg('site_name')) ?></title>
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
<link rel="preload" href="<?= e(url('/assets/fonts/public-sans-latin.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(url('/assets/css/site.css')) ?>">
<link rel="icon" href="<?= e(url('/assets/img/favicon.svg')) ?>" type="image/svg+xml">

<?php /* Sets the js flag before first paint so nav never flashes open. */ ?>
<script>document.documentElement.className='js';</script>
</head>
<body>
<a class="skip" href="#main">Skip to content</a>
