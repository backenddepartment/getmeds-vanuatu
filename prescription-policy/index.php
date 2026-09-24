<?php
/**
 * Merged into /policies on 2026-09-24. The address is kept so old links,
 * bookmarks and search results still land in the right place.
 */
require __DIR__ . '/../includes/bootstrap.php';

header('Location: ' . url('/policies') . '#prescriptions', true, 301);
exit;
