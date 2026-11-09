<?php
/**
 * Article DRAFT (audit 2026-11-04: original articles in place of scraped news).
 * Awaiting pharmacist review. Hidden: noindex, out of the sitemap, the site
 * search, menus and the /articles listing. Built only from what the site
 * already says (/named-patient-supply, /patients, /how-it-works, FAQ).
 */
require __DIR__ . '/../../../includes/bootstrap.php';

$page = [
    'title'     => 'What is Named Patient Supply?',
    'seo_title' => 'What is Named Patient Supply? — Getmeds Vanuatu',
    'desc'      => 'How a medicine that is not normally available in Vanuatu can be ordered for one named patient, and what Getmeds needs from you.',
    'noindex'   => true,
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-sec--tight">
  <article class="g-wrap g-read g-legal g-article">
    <?php g_crumbs([['Home', '/'], ['Articles', '/articles'], ['Drafts', '/articles/drafts'], ['What is Named Patient Supply?', null]]); ?>
    <?php include __DIR__ . '/../banner.php'; ?>
    <h1>What is Named Patient Supply?</h1>
    <p class="g-legal__date">Patient guide · Draft</p>

    <p>Sometimes a doctor prescribes a medicine that Vanuatu does not normally have. It may be a specialist medicine, such as a cancer medicine, that is not kept in stock here. Named Patient Supply is a way to get that medicine for you.</p>
    <p>In simple words: if your doctor prescribes a medicine that Vanuatu does not normally have, Getmeds can try to bring it in just for you. We need your prescription. We tell you the price and time before you pay.</p>

    <h2>What does "named patient" mean?</h2>
    <p>The medicine is ordered for one patient, by name, based on that patient's prescription. It is not ordered for general sale, and it is not put on a shelf for anyone to buy.</p>

    <h2>Why does it exist?</h2>
    <p>Vanuatu is a small market. Many specialist medicines, including many cancer medicines, are not kept in stock. Named Patient Supply gives patients a way to get the medicine their doctor prescribed, even when it is not normally available here.</p>

    <h2>When is it used?</h2>
    <ul>
      <li>When a medicine is not available in Vanuatu.</li>
      <li>When a medicine is often out of stock.</li>
      <li>When a patient comes home from treatment overseas and needs to continue a medicine at home.</li>
      <li>When a doctor prescribes a specialist medicine for a serious condition.</li>
    </ul>

    <h2>The prescription comes first</h2>
    <p>Every Named Patient Supply request needs a valid prescription or treatment protocol from the patient's doctor. For your safety, we only supply the medicine your doctor prescribed. Your doctor decides your treatment. We supply the medicine. We cannot choose a medicine for you or change your treatment.</p>

    <h2>What we need from you</h2>
    <ul>
      <li>The patient's full name and contact details.</li>
      <li>The doctor's prescription or treatment protocol. A clear photo is fine.</li>
      <li>The medicine name, strength and amount.</li>
      <li>The doctor's or hospital's name and contact details.</li>
      <li>The date the medicine is needed, for example the next treatment cycle.</li>
      <li>Any medical papers needed for the import approval.</li>
    </ul>
    <p>If you do not have everything, contact us anyway. We will tell you what is missing. A family member, caregiver or guardian can also send the request for the patient.</p>

    <h2>How it works, step by step</h2>
    <ol>
      <li><strong>Enquiry.</strong> The patient, doctor or hospital contacts Getmeds.</li>
      <li><strong>Prescription check.</strong> Our pharmacist reviews the prescription or protocol.</li>
      <li><strong>Sourcing.</strong> We find a supplier and check the paperwork needed.</li>
      <li><strong>Quotation.</strong> We tell you the price and expected timing before you commit.</li>
      <li><strong>Approval and import.</strong> We arrange the import approval and order the medicine.</li>
      <li><strong>Supply.</strong> The medicine is dispensed to the patient, or prepared for the hospital for injectable treatment.</li>
    </ol>
    <p>While the medicine is on its way, we keep both the patient and the doctor updated. We also plan ahead for the next treatment cycle, so the medicine is ready when it is needed.</p>

    <h2>What you can expect from us</h2>
    <ul>
      <li>A clear answer about whether we can source the medicine.</li>
      <li>A price before you pay anything.</li>
      <li>An estimated time. This can be longer than for medicines we keep in stock.</li>
      <li>A pharmacist to explain how to take or handle your medicine.</li>
    </ul>

    <h2>An honest limit</h2>
    <p>Not every medicine can be supplied this way. Some medicines cannot be exported by the maker, or need papers that are not available. We will tell you honestly what is possible.</p>

    <h2>How to ask</h2>
    <p>Send us the prescription and we will check what is possible. You can use the <a href="<?= e(request_url('nps')) ?>">Named Patient Supply enquiry form</a>, <a href="<?= e(whatsapp_url('Hello Getmeds, I would like to ask about Named Patient Supply. I will send a photo of the prescription.')) ?>" rel="noopener">send a photo of the prescription on WhatsApp</a>, or call <a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a>. <?= e(cfg('reply_time')) ?> We are open <?= e(cfg('hours_long')) ?></p>
    <p>Read more on our <a href="<?= e(url('/named-patient-supply')) ?>">Named Patient Supply</a> page.</p>
  </article>
</section>

<?php include INC . '/footer.php'; ?>
