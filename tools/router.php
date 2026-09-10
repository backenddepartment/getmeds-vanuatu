<?php
/**
 * Router for PHP's built-in server, used only by tools/build-static.php.
 *
 * The built-in server already serves index.php for a directory request, so the
 * only thing missing is what .htaccess does for the two cases below: refuse the
 * private directories, and send anything unrouted to 404.php so the static build
 * can capture a real 404 page.
 *
 * This file is never deployed. It is a build-time shim, not part of the site.
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$root = __DIR__ . '/..';

// Mirrors the RewriteRule ^data/ and ^includes/ denials in .htaccess.
if (preg_match('~^/(data|includes|tools|\.github)(/|$)~', $path)) {
    http_response_code(403);
    echo 'Forbidden';
    return true;
}

$file = realpath($root . $path);
$rootReal = realpath($root);

// A real file that is not PHP: let the server stream it.
if ($file && $rootReal && strpos($file, $rootReal) === 0 && is_file($file)) {
    return false;
}

// A directory holding an index.php: let the server handle it.
if ($file && is_dir($file) && is_file($file . '/index.php')) {
    return false;
}

// Anything else is a 404, and the static build wants that page's HTML.
http_response_code(404);
require $root . '/404.php';
return true;
