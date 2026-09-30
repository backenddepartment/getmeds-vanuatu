<?php
/**
 * Keys that must never be committed.
 *
 * Copy this file to data/secrets.php and fill it in. data/secrets.php is listed
 * in .gitignore, and .htaccess refuses everything under /data/ over HTTP.
 *
 * On a host where you can set environment variables, set NEWSDATA_API_KEY
 * there instead and leave this file out: the environment variable wins.
 */

return [
    // From https://newsdata.io/ -> Dashboard -> API Key.
    'newsdata_api_key' => '',
];
