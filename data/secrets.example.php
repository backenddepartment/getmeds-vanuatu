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
    // Brevo SMTP (includes/mailer.php): emails every inquiry form to the team
    // and a confirmation to the person. In Brevo: SMTP & API -> SMTP tab.
    // Login is the "Login" shown there; key is an SMTP key you generate there
    // (not an API key). The sender must be verified in Brevo (Senders,
    // Domains & Dedicated IPs); '' uses config.php's 'email'.
    'brevo_smtp_login'   => '',
    'brevo_smtp_key'     => '',
    'brevo_sender_email' => '',
    'brevo_sender_name'  => 'Getmeds Vanuatu',
    // Google Sheet for every form (includes/sheets.php). The /exec address of
    // the Apps Script web app in docs/google-sheets.gs, and the same SECRET
    // that script holds.
    'sheets_webhook_url'    => '',
    'sheets_webhook_secret' => '',
];
