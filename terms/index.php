<?php
/**
 * Terms of Use (content guide, page 20). Same plain text layout as the
 * Privacy Policy: 720px reading column, last-updated date at the top.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Terms of Use',
    'seo_title' => 'Terms of Use — Getmeds Vanuatu',
    'desc'      => 'The terms for using the Getmeds Vanuatu website.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-sec--tight">
  <article class="g-wrap g-read g-legal">
    <?php g_crumbs([['Home', '/'], ['Terms of Use', null]]); ?>
    <h1>Terms of Use</h1>
    <p class="g-legal__date">Last updated: September 2026</p>

    <h2>Using this website</h2>
    <p>This website gives information about Getmeds Vanuatu and lets you send enquiries. No medicines are sold or paid for on this website.</p>

    <h2>Medical information</h2>
    <p>Information on this website is general and is not medical advice. Always follow the advice of your doctor, nurse or pharmacist.</p>

    <h2>Orders and prices</h2>
    <p>Every medicine request is checked by a pharmacist. Availability and prices are confirmed in a quotation before you commit. Prescription medicines need a valid prescription.</p>

    <h2>Links</h2>
    <p>We are not responsible for the content of other websites we link to.</p>

    <h2>Changes</h2>
    <p>We may update these terms. The date at the top shows the latest version.</p>
  </article>
</section>

<?php include INC . '/footer.php'; ?>
