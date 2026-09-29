<?php
/**
 * sitemap-images.xml, the image sitemap, kept separate from the page sitemap.
 * .htaccess serves this file at /sitemap-images.xml and tools/build-static.php
 * writes it out as a static sitemap-images.xml.
 *
 * One <url> per page that shows a photograph, listing each photograph once at
 * its largest size. Pages with no photographs are left out.
 */
require __DIR__ . '/includes/bootstrap.php';
require_once INC . '/sitemap.php';

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', "\n";
echo '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">', "\n";
foreach (sitemap_pages() as $p) {
    $images = sitemap_page_images($p);
    if (!$images) {
        continue;
    }
    echo "  <url>\n";
    echo '    <loc>', sitemap_xml(sitemap_loc($p['path'])), "</loc>\n";
    foreach ($images as $img) {
        echo "    <image:image>\n";
        echo '      <image:loc>', sitemap_xml(abs_url($img)), "</image:loc>\n";
        echo "    </image:image>\n";
    }
    echo "  </url>\n";
}
echo "</urlset>\n";
