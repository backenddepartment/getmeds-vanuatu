<?php
/**
 * About Getmeds Vanuatu (content guide, page 02). A story page: narrow reading
 * column, calm tone, broken up by full-width photo and colour bands.
 *
 * The old /about/licences, /about/our-pharmacists, /about/who-we-are and
 * /about/part-of-getmeds pages redirect here.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'About Getmeds Vanuatu',
    'seo_title' => 'About Getmeds Vanuatu — Medicine Access for Vanuatu and the Pacific',
    'desc'      => 'Getmeds Vanuatu is a Port Vila pharmacy focused on cancer medicines. Learn why we started and how we help patients across the Pacific get the medicines they need.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<?php /* Full-screen photo hero, same size and style as the home page's hero
         (the navbar sits over it, see-through: includes/header.php, guide.css
         "Home hero"). */ ?>
<section class="g-homehero g-homehero--split" aria-labelledby="about-hero-title">
  <div class="g-homehero__media">
    <img src="<?= e(asset('/assets/img/aboutusbg.jpg')) ?>"
         width="1920" height="881" alt="" loading="eager" fetchpriority="high" decoding="async">
    <div class="g-homehero__scrim" aria-hidden="true"></div>
  </div>
  <?php /* Same bottom layout as the home hero: headline left, description and
           buttons right, then a rule and the kicker under it. */ ?>
  <div class="g-wrap g-homehero__inner">
    <div class="g-homehero__row">
      <h1 class="g-homehero__title" id="about-hero-title">Cancer Medicines, Here in Vanuatu.</h1>
      <div class="g-homehero__side">
        <p class="g-homehero__lede">Getmeds Vanuatu is a pharmacy in Port Vila. Our main work is making cancer medicines available in Vanuatu and the Pacific. We also supply other important medicines and medical supplies.</p>
        <div class="g-homehero__btns">
          <a class="g-homehero__btn g-homehero__btn--fill" href="<?= e(url('/order')) ?>">Order a Medicine</a>
          <a class="g-homehero__btn g-homehero__btn--line" href="<?= e(tel_url()) ?>">Call <?= e(cfg('phone')) ?></a>
        </div>
      </div>
    </div>
    <div class="g-homehero__foot">
      <p class="g-homehero__kicker">Our Story</p>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" id="about-getmeds" aria-labelledby="about-h">
  <div class="g-wrap">
    <?php /* Same layout as "Why we started" further down: eyebrow and title left, text right. */ ?>
    <div class="g-split g-why-start">
      <div class="g-why-start__intro">
        <span class="g-label">About Us</span>
        <h2 id="about-h">About Getmeds Vanuatu</h2>
        <picture class="g-about-avatars">
          <source type="image/webp" srcset="<?= e(asset('/assets/img/avatars-600.webp')) ?> 600w, <?= e(asset('/assets/img/avatars-1200.webp')) ?> 1200w" sizes="(max-width: 47.99em) 100vw, 560px">
          <img src="<?= e(asset('/assets/img/avatars-1200.png')) ?>" srcset="<?= e(asset('/assets/img/avatars-600.png')) ?> 600w, <?= e(asset('/assets/img/avatars-1200.png')) ?> 1200w" sizes="(max-width: 47.99em) 100vw, 560px"
               width="1200" height="228" loading="lazy" decoding="async"
               alt="Portraits of seven members of the Getmeds Vanuatu team.">
        </picture>
      </div>
      <div class="g-stack">
        <p class="g-about-intro">Getmeds Vanuatu is a pharmacy at Golden Port, Port Vila. We are the first chemotherapy pharmacy in the Pacific. Our main work is making cancer medicines available to patients in Vanuatu and across the Pacific Islands.</p>
        <p>We work with patients, families, doctors and hospitals. A pharmacist checks every prescription, and our team follows each order from the first enquiry until the medicine is collected.</p>
        <p>We also supply essential medicines, medical supplies and equipment. As part of the Getmeds group, which also works in the Philippines and India, we can source a wide range of medicines for patients in the Pacific.</p>
      </div>
    </div>
  </div>
</section>

<?php /* Photo band that widens from inset to full width as it scrolls in, with a
         white panel overlapping its bottom edge that fades up (guide.css "What
         we do band"; guide.js sets --band-progress). */ ?>
