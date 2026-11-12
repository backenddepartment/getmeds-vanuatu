<?php
/**
 * Bootstrap. Every page requires this first, and nothing else.
 *
 * Loads config, works out where the site is mounted, and defines the small set
 * of helpers the templates use. No output is produced here.
 */

declare(strict_types=1);

/* PHP errors show only on this computer (localhost). On the live site a visitor
   would see the server's folder paths in them, so there they go to the error
   log instead. */
if (!in_array(strtolower((string) preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? 'localhost')), ['localhost', '127.0.0.1', '::1', '[::1]'], true)) {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

/* Behind a Cloudflare Tunnel the request can reach Apache marked http even
   though the visitor used https. Cloudflare always says which in CF-Visitor;
   carry it into X-Forwarded-Proto, the one header the rest of the site reads. */
if (strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) !== 'https'
    && stripos((string) ($_SERVER['HTTP_CF_VISITOR'] ?? ''), '"scheme":"https"') !== false) {
    $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
}

define('APP_ROOT', dirname(__DIR__));
define('INC', APP_ROOT . '/includes');

$GLOBALS['CFG'] = require APP_ROOT . '/config.php';

/**
 * Work out the URL path the site is mounted at, so it runs unchanged whether it
 * sits at the document root or inside a subfolder such as htdocs/getmeds-vanuatu.
 */
function base_path(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }

    // A static build is rendered by a throwaway localhost server but served from
    // somewhere else entirely — a GitHub Pages project site lives under
    // /<repo>/. DOCUMENT_ROOT cannot know that, so the build tells us.
    $override = getenv('GV_BASE_PATH');
    if ($override !== false) {
        return $base = rtrim($override, '/');
    }

    $base = '';
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
    $appRoot = realpath(APP_ROOT);

    if ($docRoot && $appRoot) {
        $docRoot = rtrim(str_replace('\\', '/', $docRoot), '/');
        $appRoot = rtrim(str_replace('\\', '/', $appRoot), '/');

        if ($appRoot === $docRoot) {
            $base = '';
        } elseif (strpos($appRoot, $docRoot . '/') === 0) {
            $base = substr($appRoot, strlen($docRoot));
        }
    }

    return $base;
}

/**
 * Build a site URL from a root-relative path. Always use this, never a bare href.
 *
 * Page addresses get the trailing slash they are served at (/about becomes
 * /about/), so every internal link matches its canonical and sitemap URL and
 * no click costs a 301. A path whose last part has a file extension (.css,
 * .jpg, .xml, .php) is a file and is left alone. Any ?query or #hash is kept.
 */
function url(string $path = '/'): string
{
    if ($path === '' || $path[0] !== '/') {
        $path = '/' . $path;
    }
    $cut    = strcspn($path, '?#');
    $suffix = (string) substr($path, $cut);
    $path   = substr($path, 0, $cut);
    if ($path !== '/' && substr($path, -1) !== '/' && strpos(basename($path), '.') === false) {
        $path .= '/';
    }
    return base_path() . $path . $suffix;
}

/**
 * URL for a file under /assets, stamped with its modification time. .htaccess
 * caches CSS and JS for a week, so without the stamp a returning visitor keeps
 * the old file after an edit.
 */
function asset(string $path): string
{
    $file = APP_ROOT . $path;
    return url($path) . (is_file($file) ? '?v=' . filemtime($file) : '');
}

/**
 * A stylesheet, served as a minified copy (<name>.min.css beside it) that is
 * rebuilt whenever the source is newer. Edit the normal .css file only; the
 * .min.css is generated. Conservative: strips comments and whitespace, never
 * touches the space before a colon (".a :hover" differs from ".a:hover") or
 * anything inside calc(). Falls back to the source file if it cannot write.
 */
function asset_css(string $path): string
{
    $src = APP_ROOT . $path;
    $minPath = (string) preg_replace('/\.css$/', '.min.css', $path);
    $min = APP_ROOT . $minPath;
    if (!is_file($src)) {
        return asset($path);
    }
    if (!is_file($min) || filemtime($min) < filemtime($src)) {
        $css = (string) file_get_contents($src);
        $css = (string) preg_replace('#/\*.*?\*/#s', '', $css);
        $css = (string) preg_replace('/\s+/', ' ', $css);
        $css = (string) preg_replace('/\s*([{};,>])\s*/', '$1', $css);
        $css = (string) preg_replace('/:\s+/', ':', $css);
        $css = str_replace(';}', '}', $css);
        $tmp = $min . '.' . getmypid() . '.tmp';
        if ($css === '' || @file_put_contents($tmp, trim($css)) === false || !@rename($tmp, $min)) {
            @unlink($tmp);
            return asset($path);
        }
    }
    return asset($minPath);
}

