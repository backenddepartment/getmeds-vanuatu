<?php
/**
 * The old shop addresses. The Product Range moved to /product-range and its
 * categories and products to the root (product-range/index.php), so every
 * /medicines/shop/... address answers 301 to its new home, keeping any
 * search or filter in the query string:
 *
 *   /medicines/shop                      -> /product-range
 *   /medicines/shop/<category>/...       -> /<category>/...
 *   POST /medicines/shop/inquiry         still answered, for a page left open
 */
require __DIR__ . '/../../includes/bootstrap.php';

$path = current_path();

if (rtrim($path, '/') === '/medicines/shop/inquiry') {
    include INC . '/shop-inquiry.php';
    return;
}

$rest = trim((string) substr($path, strlen('/medicines/shop')), '/');
$qs   = (string) ($_SERVER['QUERY_STRING'] ?? '');
redirect_to(($rest === '' ? '/product-range/' : '/' . $rest . '/') . ($qs !== '' ? '?' . $qs : ''));