<section class="g-wwd" id="what-we-do" aria-labelledby="do-h">
  <div class="g-wwd__media" aria-hidden="true">
    <picture>
      <source type="image/webp" srcset="<?= e(asset('/assets/img/aboutbackground-800.webp')) ?> 800w, <?= e(asset('/assets/img/aboutbackground-1200.webp')) ?> 1200w" sizes="100vw">
      <img src="<?= e(asset('/assets/img/aboutbackground-1200.jpg')) ?>" srcset="<?= e(asset('/assets/img/aboutbackground-800.jpg')) ?> 800w, <?= e(asset('/assets/img/aboutbackground-1200.jpg')) ?> 1200w" sizes="100vw"
           width="1200" height="630" alt="" loading="lazy" decoding="async">
    </picture>
  </div>
  <div class="g-wrap">
    <div class="g-wwd__panel">
      <div class="g-wwd__head">
        <div>
          <span class="g-label">What We Do</span>
          <h2 id="do-h">We supply the medicines patients need, here in Vanuatu</h2>
        </div>
        <p class="g-wwd__lead">From our pharmacy in Port Vila, we supply cancer medicines and other important medicines and medical supplies.</p>
      </div>
      <div class="g-wwd__list">
        <?php g_check([
            "We supply cancer medicines against a doctor's prescription or treatment protocol.",
            'We keep medicines in stock in Port Vila.',
            'We import medicines that are not in stock.',
            'We source hard-to-find medicines for individual patients.',
            'We supply essential medicines, including antibiotics and medicines for diabetes, blood pressure, cholesterol, heart disease and kidney care.',
            'We supply medical consumables, devices, equipment and laboratory supplies.',
            'We offer simple blood pressure and blood sugar checks at our pharmacy.',
        ], '2'); ?>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" id="why-we-started" aria-labelledby="why-h">
  <div class="g-wrap">
    <?php /* Eyebrow and title, then the story under them. */ ?>
    <div class="g-why-start g-why-start--stacked">
      <div class="g-why-start__intro">
        <span class="g-label">Our Story</span>
        <h2 id="why-h">Why we started</h2>
      </div>
      <p class="g-why-start__story">Cancer is a major health problem in Vanuatu. But for a long time, many cancer medicines were not available here. Many patients were referred overseas for treatment. This can cost a family millions of vatu. When patients came home, some could not continue treatment because the medicine was not available locally. Others had to wait for someone to bring it from overseas. When a treatment cycle is missed, treatment can fail. Getmeds Vanuatu was started so that patients can get their medicine here, on time.</p>
    </div>
    <?php /* The problem, as a chain of arrows: each head lies over the start of
             the next (guide.css "Arrow chain"; guide.js slides them in). */
    $chain = [['doctor', 'Diagnosis'], ['plane', 'Treatment overseas'], ['shelf-empty', 'Home, but no medicine']]; ?>
    <ol class="g-chain" aria-label="The problem before Getmeds: diagnosis, then treatment overseas, then home but no medicine.">
      <?php foreach ($chain as $i => [$icon, $label]): ?>
      <li class="g-chain__step" style="--g-chain-i: <?= $i ?>; z-index: <?= count($chain) - $i ?>">
        <span class="g-chain__shape" aria-hidden="true"></span>
        <span class="g-chain__label"><?= gi($icon) ?><?= e($label) ?></span>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="g-sec g-bg-white" id="cancer-care" aria-labelledby="cancer-h">
  <div class="g-wrap g-split">
    <div>
      <h2 id="cancer-h">Our focus on cancer care</h2>
      <p class="g-mt">Getmeds Vanuatu is the first chemotherapy pharmacy in the Pacific. Cancer medicines are our main specialisation.</p>
      <div class="g-mt"><?php g_box('safety', 'We do not diagnose or treat cancer. That is the work of your doctor. Our job is to make sure the medicine your doctor prescribed is available, checked by a pharmacist and ready when you need it.'); ?></div>
    </div>
    <div class="g-media">
      <?= g_photo('shelves', 'A pharmacist in a white coat checking the label on a medicine bottle in front of shelves of stock.') ?>
    </div>
  </div>
</section>

<section class="g-sec g-bg-sky" id="named-patient" aria-labelledby="nps-h">
  <div class="g-wrap g-read">
    <h2 id="nps-h">Medicines for one named patient</h2>
    <p class="g-mt">Some medicines are not normally available in Vanuatu. Through Named Patient Supply, we help arrange a medicine for one named patient, based on their doctor's prescription. We handle the sourcing and paperwork and keep the patient and doctor informed.</p>
    <p class="g-mt"><a class="g-link-arrow" href="<?= e(url('/named-patient-supply')) ?>">How Named Patient Supply works <?= gi('arrow') ?></a></p>
  </div>
</section>

