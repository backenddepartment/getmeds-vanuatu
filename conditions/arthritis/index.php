<?php
/**
 * Removed page (owner decision, 2026-10-26): the condition pages are replaced
 * by the full Product Range, so this address 301s there and old links and
 * search results still land somewhere useful.
 */
require __DIR__ . '/../../includes/bootstrap.php';

redirect_to('/product-range/');
