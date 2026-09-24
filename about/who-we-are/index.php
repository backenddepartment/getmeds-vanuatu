<?php
/**
 * This page is now a section of the About Us page. The address is kept so old
 * links and bookmarks still land in the right place.
 */
require __DIR__ . '/../../includes/bootstrap.php';

header('Location: ' . url('/about') . '#who-we-are', true, 301);
exit;
