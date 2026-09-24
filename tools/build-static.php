<?php
/**
 * Render the site to static HTML.
 *
 * This exists because GitHub Pages runs no PHP. Every route is fetched from a
 * throwaway localhost PHP server and written to dist/<route>/index.html, which
 * is the shape a static host serves clean URLs from without any rewrite rules.
 *
 * What this cannot carry across, and what is done about it:
 *
 *   - The enquiry form and the quote form need a POST handler. GV_STATIC=1
 *     makes both pages render the phone and email instead of a form that would
 *     silently discard what someone typed. See includes/components.php.
 *   - The healthcare-professional gate is a POST plus a cookie. GV_PROVIDER_GATE
 *     decides what happens: 'open' publishes /providers (the default here,
 *     because an unreachable section is worse than an ungated one), 'closed'
 *     serves the contact-only variant, and 'skip' leaves /providers out of the
 *     build entirely.
 *   - .htaccess does nothing on a static host. The security headers, the
 *     directory denials and the cache policy in it are Apache-only. A static
 *     host that supports headers needs them re-expressed in its own config.
 *
 * Usage:
 *   php tools/build-static.php --base=/repo-name --origin=https://user.github.io
 */

$opts = getopt('', ['base::', 'origin::', 'out::', 'port::', 'gate::']);
$base   = rtrim($opts['base']   ?? '', '/');
$origin = rtrim($opts['origin'] ?? '', '/');
$out    = $opts['out']  ?? __DIR__ . '/../dist';
$port   = (int) ($opts['port'] ?? 8181);
$gate   = $opts['gate'] ?? 'open';

$root = realpath(__DIR__ . '/..');
$out  = rtrim(str_replace('\\', '/', $out), '/');

fwrite(STDERR, "root   $root\nbase   " . ($base === '' ? '(site root)' : $base)
             . "\norigin " . ($origin === '' ? '(none)' : $origin) . "\ngate   $gate\nout    $out\n\n");

/* ---------- 1. Work out the routes from the filesystem ------------------- */
/* Every index.php under the root is a route. Nothing is hand-listed, so a new
   page is published by existing, not by being remembered here. */

$routes = ['/'];
$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);
foreach ($it as $file) {
    $path = str_replace('\\', '/', $file->getPathname());
    if (basename($path) !== 'index.php') {
        continue;
    }
    $rel = trim(substr($path, strlen($root)), '/');
    if ($rel === 'index.php') {
        continue;                       // already have '/'
    }
    foreach (['includes/', 'data/', 'tools/', 'vendor/', 'node_modules/', '.github/'] as $skip) {
        if (strpos($rel, $skip) === 0) {
            continue 2;
        }
    }
    $routes[] = '/' . dirname($rel);
}
sort($routes);

if ($gate === 'skip') {
    $routes = array_values(array_filter($routes, fn($r) => strpos($r, '/providers') !== 0));
}

fwrite(STDERR, count($routes) . " routes found\n");

/* ---------- 2. Start the render server ----------------------------------- */

$env = array_merge(getenv(), [
    'GV_STATIC'        => '1',
    'GV_BASE_PATH'     => $base,
    'GV_SITE_ORIGIN'   => $origin,
    'GV_PROVIDER_GATE' => $gate === 'skip' ? 'open' : $gate,
]);
foreach (['GV_STATIC', 'GV_BASE_PATH', 'GV_SITE_ORIGIN', 'GV_PROVIDER_GATE'] as $k) {
    putenv("$k={$env[$k]}");              // this process too, for any local use
}

/* The server's log goes to a file, never to a pipe.
   PHP's built-in server writes about three lines per request to stderr. Piped,
   that fills the OS pipe buffer after roughly twenty requests - 4KB on Windows -
   at which point the server blocks on write and silently stops serving, and the
   build stalls halfway through with no error at all. A file has no such limit. */
$logFile = sys_get_temp_dir() . '/gv-render-server.log';
$descriptors = [
    1 => ['file', $logFile, 'w'],
    2 => ['file', $logFile, 'a'],
];

/* Array form, so the server is exec'd directly instead of through a shell.
   With a shell in between, proc_terminate() kills the shell and leaves the PHP
   server orphaned, and proc_close() then waits on it forever. It also means the
   environment goes through proc_open rather than a platform-specific `set` or
   `VAR=x` prefix. */
$server = proc_open(
    [PHP_BINARY, '-S', "127.0.0.1:$port", '-t', $root, __DIR__ . '/router.php'],
    $descriptors,
    $pipes,
    $root,
    $env
);
if (!is_resource($server)) {
    fwrite(STDERR, "could not start the render server\n");
    exit(1);
}

// Wait for it to accept connections rather than sleeping a fixed amount.
$up = false;
for ($i = 0; $i < 100; $i++) {
    $c = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.3);
    if ($c) { fclose($c); $up = true; break; }
    usleep(150000);
}
if (!$up) {
    fwrite(STDERR, "render server never came up on port $port\n");
    proc_terminate($server);
    exit(1);
}

/* ---------- 3. Fetch every route ----------------------------------------- */

$fail = 0;
$written = 0;
$bytes = 0;

function put(string $file, string $body): void
{
    @mkdir(dirname($file), 0777, true);
    file_put_contents($file, $body);
}

