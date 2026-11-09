<?php
/**
 * The page sitemaps. .htaccess serves this file at /sitemap.xml (the sitemap
 * index) and at /sitemap-pages.xml, /sitemap-categories.xml and
 * /sitemap-products.xml (?set=...). tools/build-static.php writes all four out
 * as static files.
 *
 * The page list, priorities and dates come from includes/sitemap.php. The
 * photographs are in their own sitemap, sitemap-images.xml, which the index
 * lists too.
 */
require __DIR__ . '/includes/bootstrap.php';
require_once INC . '/sitemap.php';

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');

$sets = sitemap_sets();
$set  = (string) ($_GET['set'] ?? '');

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";

if (!isset($sets[$set])) {
    // The index: each child sitemap with the newest date among its pages.
    echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', "\n";
    $newest = '';
    foreach ($sets as $name => $file) {
        $dates = array_column(sitemap_set_pages($name), 'lastmod');
        if (!$dates) {
            continue;
        }
        $last   = max($dates);
        $newest = max($newest, $last);
        echo "  <sitemap>\n";
        echo '    <loc>', sitemap_xml(abs_url('/' . $file)), "</loc>\n";
        echo '    <lastmod>', $last, "</lastmod>\n";
        echo "  </sitemap>\n";
    }
    echo "  <sitemap>\n";
    echo '    <loc>', sitemap_xml(abs_url('/sitemap-images.xml')), "</loc>\n";
    echo '    <lastmod>', $newest !== '' ? $newest : date('Y-m-d'), "</lastmod>\n";
    echo "  </sitemap>\n";
    echo "</sitemapindex>\n";
    return;
}

echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', "\n";
foreach (sitemap_set_pages($set) as $p) {
    echo "  <url>\n";
    echo '    <loc>', sitemap_xml(sitemap_loc($p['path'])), "</loc>\n";
    echo '    <lastmod>', $p['lastmod'], "</lastmod>\n";
    echo '    <changefreq>', $p['changefreq'], "</changefreq>\n";
    echo '    <priority>', $p['priority'], "</priority>\n";
    echo "  </url>\n";
}
echo "</urlset>\n";
