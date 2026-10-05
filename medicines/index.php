<?php
/**
 * /medicines — Content & Design Guide, page 04.
 *
 * A reference page people scan: a short Sky Tint hero, a sticky bar of
 * category chips, one expandable card per medicine group in the guide's order
 * (anchors are linked from the menu and other pages; guide.js opens the card
 * whose id matches the URL hash), then the three-step availability strip just
 * above the medical supplies band. Groups, not products: no product photos,
 * no prices, no medicine names.
 *
 * data/medicines.php stays: the legacy search page still reads it.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Medicines we supply',
    'seo_title' => 'Medicines We Supply — Getmeds Vanuatu',
    'desc'      => 'Cancer medicines, essential medicines and medical supplies from Getmeds Vanuatu in Port Vila. Prescription required. Contact us to check availability.',
];

$access = 'Send us the medicine name and prescription. Our pharmacist will check availability and reply with the next steps. Availability may vary.';

// [anchor, icon, name, what it is, what Getmeds provides, who may need this, CTA label, CTA href]
$groups = [
    ['oncology', 'ribbon', 'Oncology (cancer medicines)',
        'Medicines used to treat cancer, including chemotherapy.',
        'Cancer medicines in stock, for import, or sourced on request. This is our main specialisation.',
        'These medicines may be used as part of treatment for people with cancer, as prescribed by their doctor or oncologist.',
        'Enquire About Cancer Medicines', request_url('cancer')],
    ['haematology', 'drop', 'Haematology (blood disorders and blood cancers)',
        'Medicines used for diseases of the blood and bone marrow, including some blood cancers.',
        'Supply and sourcing of haematology medicines, alongside our cancer medicines.',
        'These medicines may be used as part of treatment for people with conditions such as leukaemia, lymphoma, myeloma or other blood disorders.',
        'Request Medicine Information', request_url('medicine')],
    ['anaemia', 'cells', 'Anaemia',
        'Medicines used when the body does not have enough healthy red blood cells.',
        'Supply and sourcing of anaemia medicines, including those used in kidney care and during cancer treatment.',
        'These medicines may be used as part of treatment for people with anaemia, including anaemia linked to kidney disease or chemotherapy.',
        'Request Medicine Information', request_url('medicine')],
    ['supportive-care', 'heart', 'Supportive care in cancer treatment',
        'Medicines that help with the side effects of cancer treatment, such as nausea, pain and mouth sores.',
        'Supportive medicines, including anti-sickness medicine in tablet and injection form.',
        'People receiving chemotherapy or other cancer treatment, as prescribed by their treating team.',
        'Request Medicine Information', request_url('medicine')],
    ['hormonal-therapy', 'scale', 'Hormonal therapy',
        'Medicines that change the level or effect of hormones in the body. Some are used in cancer treatment.',
        'Supply and sourcing of hormonal therapies, including long-course medicines that need steady supply.',
        'These medicines may be used as part of treatment for some breast and prostate cancers, and for thyroid and other hormone conditions.',
        'Request Medicine Information', request_url('medicine')],
    ['diabetes', 'gauge', 'Diabetes',
        'Medicines and supplies used to manage blood sugar.',
        'Diabetes medicines, and devices and supplies for diabetes care. Simple blood sugar checks at our pharmacy.',
        'These medicines may be used as part of treatment for people living with type 1 or type 2 diabetes.',
        'Enquire About Medicines', request_url('medicine')],
    ['cardiology', 'pulse', 'Cardiology (heart, blood pressure and cholesterol)',
        'Medicines for the heart and blood vessels.',
        'Medicines for high blood pressure, high cholesterol and heart disease. Blood pressure devices and supplies. Simple blood pressure checks at our pharmacy.',
        'These medicines may be used as part of treatment for people with high blood pressure, high cholesterol or heart disease.',
        'Enquire About Medicines', request_url('medicine')],
    ['renal', 'kidney', 'Renal (kidney care and dialysis)',
        'Medicines used in kidney care, including for people on dialysis.',
        'Dialysis medicines and other kidney-care medicines, sourced and supplied on prescription.',
        'These medicines may be used as part of treatment for people with kidney disease or on dialysis.',
        'Enquire About Medicines', request_url('medicine')],
    ['bone-health', 'bone', 'Bone health',
        'Medicines that help protect bone strength. Some are used for people with cancer that affects the bones.',
        'Supply and sourcing of bone health medicines.',
        'These medicines may be used as part of treatment for people with weak bones or bone problems linked to cancer.',
        'Request Medicine Information', request_url('medicine')],
    ['antibiotics', 'bug', 'Antibiotics',
        'Medicines that treat infections caused by bacteria.',
        'Antibiotics, including broad-spectrum hospital antibiotics.',
        'These medicines may be used to treat bacterial infections when prescribed by a doctor. Antibiotics do not treat colds or flu.',
        'Enquire About Medicines', request_url('medicine')],
    ['pain-management', 'pill', 'Pain management',
        'Medicines that help control pain, from everyday pain relief to stronger medicines.',
        'Supply and sourcing of pain medicines. Some pain medicines have extra prescription rules, which we follow.',
        'People with pain, including pain linked to cancer or surgery, as prescribed by their doctor.',
        'Request Medicine Information', request_url('medicine')],
    ['anti-inflammatory', 'joint', 'Anti-inflammatory',
        'Medicines that reduce swelling and inflammation.',
        'Supply and sourcing of anti-inflammatory medicines.',
        'These medicines may be used as part of treatment for conditions such as arthritis and gout.',
        'Request Medicine Information', request_url('medicine')],
    ['allergy-respiratory', 'lungs', 'Allergy and respiratory',
        'Medicines and devices used to manage allergies and breathing conditions such as asthma.',
        'Allergy medicines, and devices and supplies for asthma care.',
        'People with allergies or asthma, as advised by their doctor or pharmacist.',
        'Request Medicine Information', request_url('medicine')],
    ['radiology-contrast-media', 'scan', 'Radiology and contrast media',
        'Products used by hospitals and clinics during some scans and X-rays to help pictures show more clearly.',
        'Sourcing of contrast media for hospitals, clinics and imaging services.',
        'Hospitals, clinics and imaging services. These products are used by trained staff only.',
        'Healthcare Professional Enquiry', request_url('professional')],
    ['everyday-medicines', 'home', 'Everyday and over-the-counter medicines',
        'Common medicines for the home, including some that do not need a prescription.',
        'Essential and over-the-counter medicines, including medicines for infants and children.',
        'Families and individuals. Ask our pharmacist if you are not sure a medicine is right for you.',
        'Talk to Our Team', tel_url()],
];

// Sticky filter chips: label => anchor.
$chips = [
    'Cancer'         => 'oncology',
    'Blood'          => 'haematology',
    'Supportive care'=> 'supportive-care',
    'Diabetes'       => 'diabetes',
    'Heart'          => 'cardiology',
    'Kidney'         => 'renal',
    'Antibiotics'    => 'antibiotics',
    'More'           => 'medicine-groups',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-bg-white g-hero--40 g-pg">
  <div class="g-wrap">
    <div class="g-med-hero__text">
      <h1>Medicines we supply</h1>
      <p class="g-hero__lede">Getmeds Vanuatu supplies medicines across several medical areas. Cancer medicines are our main focus. We also supply essential medicines and medical supplies. We list medicine groups, not every product. Stock changes, and we do not want to promise a medicine we cannot supply. Send us the prescription and a pharmacist will check it for you. Prescription medicines need a valid prescription. Availability may vary.</p>
    </div>
  </div>
  <?php /* assets/img/medicinebg, edge to edge under the text. */ ?>
  <picture class="g-med-hero__img">
    <source type="image/webp" srcset="<?= e(asset('/assets/img/medicinebg-1024.webp')) ?> 1024w, <?= e(asset('/assets/img/medicinebg-full.webp')) ?> 1774w" sizes="100vw">
    <img src="<?= e(asset('/assets/img/medicinebg-full.jpg')) ?>" srcset="<?= e(asset('/assets/img/medicinebg-1024.jpg')) ?> 1024w, <?= e(asset('/assets/img/medicinebg-full.jpg')) ?> 1774w" sizes="100vw"
         width="1774" height="387" alt="" decoding="async">
  </picture>
