<?php
/**
 * Oncology and Chemotherapy (content guide, page 03). The flagship page.
 *
 * Care Pink accents on the normal navy/blue/green system. Sections use the
 * full content width (the old sticky in-page side menu was removed).
 * No needles, IV drips or sick-looking people anywhere on the page.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Oncology and Chemotherapy',
    'seo_title' => 'Chemotherapy and Cancer Medicines in Vanuatu — Getmeds Vanuatu',
    'desc'      => "Getmeds Vanuatu supplies chemotherapy and cancer medicines in Port Vila against a doctor's prescription. In stock, imported or sourced for each patient.",
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-bg-white g-onc-hero">
  <div class="g-wrap g-split g-split--7-5">
    <div>
      <span class="g-label">First chemotherapy pharmacy in the Pacific</span>
      <h1>Cancer and chemotherapy medicines in Vanuatu</h1>
      <p class="g-hero__lede">Getmeds Vanuatu is the first chemotherapy pharmacy in the Pacific. We supply cancer medicines in Port Vila, so patients can continue their treatment closer to home.</p>
      <div class="g-btns">
        <?= g_btn('Request a Cancer Medicine', request_url('cancer'), 'primary') ?>
        <?= g_btn('Healthcare Professional Enquiry', request_url('professional'), 'white') ?>
      </div>
    </div>
    <div class="g-hero__media g-onc-hero__media">
      <picture>
        <source type="image/webp" srcset="<?= e(asset('/assets/img/oncology-ribbon-hero-cutout-800.webp')) ?> 800w, <?= e(asset('/assets/img/oncology-ribbon-hero-cutout-1400.webp')) ?> 1400w" sizes="(min-width: 64em) 560px, 480px">
        <img src="<?= e(asset('/assets/img/oncology-ribbon-hero-cutout.png')) ?>" width="1436" height="991" alt="The islands of Vanuatu in blue beside a blue cancer awareness ribbon." decoding="async" fetchpriority="high">
      </picture>
    </div>
  </div>
</section>

<?php
$ribbons = [
    'breast' => 'Breast', 'lung' => 'Lung', 'prostate' => 'Prostate', 'colorectal' => 'Colorectal',
    'leukemia' => 'Leukemia', 'lymphoma' => 'Lymphoma', 'multiple-myeloma' => 'Multiple myeloma',
    'ovarian' => 'Ovarian', 'liver' => 'Liver', 'pancreatic' => 'Pancreatic', 'brain' => 'Brain',
    'childhood' => 'Childhood', 'stomach' => 'Stomach', 'cervical' => 'Cervical',
    'head-neck' => 'Head & Neck', 'skin' => 'Skin', 'testicular' => 'Testicular',
    'uterine' => 'Uterine', 'melanoma' => 'Melanoma', 'thyroid' => 'Thyroid',
];
?>
<section class="g-onc-ribbons" aria-label="Cancer types">
  <div class="g-onc-ribbons__track">
    <?php foreach ([false, true] as $copy): ?>
      <ul class="g-onc-ribbons__list"<?= $copy ? ' aria-hidden="true"' : '' ?>>
        <?php foreach ($ribbons as $file => $name):
          [$w, $h] = getimagesize(APP_ROOT . "/assets/img/ribbons/$file.png"); ?>
          <li>
            <picture>
              <source srcset="<?= e(asset("/assets/img/ribbons/$file.webp")) ?>" type="image/webp">
              <img src="<?= e(asset("/assets/img/ribbons/$file.png")) ?>" width="<?= $w ?>" height="<?= $h ?>" alt="<?= $copy ? '' : e($name) ?>" loading="lazy" decoding="async">
            </picture>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endforeach; ?>
  </div>
</section>

<div class="g-onc">
  <section class="g-sec g-bg-white" id="local-access" aria-labelledby="local-h">
    <div class="g-wrap">
      <div class="g-split g-split--7-5">
        <div>
          <span class="g-label">Closer to Home</span>
          <h2 id="local-h">Why local access matters</h2>
          <div class="g-stack g-mt">
            <p>Cancer treatment is often given in cycles, and each cycle needs the right medicine at the right time for the treatment to work as planned. In the past, many patients in Vanuatu travelled overseas for treatment, only to find that the medicine for their next cycle was not available when they came home. Some had to wait for a traveller to bring it, and some missed cycles altogether, which can make treatment less effective. Having cancer medicines here in Vanuatu means patients can continue their care closer to family and stay on the schedule their doctor planned.</p>
          </div>
        </div>
        <div class="g-onc-cycle">
          <svg viewBox="0 0 360 380" role="img" aria-label="A treatment cycle: Cycle 1, Cycle 2, Cycle 3 and Cycle 4 in a circle. Cycle 3 is greyed out and labelled missed cycle.">
            <defs>
              <marker id="ah-g" viewBox="0 0 10 10" refX="7" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse"><path d="M0 0 10 5 0 10z" fill="#1D9FDA"/></marker>
              <marker id="ah-x" viewBox="0 0 10 10" refX="7" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse"><path d="M0 0 10 5 0 10z" fill="#9AA7B3"/></marker>
            </defs>
            <g class="g-cyc__part g-cyc__arrow" style="--d:.6s"><path d="M217.1 65.9 A120 120 0 0 1 294.1 142.9" fill="none" stroke="#1D9FDA" stroke-width="3" marker-end="url(#ah-g)"/></g>
            <g class="g-cyc__part g-cyc__arrow" style="--d:1.4s"><path d="M294.1 217.1 A120 120 0 0 1 217.1 294.1" fill="none" stroke="#9AA7B3" stroke-width="3" stroke-dasharray="5 6" marker-end="url(#ah-x)"/></g>
            <g class="g-cyc__part g-cyc__arrow" style="--d:2.6s"><path d="M142.9 294.1 A120 120 0 0 1 65.9 217.1" fill="none" stroke="#9AA7B3" stroke-width="3" stroke-dasharray="5 6" marker-end="url(#ah-x)"/></g>
            <g class="g-cyc__part g-cyc__arrow" style="--d:3.4s"><path d="M65.9 142.9 A120 120 0 0 1 142.9 65.9" fill="none" stroke="#1D9FDA" stroke-width="3" marker-end="url(#ah-g)"/></g>
            <g class="g-cyc__part g-cyc__centre" style="--d:0s">
              <text x="180" y="174" text-anchor="middle" font-family="Poppins, sans-serif" font-size="17" font-weight="600" fill="#0B2A5B">Treatment</text>
              <text x="180" y="196" text-anchor="middle" font-family="Poppins, sans-serif" font-size="17" font-weight="600" fill="#0B2A5B">cycle</text>
            </g>
            <?php foreach ([[1, 180, 60, '.2s'], [2, 300, 180, '1s'], [4, 60, 180, '3s']] as [$n, $x, $y, $d]): ?>
            <g class="g-cyc__part g-cyc__node" style="--d:<?= $d ?>">
              <circle cx="<?= $x ?>" cy="<?= $y ?>" r="36" fill="#1D9FDA"/>
              <text x="<?= $x ?>" y="<?= $y - 4 ?>" text-anchor="middle" font-family="Poppins, sans-serif" font-size="12" font-weight="600" fill="#fff">Cycle</text>
              <text x="<?= $x ?>" y="<?= $y + 17 ?>" text-anchor="middle" font-family="Poppins, sans-serif" font-size="22" font-weight="700" fill="#fff"><?= $n ?></text>
            </g>
            <?php endforeach; ?>
            <g class="g-cyc__part g-cyc__node g-cyc__node--missed" style="--d:1.8s">
              <circle cx="180" cy="300" r="36" fill="#EEF1F4" stroke="#9AA7B3" stroke-width="2" stroke-dasharray="5 5"/>
              <text x="180" y="296" text-anchor="middle" font-family="Poppins, sans-serif" font-size="12" font-weight="600" fill="#5A6B7B">Cycle</text>
              <text x="180" y="317" text-anchor="middle" font-family="Poppins, sans-serif" font-size="22" font-weight="700" fill="#5A6B7B">3</text>
            </g>
            <g class="g-cyc__part g-cyc__badge" style="--d:2.2s">
              <rect x="136" y="346" width="88" height="22" rx="11" fill="#D93A3F"/>
              <text x="180" y="361" text-anchor="middle" font-family="Poppins, sans-serif" font-size="11" font-weight="600" fill="#fff">Missed cycle</text>
            </g>
          </svg>
        </div>
      </div>
    </div>
  </section>

  <?php /* Same layout as Named Patient Supply's "Step by Step / How it works":
           white heading row (eyebrow and big black title left, subtext right),
           then the six types on the blue-to-green band in three columns, each
           with its icon in the glassy circle where the step number would be
           (guide.css "NPS how band"). */ ?>
  <section class="g-sec g-bg-white g-nps-how-head" id="types" aria-labelledby="types-h">
    <div class="g-wrap g-split g-split--top g-nps-helps">
      <div class="g-nps-col">
        <span class="g-label">Our Range</span>
        <h2 id="types-h">Types of cancer medicine we handle</h2>
      </div>
      <p class="g-nps-helps__text">We supply cancer medicines from first-line treatments through to more advanced medicines, always against your doctor's prescription or treatment protocol. Some we keep in stock in Port Vila; others we import or source for each patient. If your medicine is not listed here, ask us anyway. They include:</p>
    </div>
  </section>
  <section class="g-sec g-nps-how g-onc-types" aria-label="The types of cancer medicine">
    <div class="g-wrap">
      <ul class="g-steps g-steps--rows3" role="list">
        <?php foreach ([
            ['flask', 'Chemotherapy', 'Given as an injection or drip in hospital, or as tablets at home'],
            ['pill', 'Hormone therapy', 'Medicines used for cancers that respond to hormones'],
            ['scan', 'Targeted and biological medicines', 'Newer medicines aimed at specific features of a cancer'],
            ['drop', 'Medicines for blood cancers', 'Used in cancers such as leukaemia, lymphoma and myeloma'],
            ['heart', 'Supportive care medicines', 'Medicines used alongside treatment, such as anti-sickness medicine'],
            ['box', 'Medical consumables', 'Single-use medical consumables used during cancer treatment'],
        ] as [$icon, $title, $text]): ?>
        <li class="g-step">
          <span class="g-step__num" aria-hidden="true"><?= gi($icon) ?></span>
          <span class="g-step__title"><?= e($title) ?></span>
          <p class="g-step__text"><?= e($text) ?></p>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <?php /* Injectable chemotherapy and Planning each treatment cycle side by
           side, in the style of Named Patient Supply's "Before You Start /
           Our Promise" columns: blue eyebrow, black title, plain blue bullets
           (guide.css "NPS meaning columns"). */ ?>
  <section class="g-sec g-bg-white g-onc-treat" aria-label="Injectable chemotherapy and planning each treatment cycle">
    <div class="g-wrap g-nps-cols">
      <div class="g-nps-col" id="injectable" aria-labelledby="inj-h">
        <span class="g-label">Hospital Treatment</span>
        <h2 id="inj-h">Injectable chemotherapy</h2>
        <p>Some chemotherapy is given by injection or drip. These medicines must be given by trained staff in a hospital.</p>
        <p>If your medicine is an injectable, we prepare the supply for your session. Your chemotherapy session takes place at Vila Central Hospital or Vanuatu Private Hospital, as arranged by your treating team.</p>
      </div>
      <div class="g-nps-col" id="cycles" aria-labelledby="cyc-h">
        <span class="g-label">Your Schedule</span>
        <h2 id="cyc-h">Planning each treatment cycle</h2>
        <p>We do not just supply one order. We help you plan ahead.</p>
        <ul class="g-nps-dots" role="list">
          <li>We give you a pick-up date for each cycle.</li>
          <li>A pharmacist explains how to handle your medicine.</li>
          <li>We plan your next cycle with you, so your medicine is ready on time.</li>
          <li>If your doctor changes your treatment, tell us and we will update your supply.</li>
        </ul>
      </div>
    </div>
  </section>

  <?php /* For patients and families: the home page's patients picture (its
           words are in the picture, and in the alt text), full width, with
           How we supply cancer medicines straight under it. Links to the
           patient enquiry. The Important note stays above it. The picture is
           the original lossless assets/img/patients.png, with a high-quality
           WebP copy for browsers that take it. */ ?>
  <section class="g-sec g-bg-white g-onc-flush g-onc-patimg" id="patients" aria-labelledby="pat-h">
    <div class="g-wrap">
      <h2 id="pat-h" class="g-sr">For patients and families</h2>
      <div id="important"><?php g_box('safety', '<strong>Important:</strong> Getmeds Vanuatu supplies medicines. We do not diagnose cancer or choose treatment. Always follow the advice of your doctor or oncologist. Do not change a medicine, dose or schedule without speaking to them first.'); ?></div>
    </div>
    <a class="g-onc-patimg__link" href="<?= e(request_url('patient')) ?>">
      <picture>
        <source type="image/webp" srcset="<?= e(asset('/assets/img/patients-banner.webp')) ?>">
        <img src="<?= e(asset('/assets/img/patients.png')) ?>" width="1200" height="630" loading="lazy" decoding="async" alt="For patients and families: you do not need to know medical words to ask us for help. Send us your prescription and tell us how to reach you. A pharmacist will explain the next steps. Make a patient enquiry.">
      </picture>
    </a>
  </section>

  <?php /* Eyebrow, title and the pharmacist line on the left; the four supply
           routes stacked in a blue panel on the right (guide.css "g-onc-supply"). */ ?>
  <section class="g-sec g-bg-white g-onc-supply" id="supply" aria-labelledby="supply-h">
    <div class="g-wrap g-onc-supply__split">
      <div class="g-onc-supply__intro">
        <span class="g-label">Supply Routes</span>
        <h2 id="supply-h">How we supply cancer medicines</h2>
        <p>Our pharmacist will tell you which route applies to your medicine and how long it may take.</p>
      </div>
      <div class="g-onc-supply__panel">
        <?= g_card('shelf', 'In stock', 'The medicine is kept at our pharmacy in Port Vila.') ?>
        <?= g_card('plane', 'For import', 'We order the medicine for you from our suppliers.') ?>
        <?= g_card('search', 'Sourced on request', 'We search for a medicine that is hard to find or often out of stock.') ?>
        <?= g_card('user-tag', 'Named Patient Supply', 'We help arrange a medicine that is not normally available in Vanuatu, for one named patient.') ?>
      </div>
    </div>
  </section>
