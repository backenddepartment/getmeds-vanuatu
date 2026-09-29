<?php
/**
 * Sitemaps: the one list every sitemap is built from.
 *
 * Read by sitemap.php (sitemap.xml), sitemap-images.php (sitemap-images.xml),
 * robots.php (robots.txt) and sitemap/index.php (the HTML sitemap). Nothing is
 * hand-listed: every index.php under the root is a page, the same rule
 * tools/build-static.php uses, so a new page appears in all four by existing.
 *
 * Left out, because a crawler should not be sent to them:
 *   - old addresses that only redirect_to() somewhere else
 *   - pages marked 'noindex' => true (the search results page)
 *   - the private folders .htaccess already refuses
 *
 * Priority (owner decision, 2026-09-26):
 *   1.0  the home page and the main menu pages
 *   0.8  everything else: condition pages, service and access pages
 *   0.5  static pages: about, contact, FAQ and the legal pages
 */

/** Main menu pages. Priority 1.0. About and Contact are static pages, 0.5. */
function sitemap_main_paths(): array
{
    return [
        '/', '/medicines', '/conditions', '/oncology',
        '/healthcare-professionals', '/how-it-works', '/named-patient-supply',
    ];
}

/** Static pages. Priority 0.5. */
function sitemap_static_paths(): array
{
    return [
        '/about', '/contact', '/faq', '/privacy', '/terms',
        '/complaints', '/policies-and-safety', '/sitemap',
    ];
}

/** Folders that are never pages. Mirrors .htaccess and tools/build-static.php. */
function sitemap_skip_dirs(): array
{
    return ['includes/', 'data/', 'tools/', 'docs/', 'vendor/', 'node_modules/', '.github/', '.claude/', '.impeccable/', 'assets/'];
}

/**
 * Every indexable page, sorted: main pages first, then secondary, then static.
 *
 * Each entry: path, file, priority, changefreq, lastmod (W3C date), title.
 */
function sitemap_pages(): array
{
    static $pages = null;
    if ($pages !== null) {
        return $pages;
    }

    $root  = rtrim(str_replace('\\', '/', APP_ROOT), '/');
    $main  = sitemap_main_paths();
    $flat  = sitemap_static_paths();
    $names = sitemap_condition_names();
    $pages = [];

    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        $abs = str_replace('\\', '/', $file->getPathname());
        if (basename($abs) !== 'index.php') {
            continue;
        }
        $rel = ltrim(substr($abs, strlen($root)), '/');
        foreach (sitemap_skip_dirs() as $skip) {
            if (strpos($rel, $skip) === 0) {
                continue 2;
            }
        }

        $src = (string) file_get_contents($abs);
        // Old addresses and noindex pages are not for crawlers.
        if (preg_match('/^\s*redirect_to\(/m', $src) || preg_match("/'noindex'\\s*=>\\s*true/", $src)) {
            continue;
        }

        $path = $rel === 'index.php' ? '/' : '/' . dirname($rel);

        if (in_array($path, $main, true)) {
            $priority = '1.0'; $freq = 'weekly';  $tier = 0;
        } elseif (in_array($path, $flat, true)) {
            $priority = '0.5'; $freq = 'yearly';  $tier = 2;
        } else {
            $priority = '0.8'; $freq = 'monthly'; $tier = 1;
        }

        // A condition page's copy lives in data/conditions.php, so its date is
        // whichever of the two changed last.
        $mtime = filemtime($abs);
        if (strpos($path, '/conditions/') === 0) {
            $mtime = max($mtime, (int) @filemtime($root . '/data/conditions.php'));
        }

        $title = '';
        if (preg_match("/'title'\\s*=>\\s*'((?:[^'\\\\]|\\\\.)*)'/", $src, $m)) {
            $title = stripslashes($m[1]);
        } elseif (preg_match("/\\\$slug\\s*=\\s*'([^']+)'/", $src, $m) && isset($names[$m[1]])) {
            $title = $names[$m[1]];
        }
        if ($path === '/') {
            $title = 'Home';
        }

        $pages[] = [
            'path'       => $path,
            'file'       => $abs,
            'priority'   => $priority,
            'changefreq' => $freq,
            'lastmod'    => date('Y-m-d', $mtime),
            'title'      => $title !== '' ? $title : ucwords(str_replace('-', ' ', basename($path))),
            'tier'       => $tier,
        ];
    }

    usort($pages, static function (array $a, array $b): int {
        if ($a['tier'] !== $b['tier']) {
            return $a['tier'] <=> $b['tier'];
        }
        // Keep the main pages in menu order.
        if ($a['tier'] === 0) {
            $order = array_flip(sitemap_main_paths());
            return $order[$a['path']] <=> $order[$b['path']];
        }
        return strcmp($a['path'], $b['path']);
    });

    return $pages;
}