</section>

<div class="g-medpage">
  <nav class="g-filterbar" aria-label="Medicine groups">
    <div class="g-wrap">
      <ul class="g-chips" role="list">
        <?php foreach ($chips as $label => $anchor): ?>
        <li><a class="g-chip" href="#<?= e($anchor) ?>"><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </nav>

  <section class="g-sec g-bg-white" id="medicine-groups" aria-labelledby="groups-h">
    <div class="g-wrap">
      <div class="g-head g-groups-head">
        <div>
          <span class="g-label">What We Supply</span>
          <h2 id="groups-h">Medicine groups</h2>
        </div>
        <p class="g-groups-head__sub">We supply <?= count($groups) ?> groups of medicines, from cancer and blood disorders to diabetes, heart and kidney care, antibiotics and everyday medicines. Open a group to see what it covers, what we provide, who may need it and how to get it.</p>
      </div>
      <div class="g-acc g-acc--cards g-medcards">
        <?php foreach ($groups as $i => [$id, $icon, $name, $what, $provides, $who, $ctaLabel, $ctaHref]): ?>
        <details class="g-acc__item" id="<?= e($id) ?>" data-mobile-closed>
          <summary>
            <h3 class="g-medcard__name"><?= e($name) ?></h3>
          </summary>
          <div class="g-acc__body g-medcard__body">
            <dl class="g-medcard__dl">
              <div><dt>What it is</dt><dd><?= e($what) ?></dd></div>
              <div><dt>What Getmeds provides</dt><dd><?= e($provides) ?></dd></div>
              <div><dt>Who may need this</dt><dd><?= e($who) ?></dd></div>
              <div><dt>Access</dt><dd><?= e($access) ?></dd></div>
            </dl>
            <div class="g-btns">
              <?php if ($ctaHref === tel_url()): ?>
              <?= g_call_btn($ctaLabel, 'primary') ?>
              <?php else: ?>
              <?= g_btn($ctaLabel, $ctaHref) ?>
              <?php endif; ?>
            </div>
          </div>
        </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</div>