</div>

<?php /* Pacific banner, joined straight onto the CTA banner below: same layout
         as the How It Works hospitals banner, with the Vanuatu aerial photo
         (img/vanuatugeo.jpg) behind a navy overlay (guide.css "g-onc-pac"). */ ?>
<section class="g-sec g-home-cta g-onc-pac" id="pacific" aria-labelledby="pac-h">
  <div class="g-wrap g-home-cta__inner">
    <div class="g-home-cta__text">
      <h2 id="pac-h">Cancer medicine access across the Pacific</h2>
      <p>Many Pacific Islands have the same gaps in cancer medicine supply. Getmeds is working to extend access from Vanuatu to other Pacific countries. Contact us to ask what is possible for your country.</p>
    </div>
  </div>
</section>

<?php /* Same CTA banner as the About page (guide.css "g-home-cta"), with this page's text. */ ?>
<section class="g-sec g-home-cta" aria-label="Ask about a cancer medicine">
  <div class="g-wrap g-home-cta__inner">
    <div class="g-home-cta__text">
      <h2>Ask about a cancer medicine</h2>
      <p>Send the prescription and we will check what we can supply.</p>
    </div>
    <div class="g-home-cta__btns">
      <a class="g-home-cta__btn g-home-cta__btn--white" href="<?= e(request_url('cancer')) ?>">Request a Cancer Medicine</a>
      <a class="g-home-cta__btn g-home-cta__btn--dark" href="<?= e(tel_url()) ?>"><?= gi('phone') ?><span>Call <?= e(cfg('phone')) ?></span></a>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
