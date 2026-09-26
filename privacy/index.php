<?php
/**
 * Privacy Policy (content guide, page 19).
 *
 * Plain text, a narrow 720px reading column, clear H2s and the last-updated
 * date at the top. No imagery.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Privacy Policy',
    'seo_title' => 'Privacy Policy — Getmeds Vanuatu',
    'desc'      => 'How Getmeds Vanuatu collects, uses and protects your personal and health information.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-sec--tight">
  <article class="g-wrap g-read g-legal">
    <?php g_crumbs([['Home', '/'], ['Privacy Policy', null]]); ?>
    <h1>Privacy Policy</h1>
    <p class="g-legal__date">Last updated: September 2026</p>

    <h2>What we collect</h2>
    <p>Your name and contact details, the patient's details, prescriptions and treatment protocols, the medicines you order, and your messages to us.</p>

    <h2>Why we use it</h2>
    <p>To check prescriptions, supply medicines, plan treatment cycles, contact you about your order, and meet legal and safety duties.</p>

    <h2>Who we share it with</h2>
    <p>Only people who need it to supply your medicine: our pharmacist and team, your doctor or hospital, suppliers and authorities for import approval, and delivery partners. We never sell your information.</p>

    <h2>How we keep it safe</h2>
    <p>We store records securely and limit who can see them. We keep records only as long as needed for your care and the law.</p>

    <h2>Your choices</h2>
    <p>You can ask to see or correct your information at any time.</p>

    <h2>Contact</h2>
    <p>Email <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a> or call <a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a>.</p>
  </article>
</section>

<?php include INC . '/footer.php'; ?>