<?php /* Same blue-gradient band as the About page safety section: eyebrow,
         title and button left, the three steps right (guide.css "About safety
         band" + "Medicines check band"). */ ?>
<section class="g-sec g-about-safe g-medcheck" id="check" aria-labelledby="check-h">
  <div class="g-wrap g-split g-split--top">
    <div>
      <span class="g-label">Check Availability</span>
      <h2 id="check-h">How to check if we can supply your medicine</h2>
      <div class="g-btns g-medcheck__btns">
        <?= g_btn('Request a Medicine', request_url('medicine'), 'white') ?>
      </div>
    </div>
    <div class="g-medcheck__steps">
      <?php g_steps([
          ['', 'Send us the medicine name and the prescription.'],
          ['', 'Our pharmacist checks availability, supply route and price.'],
          ['', 'We reply with the next steps.'],
      ], 'v'); ?>
    </div>
  </div>
</section>

<?php /* Three columns: text left, the cross-shaped photo collage centred
         (img/cross-crop.png, cross.png with its white margins trimmed), and a
         "what we supply" list right; guide.css "Medical supplies band". */ ?>
<section class="g-sec g-bg-white g-supplies" id="medical-supplies" aria-labelledby="supplies-h">
  <div class="g-wrap g-supplies__grid">
    <div class="g-stack">
      <h2 id="supplies-h">Medical supplies, devices and equipment</h2>
      <p>We supply single-use medical consumables, medical devices, medical equipment and laboratory supplies. This includes supplies used in cancer treatment, and devices for asthma, diabetes and blood pressure care.</p>
      <div class="g-btns g-mt">
        <?= g_btn('Request a Quotation', request_url('quotation'), 'secondary') ?>
      </div>
    </div>
    <figure class="g-supplies__cross">
      <img src="<?= e(asset('/assets/img/cross-crop.png')) ?>" width="614" height="1038" alt="" loading="lazy" decoding="async">
    </figure>
    <div class="g-supplies__info">
      <h3>What we supply</h3>
      <ul class="g-supplies__list">
        <li>Single-use medical consumables</li>
        <li>Devices for asthma, diabetes and blood pressure</li>
        <li>Medical equipment</li>
        <li>Laboratory supplies</li>
      </ul>
      <h3 class="g-mt">Who we supply</h3>
      <p>Hospitals, clinics and patients across Vanuatu.</p>
    </div>
  </div>
</section>

<?php /* Same CTA banner as the home and About pages (guide.css "g-home-cta"). */ ?>
<section class="g-sec g-home-cta" aria-label="Can't see your medicine?">
  <div class="g-wrap g-home-cta__inner">
    <div class="g-home-cta__text">
      <h2>Can't see your medicine?</h2>
      <p>We can source many products that are not listed here. Some items may take longer or cost more because of freight or import paperwork. We will tell you before you commit.</p>
    </div>
    <div class="g-home-cta__btns">
      <a class="g-home-cta__btn g-home-cta__btn--white" href="<?= e(request_url('medicine')) ?>">Request a Medicine</a>
      <a class="g-home-cta__btn g-home-cta__btn--dark" href="<?= e(tel_url()) ?>"><?= gi('phone') ?><span>Talk to Our Team</span></a>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
