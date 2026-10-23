<?php
/**
 * The Product Range and every category and product page. .htaccess (and
 * tools/router.php for the static build) sends /product-range/... and every
 * address that matches no file or folder here, and this works out which page
 * it is, like getmeds.ph:
 *
 *   /product-range               all products       shop-listing.php
 *   /<category>/                 one category       shop-listing.php
 *   /<category>/<product>/       one product        shop-product.php
 *
 * Anything else gets the site's 404 page. A real folder always wins over a
 * category, so a category slug must never match a top-level folder's name.
 *
 * Old and stray addresses answer 301 to the one address each page has:
 * ?cat=<slug>, /product-range/<category>/..., /<product>/, a product under
 * the wrong category, and capitals. /medicines/shop/... is redirected by
 * medicines/shop/index.php.
 *
 *   POST /product-range/inquiry  a Send Inquiry form   shop-inquiry.php
 */
require_once __DIR__ . '/../includes/bootstrap.php';

$path = rtrim(current_path(), '/');

if ($path === '/product-range/inquiry') {
    include INC . '/shop-inquiry.php';
    return;
}

$cats = shop_categories();
$products = shop_products();

$shopCat = null;      // category slug, null for all products
$product = null;
$to      = null;      // where an old or stray address belongs

if ($path === '/product-range') {
    $old = isset($_GET['cat']) && is_string($_GET['cat']) ? shop_slug($_GET['cat']) : '';
    if ($old !== '' && isset($cats[$old])) {
        $to = shop_category_path($old);
    }
} elseif (strpos($path, '/product-range/') === 0) {
    // A category or product under /product-range/ lives at the root.
    $to = substr($path, strlen('/product-range')) . '/';
} else {
    $rest = trim($path, '/');
    $seg  = $rest === '' ? [] : array_map('rawurldecode', explode('/', $rest));
    $s    = shop_slug(end($seg) ?: '');

    if (count($seg) === 1 && isset($cats[$s])) {
        $shopCat = $s;
        if ($seg[0] !== $s) {
            $to = shop_category_path($s);
        }
    } elseif (count($seg) === 1 && isset($products[$s])) {
        $to = shop_product_path($products[$s]);
    } elseif (count($seg) === 2 && isset($products[$s])) {
        $product = $products[$s];
        if ('/' . $rest . '/' !== shop_product_path($product)) {
            $to = shop_product_path($product);
        }
    } else {
        include APP_ROOT . '/404.php';
        return;
    }
}

// Not written as a bare redirect_to( line: sitemap.php reads one as "this
// whole page is an old address" and would drop the range from the sitemap.
if ($to !== null) { redirect_to($to); }

include INC . ($product !== null ? '/shop-product.php' : '/shop-listing.php');