foreach ($routes as $route) {
    $url  = "http://127.0.0.1:$port" . ($route === '/' ? '/' : $route . '/');
    $ctx  = stream_context_create(['http' => [
        'timeout'       => 30,
        'ignore_errors' => true,
        // Do NOT follow. A merged page's 301 has to stay visible here so it can
        // be written out as an HTML redirect; followed, every old address would
        // get its own full copy of the target page instead.
        'follow_location' => 0,
        'max_redirects'   => 1,
        'header'        => "User-Agent: getmeds-static-build\r\n",
    ]]);
    $body = @file_get_contents($url, false, $ctx);
    $code = 0;
    $to   = '';
    foreach ($http_response_header ?? [] as $h) {
        if (preg_match('~^HTTP/\S+\s+(\d{3})~', $h, $m)) { $code = (int) $m[1]; }
        if (preg_match('~^Location:\s*(\S+)~i', $h, $m))  { $to   = $m[1]; }
    }

    /* Pages merged into another page answer 301. Static hosting has no 301, so
       write the HTML equivalent: a canonical link for crawlers and a meta
       refresh for people, with a real link in case both are ignored. Without
       this every kept-alive old address would fail the build. */
    if ($code >= 300 && $code < 400 && $to !== '') {
        $dest = preg_replace('~^https?://[^/]+~', '', $to);
        $html = "<!doctype html>\n<html lang=\"en\">\n<head>\n"
              . "<meta charset=\"utf-8\">\n"
              . "<title>Moved</title>\n"
              . '<link rel="canonical" href="' . htmlspecialchars($dest, ENT_QUOTES) . "\">\n"
              . '<meta http-equiv="refresh" content="0; url=' . htmlspecialchars($dest, ENT_QUOTES) . "\">\n"
              . "<meta name=\"robots\" content=\"noindex\">\n</head>\n<body>\n"
              . '<p>This page has moved to <a href="' . htmlspecialchars($dest, ENT_QUOTES) . '">'
              . htmlspecialchars($dest, ENT_QUOTES) . "</a>.</p>\n</body>\n</html>\n";
        put($out . ($route === '/' ? '' : $route) . '/index.html', $html);
        $written++;
        $bytes += strlen($html);
        fwrite(STDERR, sprintf("  ->   %-42s %s\n", $route, $dest));
        continue;
    }

    $bad = [];
    if ($body === false || $body === '')            { $bad[] = 'empty response'; }
    if ($code !== 200)                              { $bad[] = "HTTP $code"; }
    if ($body && stripos($body, '</html>') === false) { $bad[] = 'truncated (no </html>)'; }
    foreach (['Fatal error', 'Parse error', '<b>Warning</b>', '<b>Notice</b>', '<b>Deprecated</b>'] as $needle) {
        if ($body && strpos($body, $needle) !== false) { $bad[] = "PHP $needle"; }
    }

    if ($bad) {
        fwrite(STDERR, sprintf("  FAIL %-42s %s\n", $route, implode('; ', $bad)));
        $fail++;
        continue;
    }

    $file = $out . ($route === '/' ? '' : $route) . '/index.html';
    put($file, $body);
    $written++;
    $bytes += strlen($body);
    fwrite(STDERR, sprintf("  ok   %-42s %6.1f KB\n", $route, strlen($body) / 1024));
}

/* The 404 page. GitHub Pages serves /404.html for anything it cannot find. */
$ctx  = stream_context_create(['http' => ['timeout' => 30, 'ignore_errors' => true]]);
$body = @file_get_contents("http://127.0.0.1:$port/__definitely-not-a-page__", false, $ctx);
if ($body && stripos($body, '</html>') !== false) {
    put($out . '/404.html', $body);
    fwrite(STDERR, "  ok   /404.html\n");
} else {
    fwrite(STDERR, "  WARN 404 page did not render; the host default will be used\n");
}

proc_terminate($server);
proc_close($server);

/* ---------- 4. Copy the assets ------------------------------------------- */

function copy_tree(string $from, string $to): int
{
    if (!is_dir($from)) { return 0; }
    $n = 0;
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($from, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($it as $item) {
        $target = $to . '/' . $it->getSubPathName();
        if ($item->isDir()) {
            @mkdir($target, 0777, true);
        } else {
            @mkdir(dirname($target), 0777, true);
            copy($item->getPathname(), $target);
            $n++;
        }
    }
    return $n;
}

$assets = copy_tree($root . '/assets', $out . '/assets');
foreach (['robots.txt', 'sitemap.xml', 'CNAME'] as $extra) {
    if (is_file($root . '/' . $extra)) {
        copy($root . '/' . $extra, $out . '/' . $extra);
    }
}

/* Jekyll would otherwise reprocess this and drop anything beginning with _. */
file_put_contents($out . '/.nojekyll', '');

/* ---------- 5. Report ---------------------------------------------------- */

fwrite(STDERR, sprintf(
    "\n%d pages (%.0f KB of HTML), %d asset files\n",
    $written, $bytes / 1024, $assets
));

if ($fail) {
    fwrite(STDERR, "$fail route(s) failed. Not publishing a half-built site.\n");
    exit(1);
}
if ($written < 2) {
    fwrite(STDERR, "suspiciously few pages rendered. Refusing to publish.\n");
    exit(1);
}
fwrite(STDERR, "static build OK\n");
