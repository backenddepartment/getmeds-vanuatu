<?php
/**
 * Bootstrap. Every page requires this first, and nothing else.
 *
 * Loads config, works out where the site is mounted, and defines the small set
 * of helpers the templates use. No output is produced here.
 */

declare(strict_types=1);

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

/** Build a site URL from a root-relative path. Always use this, never a bare href. */
function url(string $path = '/'): string
{
    if ($path === '' || $path[0] !== '/') {
        $path = '/' . $path;
    }
    return base_path() . $path;
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

    $https  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
           || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
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
    session_set_cookie_params([
        'path'     => base_path() === '' ? '/' : base_path(),
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']),
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
    return [
        ['label' => 'Home',      'url' => '/'],
        ['label' => 'About Us', 'url' => '/about',
         'children' => [
             ['label' => 'About Us',             'url' => '/about'],
             ['label' => 'How It Works',         'url' => '/how-it-works'],
             ['label' => 'Named Patient Supply', 'url' => '/named-patient-supply'],
         ]],
        ['label' => 'Medicines', 'url' => '/medicines',
         'children' => [
             ['label' => 'Medicines we supply', 'url' => '/medicines'],
             ['label' => 'Conditions',          'url' => '/conditions'],
         ]],
        ['label' => 'Oncology',             'url' => '/oncology'],
        ['label' => 'For Healthcare Professionals', 'url' => '/healthcare-professionals'],
        ['label' => 'Contact',              'url' => '/contact'],
    ];
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

/** Footer navigation columns (content guide, page 22). */
function footer_columns(): array
{
    return [
        'Medicines' => [
            ['Medicines we supply', '/medicines'],
            ['Conditions',          '/conditions'],
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
            ['Affordable access', '/affordable-access'],
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
