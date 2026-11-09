<?php
/**
 * Global healthcare news for /articles, from newsdata.io.
 *
 * The headlines are fetched by the server, never by the visitor's browser, so
 * the API key is not sent to anyone and a slow island connection does not wait
 * on a third party. The result is kept in data/cache/news.json and reused until
 * it is `news_cache_hours` old, which is what makes this "automatic": the first
 * visit after the cache expires refreshes it, with no cron job to set up.
 *
 * On a static build (GitHub Pages) there is no server at visit time. The same
 * code runs once while the pages are rendered, so the headlines are as fresh
 * as the last build; .github/workflows/deploy.yml rebuilds on a schedule.
 *
 * Nothing here can break the page. No key, no network, a bad reply or a daily
 * limit reached all end the same way: the last good headlines are shown, or
 * none are, and the rest of the page renders as usual.
 *
 * The key is read from the NEWSDATA_API_KEY environment variable, or from
 * data/secrets.php. It is never written in config.php, which is committed.
 */

declare(strict_types=1);

const NEWS_ENDPOINT = 'https://newsdata.io/api/1/latest';

// After a failed fetch, wait this long before trying again, so a run of
// visitors during an outage does not spend the day's credits on errors.
const NEWS_RETRY_SECONDS = 900;

// A refresh runs while a visitor waits for the page. Once this many seconds
// have gone, no further page of headlines is asked for; the next refresh,
// NEWS_RETRY_SECONDS later, fetches the rest.
const NEWS_TIME_BUDGET = 6;

/** The API key, or '' when none is configured. */
function news_api_key(): string
{
    $key = getenv('NEWSDATA_API_KEY');
    if (is_string($key) && trim($key) !== '') {
        return trim($key);
    }

    $file = APP_ROOT . '/data/secrets.php';
    if (is_file($file)) {
        $secrets = require $file;
        if (is_array($secrets) && !empty($secrets['newsdata_api_key'])) {
            return trim((string) $secrets['newsdata_api_key']);
        }
    }

    return '';
}

function news_cache_file(): string
{
    return APP_ROOT . '/data/cache/news.json';
}

/** A line in the PHP error log. The API key is never part of the message. */
function news_log(string $message): void
{
    error_log('[getmeds news] ' . $message);
}

/**
 * The headlines to show, newest first. Each is an array of plain text:
 * title, link, excerpt, image, source, icon, category, time.
 */
function news_items(): array
{
    if (!cfg('news_enabled', true)) {
        return [];
    }

    $limit = max(1, (int) cfg('news_count', 9));
    $ttl   = max(1, (int) cfg('news_cache_hours', 3)) * 3600;

    $cache = news_cache_read();
    $items = $cache['items'] ?? [];
    $now   = time();

    $fresh   = $cache && ($now - (int) ($cache['fetched_at'] ?? 0)) < $ttl;
    $backoff = $cache && ($now - (int) ($cache['attempted_at'] ?? 0)) < NEWS_RETRY_SECONDS;
    $key     = news_api_key();

    if ($fresh || $backoff || $key === '') {
        return array_slice($items, 0, $limit);
    }

    // Out of date but we have headlines: show them now, and refresh in the
    // background (news-refresh.php), so no visitor waits on newsdata.io. Only
    // with nothing to show at all, or while rendering a static build, does the
    // page wait for the fetch.
    if ($items && !is_static_build() && !defined('NEWS_REFRESHING') && news_refresh_in_background()) {
        return array_slice($items, 0, $limit);
    }

    // One refresh at a time. Anyone who arrives while it runs gets the
    // headlines already in hand instead of queueing a second request.
    $dir = dirname(news_cache_file());
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    $lock = @fopen($dir . '/news.lock', 'c');
    if ($lock && !flock($lock, LOCK_EX | LOCK_NB)) {
        fclose($lock);
        return array_slice($items, 0, $limit);
    }

    $fetched = news_fetch($key, $limit, $complete);

    if ($fetched) {
        $items = $fetched;
        // A refresh cut short (a page request failed, or time ran out) is
        // kept, but counted as fresh for only NEWS_RETRY_SECONDS, so the rest
        // is fetched soon rather than a full news_cache_hours later.
        $stamp = $complete ? $now : $now - $ttl + NEWS_RETRY_SECONDS;
        news_cache_write(['fetched_at' => $stamp, 'attempted_at' => $now, 'items' => $items]);
    } else {
        // Keep what we had, and note the attempt so the next visitor waits.
        news_cache_write([
            'fetched_at'   => (int) ($cache['fetched_at'] ?? 0),
            'attempted_at' => $now,
            'items'        => $items,
        ]);
    }

    if ($lock) {
        flock($lock, LOCK_UN);
        fclose($lock);
    }

    return array_slice($items, 0, $limit);
}