<section class="g-sec g-bg-navy g-wave g-about-pacific" id="pacific" aria-labelledby="pac-h">
  <div class="g-wrap g-split">
    <div>
      <h2 id="pac-h">Our place in the Pacific</h2>
      <p class="g-mt">Many Pacific Islands face the same problems: medicines that run out, high import costs for one patient, and long delivery times. From our base in Port Vila, we are working to build better medicine access across the region.</p>
      <ul class="g-chips g-mt" role="list">
        <?php foreach (['Solomon Islands', 'Fiji', 'Samoa', 'Tonga', 'Tuvalu', 'Nauru'] as $c): ?>
        <li class="g-chip g-chip--outline"><?= e($c) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="g-about-map"><?= g_pacific_map('dark') ?></div>
  </div>
</section>

<section class="g-sec g-bg-white" id="getmeds-group" aria-labelledby="group-h">
  <div class="g-wrap g-read g-about-group">
    <span class="g-card__icon"><?= gi('globe') ?></span>
    <div>
      <h2 id="group-h">Part of the wider Getmeds group</h2>
      <p class="g-mt">Getmeds Vanuatu is part of the Getmeds group, which also operates in the Philippines and India. This connection helps us source a wide range of medicines for patients in the Pacific.</p>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" id="who-we-serve" aria-labelledby="serve-h">
  <div class="g-wrap">
    <div class="g-head g-head--center g-serve-split">
      <span class="g-label">Our Community</span>
      <h2 id="serve-h">Who we serve</h2>
    </div>
    <ul class="g-grid g-grid--5 g-serve-row" role="list">
      <li><?= gi('users') ?><span>Patients and families</span></li>
      <li><?= gi('doctor') ?><span>Doctors and oncologists</span></li>
      <li><?= gi('hospital') ?><span>Hospitals and clinics</span></li>
      <li><?= gi('bottle') ?><span>Pharmacies</span></li>
      <li><?= gi('plane') ?><span>Overseas treatment agencies</span></li>
    </ul>
  </div>
</section>

<section class="g-sec g-bg-mint" id="team" aria-labelledby="team-h">
  <div class="g-wrap">
    <div class="g-about-col">
      <h2 id="team-h">Our team</h2>
      <p class="g-mt">Our team is based in Port Vila and knows the local community. A pharmacist checks every prescription we receive, and our staff follow each patient's order from enquiry to pick-up.</p>
    </div>
    <figure class="g-media g-about-team g-mt-lg">
      <?php /* Cropped from assets/img/vanuatutwo.jpg to drop its social-media banner. */ ?>
      <picture>
        <source type="image/webp" srcset="<?= e(asset('/assets/img/team-vanuatu-800.webp')) ?> 800w, <?= e(asset('/assets/img/team-vanuatu-1600.webp')) ?> 1600w" sizes="(max-width: 1248px) 100vw, 1200px">
        <img src="<?= e(asset('/assets/img/team-vanuatu-1600.jpg')) ?>" srcset="<?= e(asset('/assets/img/team-vanuatu-800.jpg')) ?> 800w, <?= e(asset('/assets/img/team-vanuatu-1600.jpg')) ?> 1600w" sizes="(max-width: 1248px) 100vw, 1200px"
             width="1600" height="804" loading="lazy" decoding="async"
             alt="Eight members of the Getmeds Vanuatu team standing together and smiling, most of them holding certificates.">
      </picture>
      <figcaption>The Getmeds Vanuatu team at our pharmacy in Golden Port, Port Vila.</figcaption>
    </figure>
  </div>
</section>

<section class="g-sec g-bg-white" id="achieve" aria-labelledby="achieve-h">
  <div class="g-wrap">
    <div class="g-head g-head--center"><h2 id="achieve-h">What we want to achieve</h2></div>
    <ul class="g-dots g-about-dots" role="list">
      <li>Specialist medicines that are easier to get, wherever someone lives in the Pacific.</li>
      <li>Fewer patients missing a treatment cycle.</li>
      <li>Patients and doctors who always know what is available and what it costs.</li>
    </ul>
  </div>
</section>

<section class="g-sec g-bg-white" id="safety" aria-labelledby="safe-h">
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

<section class="g-sec g-bg-white" id="how-it-works" aria-labelledby="how-h">
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

<?php g_cta_band(
    'Talk to us',
    'Have a question about a medicine? Our team is here to help.',
    [
        ['Request a Medicine', request_url('medicine'), 'primary'],
        ['Contact Us', url('/contact'), 'white'],
    ]
); ?>

<?php include INC . '/footer.php'; ?>
