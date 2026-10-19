<?php
/**
 * /medicines/shop and everything under it. .htaccess (and tools/router.php
 * for the static build) sends every /medicines/shop/... address here, and
 * this works out which page it is:
 *
 *   /medicines/shop                         all medicines       shop-listing.php
 *   /medicines/shop/<category>/             one category        shop-listing.php
 *   /medicines/shop/<category>/<medicine>/  one medicine        shop-product.php
 *
 * Old and stray addresses answer 301 to the one address each page has:
 * ?cat=<slug> (the menu's old links), /medicines/shop/<medicine>/, a medicine
 * under the wrong category, and capitals.
 *
 *   POST /medicines/shop/inquiry            a Send Inquiry form   shop-inquiry.php
 */
require __DIR__ . '/../../includes/bootstrap.php';

$path = current_path();

if ($path === '/medicines/shop/inquiry') {
    include INC . '/shop-inquiry.php';
    return;
}
$rest = trim((string) substr($path, strlen('/medicines/shop')), '/');
$seg  = $rest === '' ? [] : array_map('rawurldecode', explode('/', $rest));
$cats = shop_categories();
$products = shop_products();

$shopCat = null;      // category slug, null for all medicines, false for no such page
$product = null;
$to      = null;      // where an old or stray address belongs

switch (count($seg)) {
    case 0:
        $old = isset($_GET['cat']) && is_string($_GET['cat']) ? shop_slug($_GET['cat']) : '';
        if ($old !== '' && isset($cats[$old])) {
            $to = shop_category_path($old);
        }
        break;

    case 1:
        $s = shop_slug($seg[0]);
        if (isset($cats[$s])) {
            $shopCat = $s;
            if ($seg[0] !== $s) {
                $to = shop_category_path($s);
            }
        } elseif (isset($products[$s])) {
            $to = shop_product_path($products[$s]);
        } else {
            $shopCat = false;
        }
        break;

    case 2:
        $s = shop_slug($seg[1]);
        if (!isset($products[$s])) {
            $shopCat = false;
            break;
        }
        $product = $products[$s];
        if ('/' . $rest . '/' !== substr(shop_product_path($product), strlen('/medicines/shop'))) {
            $to = shop_product_path($product);
        }
        break;

    default:
        $shopCat = false;
}

// Not written as a bare redirect_to( line: sitemap.php reads one as "this
// whole page is an old address" and would drop the shop from the sitemap.
if ($to !== null) { redirect_to($to); }

// shop-listing.php answers 404 itself when $shopCat is false.
include INC . ($product !== null ? '/shop-product.php' : '/shop-listing.php');