/**
 * Absolute URL, for the places a relative one is not allowed: og:image and
 * canonical are both read by machines that have no page context to resolve
 * against. Derived from the request rather than hard-coded, so this works the
 * same on localhost, on staging and on the live host.
 */
function abs_url(string $path = '/'): string
{
    // Same problem as base_path(): during a static build the request host is
    // 127.0.0.1, and an og:image pointing there is worse than none at all.
    $origin = getenv('GV_SITE_ORIGIN');
    if ($origin !== false && $origin !== '') {
        return rtrim($origin, '/') . url($path);
    }

    // The production address from config.php ('site_origin'). Set at launch,
    // so canonical, og:url, og:image, the sitemaps and the schema all name the
    // live HTTPS domain whatever host the request arrived on.
    $origin = (string) cfg('site_origin', '');
    if ($origin !== '') {
        return rtrim($origin, '/') . url($path);
    }

    $https  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
           || strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    $host   = $_SERVER['HTTP_HOST'] ?? 'getmeds.vu';
    return ($https ? 'https' : 'http') . '://' . $host . url($path);
}

/**
 * True when the page is being rendered into a static file rather than served.
 *
 * A static host runs no PHP, so anything that needs a POST — the enquiry form,
 * the quote form, the healthcare-professional gate — cannot work once the page
 * is published. Those places check this and offer the phone and the email
 * instead of a form that would throw the visitor's message away.
 */
function is_static_build(): bool
{
    return getenv('GV_STATIC') === '1';
}

/** Escape for HTML text and attribute values. */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Read a config value. */
function cfg(string $key, $default = null)
{
    return $GLOBALS['CFG'][$key] ?? $default;
}

/*
 * is_placeholder(), val(), legal_copy_required() and photo_needed() were deleted
 * on 2026-09-24. They printed "Needed LEGAL_ENTITY_NAME", "Legal copy required"
 * and "Photograph needed" boxes onto live pages — markers meant for the builder
 * that customers were reading instead. Nothing unfinished is displayed now: if a
 * value is not confirmed, the markup that showed it is removed too.
 */

/**
 * A wa.me link, optionally carrying a first message. WhatsApp is the ordering
 * channel that works when the site is served as static HTML with no PHP behind
 * it, and it is the one most patients here already have open.
 */
function whatsapp_url(string $text = ''): string
{
    $u = 'https://wa.me/' . preg_replace('/\D+/', '', (string) cfg('whatsapp_href'));
    return $text === '' ? $u : $u . '?text=' . rawurlencode($text);
}

/** The full address on one line. */
function address_inline(): string
{
    return e(cfg('address_line')) . ', ' . e(cfg('address_city')) . ', ' . e(cfg('address_country'));
}

/** A tel: link carrying the phone number. */
function phone_link(string $class = ''): string
{
    $c = $class !== '' ? ' class="' . e($class) . '"' : '';
    return '<a' . $c . ' href="tel:' . e(cfg('phone_href')) . '">' . e(cfg('phone')) . '</a>';
}

/**
 * Is the current request inside this section? Drives the nav's current state.
 * Compares the request path against a section root, ignoring the mount point.
 */
function in_section(string $section): bool
{
    $path = current_path();
    if ($section === '/') {
        return $path === '/' || $path === '';
    }
    return $path === $section || strpos($path, rtrim($section, '/') . '/') === 0;
}

/** Request path with the mount point and any query string removed. */
function current_path(): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $uri = (string) parse_url($uri, PHP_URL_PATH);
    $base = base_path();

    if ($base !== '' && strpos($uri, $base) === 0) {
        $uri = substr($uri, strlen($base));
    }

    $uri = '/' . ltrim($uri, '/');
    // A directory index resolves to its directory.
    $uri = preg_replace('#/index\.php$#', '/', $uri);
    $uri = rtrim($uri, '/');

    return $uri === '' ? '/' : $uri;
}

/**
 * Start a session, with hardened cookie parameters.
 *
 * Call this at the top of any page that renders a form, BEFORE any output. It is
 * deliberately not called for every request: the only cookie this site sets is
 * the strictly-necessary session cookie behind CSRF protection, and a page with
 * no form has no reason to set one. That is also why there is no cookie banner —
 * there is nothing here that needs consent.
 */
