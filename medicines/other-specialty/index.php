<?php
/**
 * Merged into /medicines on 2026-09-24. The address is kept so old links,
 * bookmarks and search results still land in the right place.
 */
require __DIR__ . '/../../includes/bootstrap.php';

header('Location: ' . url('/medicines') . '#other', true, 301);
exit;