/** slug => condition name, from data/conditions.php. */
function sitemap_condition_names(): array
{
    $out = [];
    foreach (sitemap_condition_groups() as $group) {
        foreach ($group['conditions'] ?? [] as $c) {
            $out[$c['slug']] = $c['name'];
        }
    }
    return $out;
}

function sitemap_condition_groups(): array
{
    static $groups = null;
    if ($groups === null) {
        $file = APP_ROOT . '/data/conditions.php';
        $groups = is_file($file) ? require $file : [];
    }
    return $groups;
}

/**
 * The public URL of a page, with the trailing slash a directory is served at.
 * abs_url() carries the live origin during a static build (GV_SITE_ORIGIN).
 */
function sitemap_loc(string $path): string
{
    return abs_url($path === '/' ? '/' : $path . '/');
}

/**
 * The photographs on one page, as site paths (/assets/img/...).
 *
 * Read from the page's source, not its rendered HTML: every photograph on this
 * site is named there, either as g_photo('name', ...) or as an asset() path.
 * Of several widths of one picture, only the largest is listed. Icons, the
 * favicon and the logo are not content and are left out, except that the home
 * page lists the logo once.
 */
function sitemap_page_images(array $page): array
{
    $src  = (string) file_get_contents($page['file']);
    $best = [];   // base name => [width, path]

    // g_photo('carton', ...) -> the largest JPEG width in data/images.php.
    if (preg_match_all("/g_photo\\(\\s*'([a-z0-9-]+)'/", $src, $m)) {
        $manifest = function_exists('img_manifest') ? img_manifest() : [];
        foreach ($m[1] as $name) {
            $widths = $manifest[$name]['widths'] ?? [];
            if ($widths) {
                $best['photo/' . $name] = [max($widths), '/assets/img/photo/' . $name . '-' . max($widths) . '.jpg'];
            }
        }
    }

    // asset('/assets/img/home-about-1600.jpg') and friends.
    if (preg_match_all("~asset\\(\\s*'(/assets/img/[^']+\\.(?:jpe?g|png))'~i", $src, $m)) {
        foreach ($m[1] as $path) {
            $file = basename($path);
            if (preg_match('/^(favicon|apple-touch|logo)/', $file)) {
                continue;
            }
            $base  = preg_replace('/-\d+(?=\.[a-z]+$)/i', '', $path);
            $base  = preg_replace('/\.[a-z]+$/i', '', $base);
            $width = preg_match('/-(\d+)\.[a-z]+$/i', $path, $w) ? (int) $w[1] : 0;
            // Prefer a sized JPEG over an unsized original of the same picture.
            if (!isset($best[$base]) || $width > $best[$base][0]) {
                $best[$base] = [$width, $path];
            }
        }
    }

    $out = [];
    foreach ($best as [, $path]) {
        if (is_file(APP_ROOT . $path)) {
            $out[] = $path;
        }
    }
    if ($page['path'] === '/' && is_file(APP_ROOT . '/assets/img/logo-getmeds-vanuatu.png')) {
        $out[] = '/assets/img/logo-getmeds-vanuatu.png';
    }
    return array_values(array_unique($out));
}

/** Escape for XML text. */
function sitemap_xml(string $s): string
{
    return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}
