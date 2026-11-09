<?php
/**
 * Refreshes the news cache (includes/news.php) in the background.
 *
 * A page that finds the cached headlines out of date shows them anyway and
 * asks for this address without waiting for the answer, so no visitor waits
 * on newsdata.io. Safe to be reached by anyone: news_items() fetches only when
 * the cache really is out of date, one refresh at a time, and backs off after
 * a failure, so calling it again and again costs nothing.
 */
require __DIR__ . '/includes/bootstrap.php';
require INC . '/news.php';

ignore_user_abort(true);   // the page that asked has already hung up
set_time_limit(60);
define('NEWS_REFRESHING', true);

header('Content-Type: text/plain; charset=utf-8');
header('X-Robots-Tag: noindex');
header('Cache-Control: no-store');
news_items();
echo "ok\n";
