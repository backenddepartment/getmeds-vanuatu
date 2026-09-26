<?php
/**
 * Home (content guide, page 01).
 *
 * Section order as the guide sets it: hero, trust strip, intro, why, oncology,
 * Named Patient Supply, categories, Pacific, affordability, how it works, who
 * we serve, professionals/patients split, safety, final CTA, footer.
 * The hero keeps the owner's H1, lede and kicker (BUILD-BRIEF, owner decision 2).
 */
require __DIR__ . '/includes/bootstrap.php';

$page = [
    'title'     => 'Getmeds Vanuatu',
    'seo_title' => 'Getmeds Vanuatu — Cancer Medicines and Chemotherapy Pharmacy, Port Vila',
    'desc'      => 'Getmeds Vanuatu helps patients and doctors get cancer medicines and other important medicines in Vanuatu and across the Pacific. Send a prescription to start.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<?php /* Full-screen photo hero. The navbar sits over it, see-through, on this page only
         (includes/header.php, guide.css "Home hero"). */ ?>
<section class="g-homehero" aria-labelledby="hero-title">
  <div class="g-homehero__media">
    <img src="<?= e(asset('/assets/img/homeherosection-1200.jpg')) ?>"
         srcset="<?= e(asset('/assets/img/homeherosection-800.jpg')) ?> 800w, <?= e(asset('/assets/img/homeherosection-1200.jpg')) ?> 1200w, <?= e(asset('/assets/img/homeherosection.png')) ?> 1600w"
         sizes="100vw" width="1200" height="628" alt="" loading="eager" fetchpriority="high" decoding="async">
  </div>
  <div class="g-wrap g-homehero__inner">
    <p class="g-homehero__kicker">Licensed pharmacy · <?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></p>
    <h1 class="g-homehero__title" id="hero-title">Affordable Medicines. Better Access. Stronger Cancer Care.</h1>
    <p class="g-homehero__lede">Getmeds Vanuatu-Pacific helps patients and healthcare providers access essential and cancer medicines through reliable sourcing, more affordable pricing, and a growing supply network across the Pacific.</p>
    <div class="g-homehero__btns">
      <a class="g-homehero__btn g-homehero__btn--fill" href="<?= e(url('/order')) ?>">Order a Medicine</a>
      <a class="g-homehero__btn g-homehero__btn--line" href="<?= e(tel_url()) ?>">Call <?= e(cfg('phone')) ?></a>
    </div>
  </div>
</section>

<section class="g-statbar" aria-label="Getmeds Vanuatu at a glance">
  <ul class="g-wrap g-statbar__list">
    <li><span class="g-statbar__num">1st</span><span class="g-statbar__cap">Chemotherapy pharmacy<br>in the Pacific</span></li>
    <li><span class="g-statbar__num">Named</span><span class="g-statbar__cap">Patient access for<br>hard-to-find medicines</span></li>
    <li><span class="g-statbar__num"><?= e(cfg('medicines_count')) ?></span><span class="g-statbar__cap">Affordable medicines<br>available to order</span></li>
    <li><span class="g-statbar__num">83</span><span class="g-statbar__cap">Islands of Vanuatu<br>we work to reach</span></li>
  </ul>
</section>

<section class="g-sec g-bg-white g-intro-sec" aria-labelledby="intro-h">
  <div class="g-wrap g-home-intro">
    <div class="g-home-intro__head">
      <span class="g-label">About Getmeds Vanuatu</span>
      <h2 id="intro-h">A pharmacy built for cancer care in the Pacific</h2>
    </div>
    <div class="g-home-intro__body">
      <div class="g-stack">
        <p>For many people in Vanuatu, getting cancer medicine has meant travelling overseas or waiting for someone to bring it back. Getmeds Vanuatu was set up to change that.</p>
        <p>We are the first chemotherapy pharmacy in the Pacific. We keep cancer medicines in Port Vila, import them when needed, and source harder-to-find medicines for individual patients. We also supply medicines for diabetes, high blood pressure, heart disease and kidney care.</p>
      </div>
      <div class="g-home-intro__actions">
        <p class="g-home-intro__stat">83 islands. One pharmacy working to reach them.</p>
        <a class="g-home-intro__btn" href="<?= e(url('/about')) ?>">More about us</a>
      </div>
    </div>
  </div>
  <div class="g-wrap">
    <figure class="g-home-intro__photo">
      <img src="<?= e(asset('/assets/img/home-about-1600.jpg')) ?>"
           srcset="<?= e(asset('/assets/img/home-about-800.jpg')) ?> 800w, <?= e(asset('/assets/img/home-about-1600.jpg')) ?> 1600w"
           sizes="(max-width: 1248px) 100vw, 1200px" width="1600" height="905" loading="lazy" decoding="async"
           alt="Getmeds Vanuatu staff around a meeting table during a training session, with a presentation on the screen.">
    </figure>
  </div>
</section>

<section class="g-sec g-bg-white g-why-sec" aria-labelledby="why-h">
  <div class="g-wrap g-why2">
    <div class="g-why2__head">
      <span class="g-label">What sets us apart</span>
      <h2 id="why-h">Why Getmeds Vanuatu</h2>
    </div>
    <div class="g-why2__body">
      <ul class="g-why2__list">
        <li><?= gi('arrow') ?><div><h3>Available</h3><p>Many cancer medicines are kept in stock in Port Vila. Others can be imported or sourced for you.</p></div></li>
        <li><?= gi('arrow') ?><div><h3>Checked</h3><p>A pharmacist checks every prescription before we supply anything.</p></div></li>
        <li><?= gi('arrow') ?><div><h3>More affordable</h3><p>We work to make medicines more affordable to access, so fewer patients have to travel overseas for them.</p></div></li>
        <li><?= gi('arrow') ?><div><h3>Ongoing</h3><p>We plan your next treatment cycle with you, so your medicine is ready when you need it.</p></div></li>
      </ul>
      <p class="g-why2__note">We are the first chemotherapy pharmacy in the Pacific, and we work to reach patients across all 83 islands of Vanuatu.</p>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="onc-h">
  <div class="g-wrap g-split">
    <div>
      <span class="g-label g-label--pink">Oncology and chemotherapy</span>
      <h2 id="onc-h">Cancer and chemotherapy medicines</h2>
      <div class="g-stack g-mt">
        <p>Cancer treatment often needs medicine in cycles, over many months. If one cycle is missed or late, treatment can be interrupted.</p>
        <p>Getmeds Vanuatu supplies cancer medicines against a doctor's prescription or treatment protocol. We supply medicines in three ways:</p>
      </div>
      <ul class="g-routes g-mt" role="list">
        <li><span class="g-chip g-chip--pink"><?= gi('shelf') ?>In stock</span><span>ready in Port Vila</span></li>
        <li><span class="g-chip g-chip--pink"><?= gi('plane') ?>For import</span><span>ordered for you from our suppliers</span></li>
        <li><span class="g-chip g-chip--pink"><?= gi('search') ?>Sourced on request</span><span>found for you when a medicine is hard to get</span></li>
      </ul>
      <p class="g-mt">Injectable chemotherapy is given at a hospital. We work with your treating team so the medicine is ready for your session.</p>
      <div class="g-btns g-mt"><?= g_btn('Learn about cancer medicines', url('/oncology'), 'primary') ?></div>
    </div>
    <div class="g-media">
      <?= g_photo('carton', 'Gloved hands taking a blister pack of tablets out of its medicine box.') ?>
    </div>
  </div>
</section>

<section class="g-sec g-bg-sky" aria-labelledby="nps-h">
  <div class="g-wrap g-split">
    <div class="g-diagram" role="img" aria-label="Named Patient Supply in three steps: the doctor's prescription, Getmeds sources and imports, the medicine for one named patient.">
      <div class="g-diagram__step"><?= gi('clipboard') ?><strong>Doctor's prescription</strong></div>
      <span class="g-diagram__arrow"><?= gi('arrow') ?></span>
      <div class="g-diagram__step"><?= gi('plane') ?><strong>Getmeds sources and imports</strong></div>
      <span class="g-diagram__arrow"><?= gi('arrow') ?></span>
      <div class="g-diagram__step"><?= gi('user-tag') ?><strong>Medicine for one named patient</strong></div>
    </div>
    <div>
      <h2 id="nps-h">Named Patient Supply</h2>
      <div class="g-stack g-mt">
        <p>Sometimes a doctor prescribes a medicine that is not normally available in Vanuatu. Named Patient Supply is a way to get that medicine for one named patient, based on their prescription.</p>
        <p>Getmeds helps the doctor and patient with the request, the paperwork and the import. Not every medicine can be supplied this way, and we will tell you clearly what is possible.</p>
      </div>
      <p class="g-mt"><a class="g-link-arrow" href="<?= e(url('/named-patient-supply')) ?>">How Named Patient Supply works <?= gi('arrow') ?></a></p>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="cat-h">
  <div class="g-wrap">
    <div class="g-head g-head--center"><h2 id="cat-h">What we supply</h2></div>
    <?php
    $tiles = [
        ['oncology', 'ribbon', 'Cancer medicines', 'Chemotherapy, hormone therapy and other cancer medicines'],
        ['haematology', 'drop', 'Blood disorders', 'Medicines for blood cancers and blood conditions'],
        ['supportive-care', 'heart', 'Supportive care', 'Medicines used alongside cancer treatment, such as anti-sickness medicine'],
        ['diabetes', 'gauge', 'Diabetes', 'Medicines and supplies for people living with diabetes'],
        ['cardiology', 'pulse', 'Heart and blood pressure', 'Medicines for high blood pressure, cholesterol and heart disease'],
        ['renal', 'kidney', 'Kidney and dialysis', 'Medicines used in kidney care and dialysis'],
        ['antibiotics', 'bug', 'Antibiotics', 'Medicines used to treat bacterial infections'],
        ['medical-supplies', 'box', 'Medical supplies', 'Single-use consumables, devices and equipment'],
    ];
    ?>
    <div class="g-grid g-grid--4 g-grid--m2 g-tiles">
      <?php foreach ($tiles as $i => [$anchor, $ic, $name, $line]): ?>
      <a class="g-card g-card--link g-card--compact<?= $i === 0 ? ' g-card--pink-top' : '' ?>" href="<?= e(url('/medicines') . '#' . $anchor) ?>">
        <span class="g-card__icon"><?= gi($ic) ?></span>
        <h3><?= e($name) ?></h3>
        <p><?= e($line) ?></p>
        <span class="g-card__go" aria-hidden="true"><?= gi('arrow') ?></span>
      </a>
      <?php endforeach; ?>
    </div>
    <div class="g-btns g-btns--center g-mt-lg"><?= g_btn('See all medicine groups', url('/medicines'), 'secondary') ?></div>
  </div>
</section>

<section class="g-sec g-bg-navy g-wave g-home-pacific" aria-labelledby="pac-h">
  <div class="g-wrap g-split">
    <div>
      <h2 id="pac-h">Across the Pacific</h2>
      <div class="g-stack g-mt">
        <p>Access to important medicines should not depend on where you live. From Port Vila, Getmeds is working to make medicines easier to reach across the Pacific Islands.</p>
        <p>If you are a patient, doctor or hospital outside Vanuatu, contact us and we will tell you what we can do.</p>
      </div>
      <p class="g-mt"><a class="g-link-arrow" href="<?= e(url('/pacific-access')) ?>">Pacific access <?= gi('arrow') ?></a></p>
    </div>
    <div class="g-split__media--first g-home-map"><?= g_pacific_map('dark') ?></div>
  </div>
</section>

<section class="g-sec g-bg-mint" aria-labelledby="aff-h">
  <div class="g-wrap g-split">
    <div>
      <h2 id="aff-h">More affordable access</h2>
      <p class="g-quote g-mt">In Vanuatu, cancer and dialysis medicines are usually paid for by the patient. Travelling overseas for treatment can cost millions of vatu.</p>
    </div>
    <div>
      <p class="g-lead">Getmeds works to lower these barriers. We give you a clear price before you commit to anything.</p>
      <div class="g-btns g-mt"><?= g_btn('How we keep access affordable', url('/affordable-access'), 'secondary') ?></div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="how-h">
  <div class="g-wrap">
    <div class="g-head g-head--center"><h2 id="how-h">How it works</h2></div>
    <?php g_steps([
        ['Contact us.', 'Call, message or use the online form.'],
        ['Send the prescription.', "Share your doctor's prescription or treatment protocol."],
        ['A pharmacist checks it.', 'We confirm the medicine, availability and price.'],
        ['Confirm and plan.', 'You agree to the price. We give you a pick-up date and plan your next cycle.'],
        ['Collect your medicine.', 'Pick up on the agreed date. Injectables go to your hospital for your session.'],
    ], 'h'); ?>
    <div class="g-btns g-btns--center g-mt-lg"><?= g_btn('Request a Medicine', request_url('medicine'), 'primary') ?></div>
  </div>
</section>

<section class="g-sec g-bg-sky" aria-labelledby="serve-h">
  <div class="g-wrap">
    <div class="g-head g-head--center"><h2 id="serve-h">Who we serve</h2></div>
    <div class="g-grid g-grid--5 g-scrollrow g-serve">
      <?= g_card('users', 'Patients and families', 'Help getting the medicine your doctor prescribed', 'g-card--compact') ?>
      <?= g_card('doctor', 'Doctors and oncologists', 'A local source for cancer and specialist medicines', 'g-card--compact') ?>
      <?= g_card('hospital', 'Hospitals and clinics', 'Supply for wards, clinics and treatment units', 'g-card--compact') ?>
      <?= g_card('bottle', 'Pharmacies', 'Access to medicines you do not normally stock', 'g-card--compact') ?>
      <?= g_card('plane', 'Overseas treatment agencies', 'Medicine continuity for patients who return home', 'g-card--compact') ?>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="split-h">
  <div class="g-wrap">
    <h2 id="split-h" class="g-sr">Enquiries for healthcare professionals and for patients</h2>
    <div class="g-grid g-grid--2 g-home-split">
      <div class="g-card g-card--navy">
        <span class="g-card__icon"><?= gi('doctor') ?></span>
        <h3>For doctors, hospitals and pharmacies</h3>
        <p>Send us a prescription, a treatment protocol or a product list. We will reply with availability and a quotation. Let us quote for your requirement.</p>
        <div class="g-btns"><?= g_btn('Healthcare Professional Enquiry', request_url('professional'), 'primary') ?></div>
      </div>
      <div class="g-card g-card--pink-top g-home-split__pt">
        <span class="g-card__icon"><?= gi('heart') ?></span>
        <h3>For patients and families</h3>
        <p>You do not need to know medical words to ask us for help. Send us your prescription and tell us how to reach you. A pharmacist will explain the next steps.</p>
        <div class="g-btns"><?= g_btn('Patient Enquiry', request_url('patient'), 'primary') ?></div>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-sec--flushtop" aria-labelledby="safe-h">
  <div class="g-wrap">
    <div class="g-head g-head--center"><h2 id="safe-h">How we keep medicines safe</h2></div>
    <div class="g-grid g-grid--2">
      <?= g_card('clipboard', 'Prescription first.', 'Every prescription medicine needs a valid prescription.') ?>
      <?= g_card('pharmacist', 'Pharmacist-checked.', 'A pharmacist checks every order.') ?>
      <?= g_card('shield', 'Only what was prescribed.', 'We supply exactly what your doctor prescribed.') ?>
      <?= g_card('thermo', 'Stored correctly.', 'Medicines that need cold storage are kept between 2 and 8&nbsp;°C, monitored and logged.') ?>
    </div>
  </div>
</section>

<?php g_cta_band(
    'Need a medicine? Start with your prescription.',
    'Send it to us and a pharmacist will reply with what we can supply and the price.',
    [
        ['Request a Medicine', request_url('medicine'), 'primary'],
        ['Call ' . cfg('phone'), tel_url(), 'white', 'phone'],
    ]
); ?>

<?php include INC . '/footer.php'; ?>