/**
 * Ask this same site for news-refresh.php and hang up without waiting for the
 * answer: the refresh runs in its own request while this page carries on.
 * Works under mod_php and PHP-FPM alike. False if the request could not be
 * sent, and then the caller refreshes in this request as before.
 */
function news_refresh_in_background(): bool
{
    $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
    if ($host === '' || !preg_match('/^[a-z0-9.\-]+(:\d+)?$/i', $host)) {
        return false;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $port  = (int) ($_SERVER['SERVER_PORT'] ?? ($https ? 443 : 80));
    $ip    = (string) ($_SERVER['SERVER_ADDR'] ?? '127.0.0.1');
    if ($ip === '' || $ip === '::1') {
        $ip = '127.0.0.1';
    }
    $name = (string) preg_replace('/:\d+$/', '', $host);
    $ctx  = stream_context_create(['ssl' => [
        // Our own server, reached by its own address: the name on the
        // certificate is the site's, which is checked, but not the address.
        'peer_name' => $name, 'verify_peer' => false, 'verify_peer_name' => false,
    ]]);
    $sock = @stream_socket_client(($https ? 'ssl://' : 'tcp://') . $ip . ':' . $port, $errno, $errstr, 1.5, STREAM_CLIENT_CONNECT, $ctx);
    if (!$sock) {
        news_log('background refresh: could not connect (' . $errstr . ')');
        return false;
    }
    fwrite($sock, 'GET ' . url('/news-refresh.php') . " HTTP/1.1\r\nHost: " . $host
        . "\r\nUser-Agent: getmeds-news-refresh\r\nConnection: close\r\n\r\n");
    fclose($sock);
    return true;
}

/**
 * A headline's topic for its stand-in tile: [label, icon]. Oncology when the
 * headline is about cancer, Vaccines for vaccines, else the outlet's category.
 */
function news_topic(array $n): array
{
    $t = strtolower($n['title'] . ' ' . $n['excerpt']);
    if (preg_match('/cancer|oncolog|tumou?r|chemo|leuk(a)?emia|lymphoma|myeloma|carcinoma|nsclc|metasta/', $t)) {
        return ['Oncology', 'vial'];
    }
    if (preg_match('/vaccin|immunis|immuniz/', $t)) {
        return ['Vaccines', 'shield'];
    }
    if (preg_match('/drug|medicine|pharma|fda|approval|clinical trial/', $t)) {
        return ['Medicines', 'pill'];
    }
    return strcasecmp($n['category'], 'Science') === 0 ? ['Science', 'script'] : ['Health', 'hosp'];
}

/**
 * True when a headline is about health or medicine. The home page shows only
 * these, so an off-topic science story from the feed never leads the page.
 */
function news_is_health(array $n): bool
{
    if (strcasecmp($n['category'], 'Science') !== 0) {
        return true;
    }
    return news_topic($n)[0] !== 'Science';
}

/**
 * One headline card, as shown on /articles and in the home page's Latest
 * articles section. Links to the outlet's own page in a new tab.
 */
function news_card(array $n): void
{
    ?>
      <a class="g-article-card" data-filter-item href="<?= e($n['link']) ?>" target="_blank" rel="noopener noreferrer nofollow">
        <div class="g-article-card__media<?= $n['image'] === '' ? ' g-article-card__media--none' : '' ?>">
          <?php /* The stand-in, behind the outlet's photo: it shows when an
                   article has no photo, or its photo fails to load (guide.js
                   removes a broken one). A branded tile in the Getmeds colours
                   with the article's topic, drawn in CSS: no image to load,
                   and no "missing picture" icon that reads as broken. */
                [$topic, $topicIcon] = news_topic($n); ?>
          <span class="g-article-card__ph g-article-card__ph--brand g-article-card__ph--<?= e(strtolower($topic)) ?>" aria-hidden="true">
            <?= gi($topicIcon) ?>
            <span class="g-article-card__ph-topic"><?= e($topic) ?></span>
          </span>
          <?php if ($n['image'] !== ''): ?>
          <img src="<?= e($n['image']) ?>" alt="" width="800" height="500" loading="lazy" decoding="async" referrerpolicy="no-referrer" data-fallback>
          <?php endif; ?>
          <?php if ($n['category'] !== ''): ?><span class="g-article-card__badge"><?= e($n['category']) ?></span><?php endif; ?>
          <?php if ($n['time']): ?><span class="g-article-card__tag"><?= e(gmdate('j M Y', $n['time'])) ?></span><?php endif; ?>
        </div>
        <h3 class="g-article-card__title"><?= e($n['title']) ?></h3>
        <?php /* Some outlets send the headline again as the summary: shown
                 only when it says something the title does not. */
              if ($n['excerpt'] !== '' && stripos($n['excerpt'], mb_substr($n['title'], 0, 40)) !== 0): ?><p class="g-article-card__excerpt"><?= e($n['excerpt']) ?></p><?php endif; ?>
        <div class="g-article-card__foot">
          <?php /* The outlet's own icon. The globe behind it shows when the
                   outlet has none, or its icon fails to load (guide.js). */ ?>
          <span class="g-article-card__source">
            <?php if (($n['icon'] ?? '') !== ''): ?><img class="g-article-card__favicon" src="<?= e($n['icon']) ?>" alt="" width="20" height="20" loading="lazy" decoding="async" referrerpolicy="no-referrer" data-fallback><?php endif; ?><?= gi('globe') ?>
            <?= e($n['source'] !== '' ? $n['source'] : 'News outlet') ?>
          </span>
          <span class="g-article-card__go">Read article <?= gi('arrow') ?><span class="g-sr"> (opens in a new tab)</span></span>
        </div>
      </a>
    <?php
}

function news_cache_read(): ?array
{
    $file = news_cache_file();
    if (!is_file($file)) {
        return null;
    }
    $data = json_decode((string) @file_get_contents($file), true);
    return is_array($data) && isset($data['items']) && is_array($data['items']) ? $data : null;
}

function news_cache_write(array $data): void
{
    $file = news_cache_file();
    $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    // Written beside the file and renamed over it, so a reader never sees half.
    $tmp = $file . '.' . bin2hex(random_bytes(4)) . '.tmp';
    if ($json === false || @file_put_contents($tmp, $json, LOCK_EX) === false) {
        news_log('could not write the cache; is data/cache writable?');
        return;
    }
    if (!@rename($tmp, $file)) {
        @unlink($tmp);
        news_log('could not replace the cache file');
    }
}

/**
 * Ask newsdata.io for headlines. Returns the cleaned list, or null on failure.
 * One request returns up to 10 articles on the free plan and costs one credit,
 * so enough are read to reach $limit, plus one to make up for the duplicates
 * that get dropped, and never more than five.
 *
 * A visitor is waiting while this runs, so after NEWS_TIME_BUDGET seconds no
 * further page is asked for. $complete is set false when the fetch stopped
 * early for that reason or because a page request failed.
 */
function news_fetch(string $key, int $limit, ?bool &$complete = null): ?array
{
    $complete = true;
    $started  = microtime(true);

    $base = array_filter([
        'q'        => (string) cfg('news_query', ''),
        'category' => (string) cfg('news_categories', 'health'),
        'language' => (string) cfg('news_language', 'en'),
    ], static fn($v) => $v !== '');

    // Extras that not every plan accepts. If the API refuses the request with
    // them, it is tried once more without.
    $extras = array_filter([
        'removeduplicate' => '1',
        'prioritydomain'  => (string) cfg('news_priority', 'top'),
    ], static fn($v) => $v !== '');

    $items = [];
    $seen  = [];
    $page  = '';

    $requests = min(5, (int) ceil($limit / 10) + 1);

    for ($i = 0; $i < $requests && count($items) < $limit; $i++) {
        if ($i > 0 && microtime(true) - $started > NEWS_TIME_BUDGET) {
            $complete = false;
            break;
        }
        $params = $base + $extras + ($page !== '' ? ['page' => $page] : []);
        $reply  = news_request($key, $params, $code);

        // 400 and 422 are how the API refuses a parameter. A wrong key (401) or
        // a spent limit (429) would fail the same way twice, so is not retried.
        if ($reply === null && $extras && in_array($code, [400, 422], true)) {
            $extras = [];
            $reply  = news_request($key, $base + ($page !== '' ? ['page' => $page] : []), $code);
        }
        if ($reply === null) {
            $complete = false;
            break;
        }

        foreach ($reply['results'] as $raw) {
            $item = is_array($raw) ? news_clean($raw) : null;
            if (!$item) {
                continue;
            }
            // The same wire story is carried by many outlets under one headline.
            $fingerprint = preg_replace('/[^a-z0-9]+/', '', strtolower($item['title']));
            if (isset($seen[$fingerprint])) {
                continue;
            }
            $seen[$fingerprint] = true;
            $items[] = $item;
        }

        $page = is_string($reply['nextPage'] ?? null) ? $reply['nextPage'] : '';
        if ($page === '') {
            break;
        }
    }

    if (!$items) {
        return null;
    }

    usort($items, static fn($a, $b) => $b['time'] <=> $a['time']);
    return array_slice($items, 0, $limit);
}

/**
 * One API call. Returns the decoded reply on success, null on any failure.
 * $code is set to the HTTP status, 0 if there was no reply at all.
 */
function news_request(string $key, array $params, ?int &$code = null): ?array
{
    $url = NEWS_ENDPOINT . '?' . http_build_query(['apikey' => $key] + $params);
    [$code, $body] = news_http_get($url);

    $data = is_string($body) ? json_decode($body, true) : null;

    if ($code !== 200 || !is_array($data) || ($data['status'] ?? '') !== 'success' || !is_array($data['results'] ?? null)) {
        $why = is_array($data) && is_array($data['results'] ?? null)
            ? ($data['results']['code'] ?? '') . ' ' . ($data['results']['message'] ?? '')
            : 'no usable reply';
        news_log('request failed (HTTP ' . $code . '): ' . trim(str_replace($key, '***', (string) $why)));
        return null;
    }

    return $data;
}

/** GET a URL. Returns [status code, body]; [0, null] if nothing came back. */
function news_http_get(string $url): array
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 4,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_PROTOCOLS      => CURLPROTO_HTTPS,
            CURLOPT_USERAGENT      => 'getmeds-vanuatu-news/1.0',
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        if ($body === false) {
            news_log('network error: ' . curl_error($ch));
        }
        curl_close($ch);
        return [$code, $body === false ? null : $body];
    }

    $ctx = stream_context_create(['http' => [
        'timeout'         => 8,
        'ignore_errors'   => true,
        'follow_location' => 0,
        'header'          => "Accept: application/json\r\nUser-Agent: getmeds-vanuatu-news/1.0\r\n",
    ]]);
    $body = @file_get_contents($url, false, $ctx);
    $code = 0;
    foreach ($http_response_header ?? [] as $h) {
        if (preg_match('~^HTTP/\S+\s+(\d{3})~', $h, $m)) {
            $code = (int) $m[1];
        }
    }
    return [$code, $body === false ? null : $body];
}

