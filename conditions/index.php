<?php
/**
 * Removed page (owner decision, 2026-10-26): the Conditions index is replaced
 * by the full Product Range, so /conditions 301s there and old links and
 * search results still land somewhere useful. The single condition pages
 * (/conditions/<slug>) are kept.
 */
require __DIR__ . '/../includes/bootstrap.php';

redirect_to('/product-range/');
