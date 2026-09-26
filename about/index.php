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

<section class="g-hero g-hero--photo g-hero--half g-about-hero">
  <div class="g-hero__bg" aria-hidden="true"><?= g_photo('wharf', '', '', true) ?></div>
  <div class="g-wrap">
    <?php g_crumbs([['Home', '/'], ['About', null]]); ?>
    <h1>About Getmeds Vanuatu</h1>
    <p class="g-hero__lede">Getmeds Vanuatu is a pharmacy in Port Vila. Our main work is making cancer medicines available in Vanuatu and the Pacific. We also supply other important medicines and medical supplies.</p>
  </div>
</section>

<section class="g-sec g-bg-white" id="why-we-started" aria-labelledby="why-h">
  <div class="g-wrap g-split g-split--7-5 g-split--top">
    <div class="g-about-col">
      <h2 id="why-h">Why we started</h2>
      <div class="g-stack g-mt">
        <p>Cancer is a major health problem in Vanuatu. But for a long time, many cancer medicines were not available here.</p>
        <p>Many patients were referred overseas for treatment. This can cost a family millions of vatu. When patients came home, some could not continue treatment because the medicine was not available locally. Others had to wait for someone to bring it from overseas.</p>
        <p>When a treatment cycle is missed, treatment can fail. Getmeds Vanuatu was started so that patients can get their medicine here, on time.</p>
      </div>
    </div>
    <div class="g-diagram g-diagram--v" role="img" aria-label="The problem before Getmeds: diagnosis, then treatment overseas, then home but no medicine.">
      <div class="g-diagram__step g-diagram__step--grey"><?= gi('doctor') ?><strong>Diagnosis</strong></div>
      <span class="g-diagram__arrow"><?= gi('arrow') ?></span>
      <div class="g-diagram__step g-diagram__step--grey"><?= gi('plane') ?><strong>Treatment overseas</strong></div>
      <span class="g-diagram__arrow"><?= gi('arrow') ?></span>
      <div class="g-diagram__step g-diagram__step--grey"><?= gi('shelf-empty') ?><strong>Home, but no medicine</strong></div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-mint" id="what-we-do" aria-labelledby="do-h">
  <div class="g-wrap g-narrow">
    <div class="g-head"><h2 id="do-h">What we do</h2></div>
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

<?php g_cta_band(
    'Talk to us',
    'Have a question about a medicine? Our team is here to help.',
    [
        ['Request a Medicine', request_url('medicine'), 'primary'],
        ['Contact Us', url('/contact'), 'white'],
    ]
); ?>

<?php include INC . '/footer.php'; ?>