function ensure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    // Refuse a session id the server did not issue (session fixation), and keep
    // the id out of URLs.
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'path'     => base_path() === '' ? '/' : base_path(),
        'httponly' => true,
        'samesite' => 'Lax',
        // Behind a proxy or CDN that ends HTTPS, PHP sees plain http; the
        // proxy's header says the visitor's connection was secure.
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                   || strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https',
    ]);
    session_start();
}

/** CSRF token for this session. */
function csrf_token(): string
{
    ensure_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_valid(?string $token): bool
{
    ensure_session();
    return !empty($_SESSION['csrf']) && is_string($token)
        && hash_equals($_SESSION['csrf'], $token);
}

/**
 * The site's navigation (content guide, page 23). Seven items, so the row fits
 * on one line on a laptop. Medicines carries the guide's one dropdown.
 *
 * The guide also puts a green "Request a Medicine" button in the header. The
 * owner asked for the header to carry links only, so that button lives in the
 * page bodies, the footer CTA strip and the phone's sticky bottom bar instead.
 */
function nav_tree(): array
{
    // The Product Range menu: its categories in patient-friendly groups
    // (data/category-groups.php), each under a group title, with short labels.
    // 'children' keeps the flat list, for the current-page check.
    $shopCats = $shopGroups = [];
    foreach (shop_category_groups() as $g) {
        $links = [];
        foreach ($g['cats'] as $slug => [$short]) {
            $links[] = $shopCats[] = ['label' => $short, 'url' => shop_category_path($slug)];
        }
        $shopGroups[] = ['label' => $g['label'], 'children' => $links];
    }

    return [
        ['label' => 'Home',      'url' => '/'],
        ['label' => 'About Us', 'url' => '/about', 'linkable' => true,
         'menu_intro' => [
             'heading' => 'About Us',
             'text'    => 'Our story, how Getmeds works, and the specialist care we focus on.',
         ],
         'children' => [
             ['label' => 'How It Works',         'url' => '/how-it-works'],
             ['label' => 'Named Patient Supply', 'url' => '/named-patient-supply'],
             ['label' => 'Oncology',             'url' => '/oncology'],
             // Exact: the pages under /medicines have menus of their own.
             ['label' => 'Medicines we supply',  'url' => '/medicines', 'exact' => true],
             ['label' => 'For Patients',         'url' => '/patients'],
             ['label' => 'For Healthcare Professionals', 'url' => '/healthcare-professionals'],
             ['label' => 'Pacific Access',       'url' => '/pacific-access'],
         ]],
        // The shop's categories (the condition groups), and the Catalog page
        // as the "View Full Catalog" button beside them.
        ['label' => 'Product Range', 'url' => '/product-range/', 'linkable' => true, 'grid' => true,
         'menu_intro' => [
             'heading' => 'Product Range',
             'text'    => 'Browse our pharmacy by type of treatment, or open the full catalog to see every medicine we list.',
             'button'  => ['View Full Catalog', '/medicines/catalog'],
         ],
         'children' => $shopCats, 'groups' => $shopGroups],
        ['label' => 'Articles', 'url' => '/articles'],
        ['label' => 'Contact',              'url' => '/contact'],
    ];
}

/**
 * The medicines shop's categories: every therapeutic category in
 * data/products.json, A to Z, each with its product count. Built from the
 * data, never hand-written, so a category appears when its first product
 * does. slug => [name, icon, count].
 */
function shop_categories(): array
{
    static $cats = null;
    if ($cats !== null) {
        return $cats;
    }
    $cats = [];
    foreach (shop_products() as $p) {
        if (!isset($cats[$p['cat']])) {
            $cats[$p['cat']] = [$p['category'], 'ribbon', 0];
        }
        $cats[$p['cat']][2]++;
    }
    uasort($cats, static function ($a, $b) {
        return strcasecmp($a[0], $b[0]);
    });
    return $cats;
}

/**
 * The categories in their patient-friendly groups (data/category-groups.php),
 * in that file's order, only categories that have products. A category not in
 * the file falls into 'other' under its full name, so nothing ever vanishes.
 * key => ['label', 'desc', 'cats' => [slug => [short label, full name, count]]]
 */
function shop_category_groups(): array
{
    static $groups = null;
    if ($groups !== null) {
        return $groups;
    }
    $cats   = shop_categories();
    $groups = [];
    $placed = [];
    foreach (require APP_ROOT . '/data/category-groups.php' as $key => [$label, $desc, $members]) {
        $groups[$key] = ['label' => $label, 'desc' => $desc, 'cats' => []];
        foreach ($members as $slug => $short) {
            if (isset($cats[$slug]) && !isset($placed[$slug])) {
                $groups[$key]['cats'][$slug] = [$short, $cats[$slug][0], $cats[$slug][2]];
                $placed[$slug] = true;
            }
        }
    }
    foreach ($cats as $slug => [$name, , $count]) {
        if (!isset($placed[$slug])) {
            $groups['other']['cats'][$slug] = [$name, $name, $count];
        }
    }
    return $groups = array_filter($groups, static function ($g) {
        return $g['cats'] !== [];
    });
}

/** The group key a category sits in. */
function shop_category_group(string $catSlug): ?string
{
    foreach (shop_category_groups() as $key => $g) {
        if (isset($g['cats'][$catSlug])) {
            return $key;
        }
    }
    return null;
}

/** A shop slug, from a name: "Heart and blood vessels" => heart-and-blood-vessels. */
function shop_slug(string $name): string
{
    return trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-');
}

/*
 * The medicines flow. Each level is a real page, linked by its breadcrumb:
 *   /medicines/catalog         Catalog: every category and its medicines
 *   /product-range             All products
 *   /<category>/               One category
 *   /<category>/<product>/     One product
 * The last two are routed by product-range/index.php (.htaccess sends them
 * there); shop_paths() lists them for the sitemap and the static build.
 */

/**
 * Every product in data/products.json (the Getmeds molecule list, used as
 * it comes), in its order, keyed by slug. The file's order is the default
 * order of the Product Range table: most-requested first. Each: slug, name,
 * cat (category slug), category (its name), drug_class, oncology_type, icon,
 * rx (always true: every molecule here is a prescription medicine).
 */
function shop_products(): array
{
    static $rows = null;
    if ($rows !== null) {
        return $rows;
    }
    $rows = [];
    $raw  = json_decode((string) @file_get_contents(APP_ROOT . '/data/products.json'), true);
    foreach ((array) $raw as $r) {
        $name = trim((string) ($r['name'] ?? ''));
        $cat  = trim((string) ($r['category'] ?? ($r['therapeutic_category'] ?? '')));
        $slug = shop_slug((string) ($r['slug'] ?? $name));
        if ($slug === '' || $name === '' || $cat === '') {
            continue;
        }
        $rows[$slug] = ['slug' => $slug, 'name' => $name, 'cat' => shop_slug($cat), 'category' => $cat,
                        'drug_class' => trim((string) ($r['drug_class'] ?? '')),
                        'oncology_type' => trim((string) ($r['oncology_type'] ?? '')),
                        'icon' => 'ribbon', 'rx' => true];
    }
    return $rows;
}

/** Solid / Hematologic / Both, for the Product Range quick filters. */
function shop_oncology_group(array $p): ?string
{
    $t = strtolower($p['oncology_type']);
    $solid = strpos($t, 'solid') !== false;
    $heme  = strpos($t, 'hematologic') !== false;
    if ($solid && $heme) {
        return 'both';
    }
    return $solid ? 'solid' : ($heme ? 'hematologic' : null);
}

/** The conditions in a category's condition group: [[slug, name], ...]. Everyday health has none. */
function shop_category_conditions(string $catSlug): array
{
    foreach (require APP_ROOT . '/data/conditions.php' as $g) {
        if (shop_slug($g['name']) === $catSlug) {
            return array_map(static function ($c) {
                return [$c['slug'], $c['name']];
            }, $g['conditions']);
        }
    }
    return [];
}

function shop_category_path(string $catSlug): string
{
    return '/' . $catSlug . '/';
}

function shop_product_path(array $p): string
{
    return shop_category_path($p['cat']) . $p['slug'] . '/';
}

/**
 * Is this product page one of the most-requested, kept in search engines?
 * The first cfg('products_indexed') rows of data/products.json are; the rest
 * carry "noindex, follow" and stay out of sitemap-products.xml.
 */
function shop_product_indexed(string $slug): bool
{
    static $keep = null;
    if ($keep === null) {
        $keep = [];
        $max  = (int) cfg('products_indexed', 50);
        foreach (shop_products() as $s => $p) {
            if (count($keep) >= $max) {
                break;
            }
            // Specialist-only therapies are never offered to search engines.
            if (!shop_is_restricted($p['cat'])) {
                $keep[$s] = true;
            }
        }
    }
    return isset($keep[$slug]);
}

/**
 * Specialist-only categories: investigational medicines, CAR-T and other cell
 * and gene therapies. Until the Vanuatu Pharmacy Board confirms how these may
 * be presented, they stay listed for hospitals and specialists but carry a
 * specialist-use notice and are kept out of search engines (audit, 2026-11-04).
 */
function shop_is_restricted(string $catSlug): bool
{
    return (bool) preg_match('/investigational|cellular-therapy|gene-therapy/', $catSlug);
}

/** The notice shown on specialist-only category and product pages (trusted HTML). */
function g_restricted_note(): string
{
    return '<strong>Specialist use only.</strong> This therapy may be investigational or not yet approved for '
         . 'general use in Vanuatu. It is supplied only to hospitals and treating specialists, for a named patient '
         . 'and subject to regulatory approval. Patients: please ask your specialist, who can '
         . '<a href="' . e(url('/healthcare-professionals') . '#quote') . '">contact us</a>.';
}

/** Every category and medicine page, for the sitemap and the static build. */
function shop_paths(): array
{
    $paths = [];
    foreach (array_keys(shop_categories()) as $slug) {
        $paths[] = rtrim(shop_category_path($slug), '/');
    }
    foreach (shop_products() as $p) {
        $paths[] = rtrim(shop_product_path($p), '/');
    }
    return $paths;
}

/** Children of a section, for a landing page to list. */
function nav_children(string $sectionUrl): array
{
    foreach (nav_tree() as $item) {
        if ($item['url'] === $sectionUrl) {
            return $item['children'] ?? [];
        }
    }
    return [];
}

/**
 * Pages whose links open as pop-ups (guide.js, data-modal), so a half-filled
 * form is not lost. Each is also a real page: opened directly (typed,
 * bookmarked, a search result, a link without JS) it renders in full with the
 * header and footer. They used to redirect when opened directly, which left
 * the forms' consent boxes pointing at a Privacy Policy no one could open
 * (audit, 4 November 2026).
 */
const MODAL_PAGES = ['/privacy', '/terms', '/complaints', '/policies-and-safety', '/report-a-side-effect'];

/** Kept so older page templates that still call it keep working. Does nothing. */
function modal_only(string $fallback = '/'): void
{
}

/** True for a link that should open as a pop-up. */
function is_modal_path(string $path): bool
{
    return in_array(rtrim((string) strtok($path, '#?'), '/'), MODAL_PAGES, true);
}

/** Footer navigation columns (content guide, page 22). */
function footer_columns(): array
{
    return [
        'Medicines' => [
            ['Product Range',       '/product-range/'],
            ['Medicines catalog',   '/medicines/catalog'],
            ['Medicines we supply', '/medicines'],
            ['Cancer medicines',    '/medicines#oncology'],
            ['Medical supplies',    '/medicines#medical-supplies'],
        ],
        'Services' => [
            ['Oncology and chemotherapy',    '/oncology'],
            ['Named Patient Supply',         '/named-patient-supply'],
            ['For healthcare professionals', '/healthcare-professionals'],
            ['For patients',                 '/patients'],
        ],
        'Help' => [
            ['How it works',         '/how-it-works'],
            ['FAQ',                  '/faq'],
            ['Contact',              '/contact'],
            ['Report a side effect', '/report-a-side-effect'],
        ],
        'Company' => [
            ['About us',          '/about'],
            ['Pacific access',    '/pacific-access'],
            ['Services',          '/services'],
        ],
    ];
}

/** The footer's legal strip (content guide, page 22). */
function footer_links(): array
{
    return [
        ['label' => 'Privacy Policy',      'url' => '/privacy'],
        ['label' => 'Terms of Use',        'url' => '/terms'],
        ['label' => 'Complaints',          'url' => '/complaints'],
        ['label' => 'Policies and Safety', 'url' => '/policies-and-safety'],
    ];
}

/**
 * Send an old address to its new page. The old sections were folded into the
 * content guide's pages; this keeps bookmarks and shared links working.
 */
function redirect_to(string $path): void
{
    header('Location: ' . url($path), true, 301);
    exit;
}

/**
 * Photography.
 Loaded here so every page has plate() and band() without
 * remembering to ask for them.
 */
require_once INC . '/plates.php';
require_once INC . '/icons.php';
require_once INC . '/ui.php';