/**
 * Turn one article from the API into plain, safe text. Returns null for an
 * article that cannot be shown: no headline, or no link to the original.
 */
function news_clean(array $raw): ?array
{
    if (!empty($raw['duplicate'])) {
        return null;
    }

    $title = news_text($raw['title'] ?? '', 140);
    $link  = news_url($raw['link'] ?? '', false);
    if ($title === '' || $link === '') {
        return null;
    }

    $excerpt = news_text($raw['description'] ?? '', 170);

    // The static build refuses to publish a page containing these words,
    // because they normally mean PHP printed an error into it. A headline that
    // happens to use them would stop the whole site deploying.
    foreach (['Fatal error', 'Parse error'] as $needle) {
        if (strpos($title . ' ' . $excerpt, $needle) !== false) {
            return null;
        }
    }

    // An article carries several categories, often led by "top", which says
    // nothing on a card. Show the one that was asked for, if it has it.
    $categories = array_values(array_filter((array) ($raw['category'] ?? []), 'is_string'));
    $wanted     = array_map('trim', explode(',', strtolower((string) cfg('news_categories', 'health'))));
    $category   = array_values(array_intersect($wanted, array_map('strtolower', $categories)))[0]
               ?? array_values(array_diff($categories, ['top']))[0]
               ?? '';

    // An article with no date gets none, rather than the moment it was fetched.
    $date = $raw['pubDate'] ?? '';
    $time = is_string($date) && trim($date) !== '' ? strtotime($date . ' UTC') : 0;

    return [
        'title'    => $title,
        'link'     => $link,
        'excerpt'  => $excerpt,
        'image'    => news_url($raw['image_url'] ?? '', true),
        'source'   => news_text($raw['source_name'] ?? ($raw['source_id'] ?? ''), 40),
        'icon'     => news_url($raw['source_icon'] ?? '', true),
        'category' => ucfirst(news_text($category, 24)),
        'time'     => $time ?: 0,
    ];
}

/** Plain text: tags and entities removed, whitespace collapsed, cut to length. */
function news_text($value, int $max): string
{
    if (!is_string($value)) {
        return '';
    }
    $text = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = trim((string) preg_replace('/\s+/u', ' ', $text));
    if (mb_strlen($text) > $max) {
        $text = rtrim(mb_substr($text, 0, $max - 1), " ,;:.-") . '…';
    }
    return $text;
}

/** A web address, or '' if it is not one. Images must be https. */
function news_url($value, bool $httpsOnly): string
{
    if (!is_string($value) || strlen($value) > 2000) {
        return '';
    }
    $value = trim($value);
    if (filter_var($value, FILTER_VALIDATE_URL) === false) {
        return '';
    }
    $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));
    $ok = $httpsOnly ? ['https'] : ['http', 'https'];
    return in_array($scheme, $ok, true) ? $value : '';
}
