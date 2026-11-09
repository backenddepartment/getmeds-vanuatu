<?php
/**
 * The banner every article draft shows at the top. Remove the include when a
 * draft is published (see README.md in this folder).
 */
if (!defined('APP_ROOT')) {
    // Opened directly: not a page.
    http_response_code(404);
    exit;
}
?>
<p class="g-draft-banner" role="note"><?= gi('alert') ?><span><strong>DRAFT — awaiting pharmacist review.</strong> This article is not published. It is hidden from search engines, the site search and the menus. Please check every statement before it goes live.</span></p>
