<?php
/**
 * sitemap.xml, the page sitemap. .htaccess serves this file at /sitemap.xml and
 * tools/build-static.php writes it out as a static sitemap.xml.
 *
 * The page list, priorities and dates come from includes/sitemap.php. The
 * photographs are in their own sitemap, sitemap-images.xml.
 */
require __DIR__ . '/includes/bootstrap.php';
require_once INC . '/sitemap.php';

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', "\n";
foreach (sitemap_pages() as $p) {
    echo "  <url>\n";
    echo '    <loc>', sitemap_xml(sitemap_loc($p['path'])), "</loc>\n";
    echo '    <lastmod>', $p['lastmod'], "</lastmod>\n";
    echo '    <changefreq>', $p['changefreq'], "</changefreq>\n";
    echo '    <priority>', $p['priority'], "</priority>\n";
    echo "  </url>\n";
}
echo "</urlset>\n";
