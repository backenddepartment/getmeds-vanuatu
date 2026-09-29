<?php
/**
 * robots.txt. .htaccess serves this file at /robots.txt and
 * tools/build-static.php writes it out as a static robots.txt.
 *
 * The sitemaps tell a crawler what to crawl; this tells it what not to. The
 * default is allow everything, then disallow:
 *   - any back panel or dashboard address, so a login or admin screen added
 *     later is never crawled. None exists on the site today.
 *   - the private folders .htaccess already refuses (data, includes, docs, tools)
 *   - the search results page, which is only a view of other pages
 *
 * Paths carry the base path, so the rules are right when the site is mounted in
 * a subfolder. A crawler only reads robots.txt at the domain root, so the file
 * takes effect once the site is served from the root of its own domain.
 */
require __DIR__ . '/includes/bootstrap.php';

header('Content-Type: text/plain; charset=utf-8');

$b = base_path();
$disallow = [
    // Back panel and dashboard.
    '/admin/', '/administrator/', '/backpanel/', '/back-panel/', '/backend/',
    '/dashboard/', '/cpanel/', '/panel/', '/login/', '/wp-admin/', '/wp-login.php',
    // Private folders.
    '/data/', '/includes/', '/docs/', '/tools/',
    // Search results.
    '/search/', '/*?q=',
];

echo "User-agent: *\n";
echo "Allow: {$b}/\n";
foreach ($disallow as $path) {
    echo "Disallow: {$b}{$path}\n";
}
echo "\n";
echo 'Sitemap: ', abs_url('/sitemap.xml'), "\n";
echo 'Sitemap: ', abs_url('/sitemap-images.xml'), "\n";
