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
    // Brevo (https://app.brevo.com -> SMTP & API -> API Keys). Emails the
    // Send Inquiry forms on the medicine pages. The sender must be an address
    // verified in that Brevo account; leave it '' to use config.php's 'email'.
    'brevo_api_key'      => '',
    'brevo_sender_email' => '',
    // Google Sheet for every form (includes/sheets.php). The /exec address of
    // the Apps Script web app in docs/google-sheets.gs, and the same SECRET
    // that script holds.
    'sheets_webhook_url'    => '',
    'sheets_webhook_secret' => '',
];
