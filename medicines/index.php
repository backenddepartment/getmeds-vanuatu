<?php
/**
 * /medicines — Content & Design Guide, page 04.
 *
 * A reference page people scan: a short Sky Tint hero, a sticky bar of
 * category chips, the three-step availability strip, then one expandable card
 * per medicine group in the guide's order (anchors are linked from the menu and
 * other pages; guide.js opens the card whose id matches the URL hash). Groups,
 * not products: no product photos, no prices, no medicine names.
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

<section class="g-hero g-hero--sky g-hero--40 g-pg">
  <div class="g-wrap g-split g-split--7-5">
    <div>
      <h1>Medicines we supply</h1>
      <p class="g-hero__lede">Getmeds Vanuatu supplies medicines across several medical areas. Cancer medicines are our main focus. We also supply essential medicines and medical supplies.</p>
      <p class="g-pg-mt">We list medicine groups, not every product. Stock changes, and we do not want to promise a medicine we cannot supply. Send us the prescription and a pharmacist will check it for you.</p>
      <div class="g-mt">
        <?php g_box('safety', '<strong>Prescription medicines need a valid prescription. Availability may vary.</strong>'); ?>
      </div>
    </div>
    <div class="g-hero__media g-med-illus">
      <svg viewBox="0 0 360 260" role="img" aria-label="Illustration of medicine boxes on a pharmacy shelf">
        <rect x="10" y="10" width="340" height="240" rx="20" fill="#fff"/>
        <!-- shelves -->
        <rect x="30" y="112" width="300" height="8" rx="4" fill="#0B2A5B"/>
        <rect x="30" y="212" width="300" height="8" rx="4" fill="#0B2A5B"/>
        <!-- top shelf boxes -->
        <rect x="46" y="52" width="58" height="60" rx="6" fill="#1E9BD7"/>
        <rect x="46" y="70" width="58" height="14" fill="#fff" opacity=".85"/>
        <rect x="112" y="36" width="44" height="76" rx="6" fill="#2FB5A6"/>
        <path d="M134 56v16M126 64h16" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
        <rect x="164" y="62" width="70" height="50" rx="6" fill="#E8508A"/>
        <rect x="164" y="80" width="70" height="12" fill="#fff" opacity=".85"/>
        <rect x="242" y="46" width="72" height="66" rx="6" fill="#EEF7F2" stroke="#6BB33F" stroke-width="3"/>
        <path d="M278 66v26M265 79h26" stroke="#6BB33F" stroke-width="6" stroke-linecap="round"/>
        <!-- bottom shelf boxes -->
        <rect x="46" y="150" width="80" height="62" rx="6" fill="#F2F8FC" stroke="#1E9BD7" stroke-width="3"/>
        <rect x="58" y="170" width="56" height="10" rx="3" fill="#1E9BD7"/>
        <rect x="58" y="186" width="36" height="8" rx="3" fill="#1E9BD7" opacity=".5"/>
        <rect x="134" y="134" width="50" height="78" rx="6" fill="#0B2A5B"/>
        <path d="M159 158v18M150 167h18" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
        <rect x="192" y="160" width="60" height="52" rx="6" fill="#6BB33F"/>
        <rect x="192" y="176" width="60" height="12" fill="#fff" opacity=".85"/>
        <rect x="260" y="142" width="54" height="70" rx="6" fill="#1E9BD7" opacity=".85"/>
        <rect x="270" y="160" width="34" height="8" rx="3" fill="#fff"/>
        <rect x="270" y="174" width="24" height="8" rx="3" fill="#fff" opacity=".7"/>
      </svg>
    </div>
  </div>
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

  <section class="g-sec g-sec--tight g-bg-white" aria-labelledby="check-h">
    <div class="g-wrap">
      <div class="g-head g-head--center">
        <h2 id="check-h">How to check if we can supply your medicine</h2>
      </div>
      <div class="g-steps-sm">
        <?php g_steps([
            ['', 'Send us the medicine name and the prescription.'],
            ['', 'Our pharmacist checks availability, supply route and price.'],
            ['', 'We reply with the next steps.'],
        ], 'h'); ?>
      </div>
      <div class="g-btns g-btns--center g-mt-lg">
        <?= g_btn('Request a Medicine', request_url('medicine')) ?>
      </div>
    </div>
  </section>

  <section class="g-sec g-bg-mint" id="medicine-groups" aria-labelledby="groups-h">
    <div class="g-wrap g-narrow">
      <div class="g-head">
        <h2 id="groups-h">Medicine groups</h2>
      </div>
      <div class="g-acc g-acc--cards g-medcards">
        <?php foreach ($groups as $i => [$id, $icon, $name, $what, $provides, $who, $ctaLabel, $ctaHref]): ?>
        <details class="g-acc__item <?= $i === 0 ? 'g-top-pink' : 'g-top-blue' ?>" id="<?= e($id) ?>" data-mobile-closed>
          <summary>
            <span class="g-acc__title">
              <span class="g-medcard__icon<?= $i === 0 ? ' g-medcard__icon--pink' : '' ?>"><?= gi($icon) ?></span>
              <span>
                <h3 class="g-medcard__name"><?= e($name) ?></h3>
                <span class="g-acc__sum"><?= e($what) ?></span>
              </span>
            </span>
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

<section class="g-sec g-bg-white" id="medical-supplies" aria-labelledby="supplies-h">
  <div class="g-wrap g-split">
    <div class="g-stack">
      <h2 id="supplies-h">Medical supplies, devices and equipment</h2>
      <p>We supply single-use medical consumables, medical devices, medical equipment and laboratory supplies. This includes supplies used in cancer treatment, and devices for asthma, diabetes and blood pressure care.</p>
      <div class="g-btns g-mt">
        <?= g_btn('Request a Quotation', request_url('quotation'), 'secondary') ?>
      </div>
    </div>
    <div class="g-media g-med-photo">
      <?= g_photo('dispenser', 'A hand drawing single-use supplies from labelled pharmacy storage bins marked alcohol pads, ointments and tape.') ?>
    </div>
  </div>
</section>

<?php g_cta_band(
    'Can\'t see your medicine?',
    'We can source many products that are not listed here. Some items may take longer or cost more because of freight or import paperwork. We will tell you before you commit.',
    [
        ['Request a Medicine', request_url('medicine'), 'primary'],
        ['Talk to Our Team', tel_url(), 'white', 'phone'],
    ]
); ?>

<?php include INC . '/footer.php'; ?>
