<?php
/**
 * Document head. Expects $page:
 *   title    string  page title, sentence case, without the site name
 *   desc     string  meta description, one sentence
 *   ref      string  gazette-style notice reference shown in the footer margin
 *   noindex  bool    optional, true to keep the page out of search results
 *   h1       string  optional, defaults to title
 *   head     string  optional, trusted HTML for this page's <head> only (its
 *                    FAQPage schema, includes/faq.php)
 */
$page = isset($page) && is_array($page) ? $page : [];
$title   = $page['title']   ?? 'Getmeds Vanuatu';
$desc    = $page['desc']    ?? '';
$noindex = !empty($page['noindex']);

/* Search results cut a title after about 60 characters and a description after
   about 160, so the end (often the most useful part) is lost. Generated pages
   (products, categories, conditions) can run long, so both are fitted here:
   a title drops its middle parts ("Name — Category — Getmeds Vanuatu" becomes
   "Name — Getmeds Vanuatu"); a description drops whole sentences from the end,
   and only a single over-long sentence is cut at a word, with an ellipsis. */
$fullTitle = !empty($page['seo_title']) ? $page['seo_title']
           : ($title === cfg('site_name') ? $title : $title . ' — ' . cfg('site_name'));
$parts = explode(' — ', $fullTitle);
while (mb_strlen(implode(' — ', $parts)) > 60 && count($parts) > 2) {
    array_splice($parts, -2, 1);
}
// Still long (a long medicine name): the brand in its short form.
if (mb_strlen(implode(' — ', $parts)) > 60 && count($parts) > 1 && end($parts) === cfg('site_name')) {
    $parts[count($parts) - 1] = 'Getmeds';
}
$fullTitle = implode(' — ', $parts);

if (mb_strlen($desc) > 160) {
    $sentences = preg_split('/(?<=[.!?])\s+/u', $desc);
    $fit = '';
    foreach ($sentences as $s) {
        $try = $fit === '' ? $s : $fit . ' ' . $s;
        if (mb_strlen($try) > 160) {
            break;
        }
        $fit = $try;
    }
    if ($fit === '') {
        $fit = rtrim((string) preg_replace('/\s+\S*$/u', '', mb_substr($desc, 0, 157)), ' ,;:—-') . '…';
    }
    $desc = $fit;
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php /* "Page — Getmeds Vanuatu", or the site name alone when that is the title
         (the home page), rather than "Getmeds Vanuatu — Getmeds Vanuatu". */ ?>
<?php /* The content guide gives every page a full SEO title; use it verbatim. */ ?>
<title><?= e($fullTitle) ?></title>
<?php if ($desc !== ''): ?>
<meta name="description" content="<?= e($desc) ?>">
<?php endif; ?>
<?php /* The page's one true address, in the sitemap's form (trailing slash, no
         query string), so search engines merge /about, /about/ and
         /about/?lang=bi into one result. Not on pages kept out of search. */
$canonical = abs_url(current_path() === '/' ? '/' : current_path() . '/');
?>
<?php if ($noindex): ?>
<meta name="robots" content="<?= $page['noindex'] === 'follow' ? 'noindex, follow' : 'noindex, nofollow' ?>">
<?php else: ?>
<link rel="canonical" href="<?= e($canonical) ?>">
<?php endif; ?>

<?php /* Share card. The description doubles as the meta description above, so
         a link posted into a WhatsApp group says the same thing the page does. */ ?>
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e(cfg('site_name')) ?>">
<meta property="og:title" content="<?= e($fullTitle) ?>">
<?php if ($desc !== ''): ?>
<meta property="og:description" content="<?= e($desc) ?>">
<?php endif; ?>
<meta property="og:image" content="<?= e(abs_url('/assets/img/og.jpg')) ?>">
<meta property="og:image:alt" content="Getmeds Vanuatu, Pacific Chemotherapy Pharmacy. Cancer medicines, supplied in Port Vila.">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta name="twitter:card" content="summary_large_image">

<?php /* Self-hosted, no third-party request anywhere on this page. */ ?>
<link rel="preload" href="<?= e(url('/assets/fonts/poppins-latin-400-normal.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset_css('/assets/css/base.css')) ?>">
<link rel="stylesheet" href="<?= e(asset_css('/assets/css/guide.css')) ?>">
<?php /* The navbar logo, centred on a square: the tab icon (32px, and 192px for
         high-resolution screens), and the icon a phone uses when the site is
         saved to its home screen. */ ?>
<link rel="icon" href="<?= e(asset('/assets/img/favicon-32.png')) ?>" type="image/png" sizes="32x32">
<link rel="icon" href="<?= e(asset('/assets/img/favicon-192.png')) ?>" type="image/png" sizes="192x192">
<link rel="apple-touch-icon" href="<?= e(asset('/assets/img/apple-touch-icon.png')) ?>" sizes="180x180">

<?php /* Sets the js flag before first paint so nav never flashes open. */ ?>
<?php /* Also applies the remembered language (assets/js/i18n.js): with Bislama
         chosen the page stays hidden until the swap, at most 2.5s. */ ?>
<?php /* Structured data on every indexed page: this page, inside the website,
         published by the pharmacy (its address and phone from config.php).
         Pages add their own on top (breadcrumbs, FAQ, Drug). */ ?>
<?php if (!$noindex): ?>
<script type="application/ld+json"><?= json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'WebPage',
    'name'        => $fullTitle,
    'url'         => $canonical,
    'description' => $desc !== '' ? $desc : null,
    'inLanguage'  => 'en',
    'isPartOf'    => ['@type' => 'WebSite', 'name' => cfg('site_name'), 'url' => abs_url('/')],
    'publisher'   => array_filter([
        '@type'     => 'Pharmacy',
        'name'      => cfg('site_name'),
        'url'       => abs_url('/'),
        'logo'      => abs_url('/assets/img/logo-getmeds-vanuatu.png'),
        'telephone' => cfg('phone') ?: null,
        'email'     => cfg('email') ?: null,
        'address'   => array_filter([
            '@type'           => 'PostalAddress',
            'streetAddress'   => cfg('address_line') ?: null,
            'addressLocality' => cfg('address_city') ?: null,
            'addressCountry'  => cfg('address_country') ?: null,
        ]),
        // Mon to Fri, 8am to 5pm (config.php hours_short / hours_long).
        'openingHoursSpecification' => [[
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens'     => '08:00',
            'closes'    => '17:00',
        ]],
        // Vanuatu, and the countries Pacific Access supplies (pacific-access/index.php).
        'areaServed' => array_map(static function ($c) {
            return ['@type' => 'Country', 'name' => $c];
        }, ['Vanuatu', 'Solomon Islands', 'Fiji', 'Samoa', 'Tonga', 'Tuvalu', 'Nauru']),
        'sameAs'    => array_values(array_filter([cfg('facebook_url')])) ?: null,
    ]),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>
<?php endif; ?>
<?= $page['head'] ?? '' ?>
<script>(function(d){var c='js',l='bi';try{var q=/[?&]lang=(bi|en)/.exec(location.search);if(q){localStorage.setItem('gv-lang-v2',q[1]);}if(localStorage.getItem('gv-lang-v2')==='en'){l='en';}}catch(e){}if(l==='bi'){c+=' lang-bi lang-pending';d.lang='bi';setTimeout(function(){d.classList.remove('lang-pending');},2500);}d.className=c;})(document.documentElement);</script>
</head>
<body class="g">
<a class="skip" href="#main">Skip to content</a>
