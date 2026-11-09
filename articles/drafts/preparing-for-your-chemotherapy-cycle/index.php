<?php
/**
 * Article DRAFT (audit 2026-11-04: original articles in place of scraped news).
 * Awaiting pharmacist review. Practical, non-clinical preparation only:
 * anything clinical is referred to the patient's oncology team. Built only
 * from what the site already says (/oncology, /patients, /how-it-works,
 * /policies-and-safety, /report-a-side-effect, FAQ).
 */
require __DIR__ . '/../../../includes/bootstrap.php';

$page = [
    'title'     => 'Preparing for your chemotherapy cycle',
    'seo_title' => 'Preparing for Your Chemotherapy Cycle — Getmeds Vanuatu',
    'desc'      => 'Practical steps to make sure your cancer medicine is ready for each treatment cycle in Vanuatu.',
    'noindex'   => true,
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-sec--tight">
  <article class="g-wrap g-read g-legal g-article">
    <?php g_crumbs([['Home', '/'], ['Articles', '/articles'], ['Drafts', '/articles/drafts'], ['Preparing for your chemotherapy cycle', null]]); ?>
    <?php include __DIR__ . '/../banner.php'; ?>
    <h1>Preparing for your chemotherapy cycle</h1>
    <p class="g-legal__date">Patient guide · Draft</p>

    <p>Cancer treatment is often given in cycles. Each cycle needs the right medicine at the right time for the treatment to work as planned. In the past, many patients in Vanuatu travelled overseas for treatment, then found the medicine for their next cycle was not available when they came home. Some missed cycles.</p>
    <p>This guide covers the practical side: making sure your medicine is ready on time. It is not medical advice.</p>

    <?php g_box('info', 'Getmeds Vanuatu supplies medicines. We do not diagnose cancer or choose treatment. Always follow the advice of your doctor or oncologist. Do not change a medicine, dose or schedule without speaking to them first.', 'Important'); ?>

    <h2>1. Keep your prescription or treatment protocol ready</h2>
    <p>Cancer medicines must follow your doctor's prescription or treatment protocol. For your safety, we only supply medicines that match what your doctor prescribed. Send us the prescription or protocol as early as you can. A clear photo is fine.</p>
    <p>If you had treatment overseas and need to continue at home, bring the prescription or treatment protocol from the overseas hospital. We help plan local supply so your treatment is not interrupted.</p>

    <h2>2. Tell us the date you need the medicine</h2>
    <p>Tell us the date of your next treatment cycle. Some medicines we keep in stock in Port Vila. Others we import or source for each patient, and that can take longer. Our pharmacist checks the prescription, whether the medicine is in stock, and the price. If it needs to be imported or specially ordered, we tell you and give you the earliest date we can.</p>
    <p>We will not promise a date we cannot keep. The earlier you contact us, the more time there is to get your medicine ready.</p>

    <h2>3. Agree the price before you pay</h2>
    <p>Prices depend on the medicine, the amount and where it comes from. We give you a clear quotation before you commit. You pay nothing until you agree.</p>

    <h2>4. Confirm your pick-up date</h2>
    <p>Once you agree to the price, we give you a schedule for pick-up and a time for a pharmacist to explain your medicine. Collect your medicine on the agreed date in Port Vila, or ask us about delivery to your island.</p>

    <h2>5. Know where an injectable is given</h2>
    <p>Some chemotherapy is given by injection or drip. These medicines must be given by trained staff in a hospital. If your medicine is an injectable, we prepare the supply for your session. Your session takes place at Vila Central Hospital or Vanuatu Private Hospital, as arranged by your treating team.</p>

    <h2>6. Let us handle storage</h2>
    <p>Some medicines must stay between 2 and 8 °C. We keep them in monitored refrigerators, and they travel in monitored cold boxes, logged from supplier to patient. Our pharmacist will explain how to handle your medicine once you have it.</p>

    <h2>7. Plan the next cycle</h2>
    <p>We do not just supply one order. We plan your next cycle with you, so your medicine is ready on time. For ongoing treatment, we keep in touch before each cycle.</p>
    <p>If your doctor changes your medicine or dose, send us the new prescription and we will update your supply.</p>

    <h2>8. Ask your oncology team about side effects</h2>
    <p>Questions about side effects, what to eat, or how you feel during treatment are for your doctor or oncology team. They know your treatment and your health.</p>
    <p>If you notice a reaction after starting a medicine, you can <a href="<?= e(url('/report-a-side-effect')) ?>">report a side effect</a> to us, and please tell your doctor too.</p>
    <?php g_box('emergency', 'If the reaction is serious — trouble breathing, swelling of the face or throat, a fever during chemotherapy, or bleeding that will not stop — go to hospital now.'); ?>

    <h2>9. Return unused medicine safely</h2>
    <p>Unused cancer medicines must not go in household rubbish or down the toilet. Bring them back to us in their original pack and we will dispose of them safely.</p>

    <h2>Need help?</h2>
    <p>Call <a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a>, <a href="<?= e(whatsapp_url('Hello Getmeds, I would like to plan my next treatment cycle.')) ?>" rel="noopener">message us on WhatsApp</a>, or <a href="<?= e(request_url('cancer')) ?>">send a cancer medicine request</a>. <?= e(cfg('reply_time')) ?> We are open <?= e(cfg('hours_long')) ?></p>
    <p>Read more on our <a href="<?= e(url('/oncology')) ?>">Oncology and Chemotherapy</a> page.</p>
  </article>
</section>

<?php include INC . '/footer.php'; ?>
