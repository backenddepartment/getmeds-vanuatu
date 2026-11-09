<?php
/**
 * Article DRAFT (audit 2026-11-04: original articles in place of scraped news).
 * Awaiting pharmacist review. The site does not describe how island delivery
 * is arranged (carrier, cost, days), so this draft says the pharmacist
 * explains the options when the request is confirmed. Built only from what
 * the site already says (/how-it-works, /pacific-access, /patients, FAQ,
 * /policies-and-safety).
 */
require __DIR__ . '/../../../includes/bootstrap.php';

$page = [
    'title'     => 'How to request a medicine from Santo, Tanna or the outer islands',
    'seo_title' => 'Request a Medicine from Santo, Tanna or the Outer Islands — Getmeds Vanuatu',
    'desc'      => 'Living outside Port Vila? How to send your prescription to Getmeds Vanuatu and ask about getting your medicine to your island.',
    'noindex'   => true,
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-sec--tight">
  <article class="g-wrap g-read g-legal g-article">
    <?php g_crumbs([['Home', '/'], ['Articles', '/articles'], ['Drafts', '/articles/drafts'], ['Requesting from the outer islands', null]]); ?>
    <?php include __DIR__ . '/../banner.php'; ?>
    <h1>How to request a medicine from Santo, Tanna or the outer islands</h1>
    <p class="g-legal__date">Patient guide · Draft</p>

    <p>Getmeds Vanuatu is based in Port Vila. From here, we supply cancer medicines and other important medicines to patients and healthcare providers across Vanuatu's 83 islands. You do not need to travel to Port Vila to ask us for help. You can start your request from wherever you are.</p>

    <h2>What you need</h2>
    <ul>
      <li>Your doctor's prescription or treatment protocol. A clear photo is fine.</li>
      <li>Your name, and a phone number where we can reach you.</li>
      <li>The name of the medicine you need.</li>
      <li>The date you need it, for example your next treatment cycle.</li>
    </ul>
    <p>Depending on the medicine, we may also ask for the doctor's or hospital's details, or other medical papers if the medicine needs to be imported. If you do not have everything, contact us anyway. We will tell you what is missing.</p>
    <p>Prescription medicines need a valid prescription. If you do not yet have one, please see a doctor first.</p>

    <h2>The quickest way: a photo on WhatsApp</h2>
    <p>If you have a phone with WhatsApp, take a clear photo of the whole prescription and <a href="<?= e(whatsapp_url('Hello Getmeds, I live outside Port Vila and would like to request a medicine. I will send a photo of my prescription.')) ?>" rel="noopener">send it to us on WhatsApp</a>. Tell us your name, which island you are on, and the date you need the medicine.</p>
    <p>Make sure the whole prescription is clear and easy to read, including the doctor's name and the medicine details.</p>

    <h2>Other ways to reach us</h2>
    <ul>
      <li><strong>Phone:</strong> call <a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a>, <?= e(cfg('hours_long')) ?></li>
      <li><strong>Online form:</strong> <a href="<?= e(request_url('patient')) ?>">send a patient enquiry</a> and attach a photo, scan or PDF of the prescription.</li>
      <li><strong>Email:</strong> <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a></li>
      <li><strong>In person:</strong> if you or a family member are in Port Vila, visit us at <?= address_inline() ?>.</li>
    </ul>
    <p>A family member, caregiver or guardian can send the request for you. They give the patient's details, their own details as the contact person, and the patient's prescription.</p>

    <h2>What happens next</h2>
    <ol>
      <li><strong>Our pharmacist checks your request.</strong> We check the prescription, whether the medicine is in stock, and the price. <?= e(cfg('reply_time')) ?></li>
      <li><strong>You get a clear quotation.</strong> We tell you the price before you commit. You pay nothing until you agree.</li>
      <li><strong>We give you a date.</strong> If the medicine needs to be imported or specially ordered, we tell you and give you the earliest date we can. We will not promise a date we cannot keep.</li>
      <li><strong>We agree how the medicine reaches you.</strong> You can collect in Port Vila, or ask us about delivery to your island. Our pharmacist will explain the options for your island when we confirm your request.</li>
    </ol>

    <h2>Medicines that must stay cold</h2>
    <p>Some medicines must stay between 2 and 8 °C. We keep them in monitored refrigerators, and they travel in monitored cold boxes, logged from supplier to patient. Tell us where you are, so we can plan how your medicine travels.</p>

    <h2>If you need an injection or drip</h2>
    <p>Some cancer medicines are given by injection or drip, and must be given by trained staff in a hospital. These sessions take place at Vila Central Hospital or Vanuatu Private Hospital, as arranged by your treating team. Talk to your doctor about where and when your treatment will be given.</p>

    <h2>Planning ahead</h2>
    <p>If you take a medicine for a long time, or have treatment in cycles, contact us early for each supply. Distance can add time. For ongoing treatment, we keep in touch before each cycle, and if your doctor changes your medicine or dose, send us the new prescription and we will update your supply.</p>

    <h2>Outside Vanuatu?</h2>
    <p>We also welcome enquiries from other Pacific countries. Tell us your country, the medicine you need, and whether you are a patient or a healthcare provider. Import rules, paperwork and freight costs differ by country, and we will explain these before you commit. See <a href="<?= e(url('/pacific-access')) ?>">Pacific Islands Access</a>.</p>
  </article>
</section>

<?php include INC . '/footer.php'; ?>
