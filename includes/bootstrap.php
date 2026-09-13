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

/** True when a config value is still an unsupplied [[PLACEHOLDER]]. */
function is_placeholder($value): bool
{
    return is_string($value) && strlen($value) > 4
        && substr($value, 0, 2) === '[['
        && substr($value, -2) === ']]';
}

/**
 * Render a config value, or a visible marker when it has not been supplied yet.
 * Markers are announced to screen readers as outstanding, not read as content.
 */
function val(string $key): string
{
    $v = cfg($key);

    if (is_placeholder($v)) {
        return '<span class="todo" role="mark">'
             . '<span class="todo__label">Needed</span>'
             . '<span class="todo__key">' . e(trim($v, '[]')) . '</span>'
             . '</span>';
    }

    return e((string) $v);
}

/** A block-level marker for legal copy that must not be drafted by the builder. */
function legal_copy_required(): string
{
    return '<div class="todo-block">'
         . '<p class="todo-block__head">Legal copy required</p>'
         . '<p class="todo-block__body">This section must be drafted and approved by a '
         . 'lawyer qualified in Vanuatu. It has deliberately not been written here. The '
         . 'headings below are the structure the copy should fill.</p>'
         . '</div>';
}

/** A marker for a photograph that has to be taken before launch. */
function photo_needed(string $subject): string
{
    return '<figure class="photo-needed"><div class="photo-needed__frame">'
         . '<p class="photo-needed__head">Photograph needed</p>'
         . '<p class="photo-needed__subject">' . e($subject) . '</p>'
         . '</div><figcaption class="photo-needed__note">Real photograph of the Port Vila '
         . 'premises or staff. Stock photography must not be used.</figcaption></figure>';
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
 * The site's navigation. The single source for both the header nav and the
 * landing pages that list their own children, so the two can never drift.
 *
 * Labels are fixed by the brief and are not to be reworded.
 * Hick's Law: at most 6 top-level items, at most 5 children each.
 */
function nav_tree(): array
{
    return [
        [
            'label' => 'Home',
            'url'   => '/',
        ],
        [
            'label' => 'Medicines',
            'url'   => '/medicines',
            'children' => [
                ['label' => 'Cancer Medicines',            'url' => '/medicines/cancer-medicines',
                 'blurb' => 'The groups of cancer medicine we supply against a prescription.'],
                ['label' => 'Medicines for Side Effects',  'url' => '/medicines/side-effects',
                 'blurb' => 'Anti-sickness, pain, mouth care and blood-count support.'],
                ['label' => 'Other Specialty Medicines',   'url' => '/medicines/other-specialty',
                 'blurb' => 'Cold-chain and hard-to-find medicines outside cancer care.'],
            ],
        ],
        [
            'label' => 'For Patients',
            'url'   => '/patients',
            'children' => [
                ['label' => 'How to Order',          'url' => '/patients/how-to-order',
                 'blurb' => 'The four steps, from prescription to collection.'],
                ['label' => 'What You Need',         'url' => '/patients/what-you-need',
                 'blurb' => 'The papers to bring or send before we can dispense.'],
                ['label' => 'Prices & Payment',      'url' => '/patients/prices-and-payment',
                 'blurb' => 'How pricing works, and how to get a figure for your medicine.'],
                ['label' => 'Talk to a Pharmacist',  'url' => '/patients/talk-to-a-pharmacist',
                 'blurb' => 'When to call, what a pharmacist can help with.'],
                ['label' => 'Delivery',              'url' => '/patients/delivery',
                 'blurb' => 'Collection in Port Vila, and sending to the other islands.'],
            ],
        ],
        [
            'label' => 'For Doctors & Hospitals',
            'url'   => '/providers',
            'children' => [
                ['label' => 'What We Stock',           'url' => '/providers/what-we-stock',
                 'blurb' => 'Formulary scope, availability and lead times.'],
                ['label' => 'Order for Your Hospital', 'url' => '/providers/order-for-your-hospital',
                 'blurb' => 'Institutional ordering, documentation and accounts.'],
                ['label' => 'Storage & Handling',      'url' => '/providers/storage-and-handling',
                 'blurb' => 'Cold chain, cytotoxic handling and transport.'],
                ['label' => 'Request a Quote',         'url' => '/providers/request-a-quote',
                 'blurb' => 'Pricing for a tender, ward stock or a named patient.'],
            ],
        ],
        [
            'label' => 'About Us',
            'url'   => '/about',
            'children' => [
                ['label' => 'Who We Are',        'url' => '/about/who-we-are',
                 'blurb' => 'What this pharmacy is, and what it is not.'],
                ['label' => 'Our Pharmacists',   'url' => '/about/our-pharmacists',
                 'blurb' => 'Who dispenses your medicine, and their registration.'],
                ['label' => 'Our Licences',      'url' => '/about/licences',
                 'blurb' => 'Our pharmacy licence and how to verify it.'],
                ['label' => 'Part of Getmeds',   'url' => '/about/part-of-getmeds',
                 'blurb' => 'How the wider group supports supply into Vanuatu.'],
            ],
        ],
        [
            'label' => 'Contact',
            'url'   => '/contact',
        ],
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

/** The nine footer links. Scanned, not decided from, so nine is correct here. */
function footer_links(): array
{
    return [
        ['label' => 'Privacy Policy',               'url' => '/privacy'],
        ['label' => 'Terms of Use',                 'url' => '/terms'],
        ['label' => 'Medical Disclaimer',           'url' => '/disclaimer'],
        ['label' => 'Prescription Policy',          'url' => '/prescription-policy'],
        ['label' => 'Shipping & Import Rules',      'url' => '/shipping-rules'],
        ['label' => 'Returns & Medicine Disposal',  'url' => '/returns'],
        ['label' => 'Report a Side Effect',         'url' => '/report-side-effect'],
        ['label' => 'Our Licences',                 'url' => '/about/licences'],
        ['label' => 'Complaints',                   'url' => '/complaints'],
    ];
}

/**
 * Photography. Loaded here so every page has plate() and band() without
 * remembering to ask for them.
 */
require_once INC . '/plates.php';
require_once INC . '/icons.php';
