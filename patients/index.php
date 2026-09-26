<?php
/**
 * For Patients and Families (content guide, page 12).
 *
 * The warmest page on the site, based on the printed Getmeds Patient Guide:
 * Care Pink step numbers, rounded white step cards on the brand gradient and
 * larger body text (19px). The emergency box sits near the bottom, directly
 * under the friendly "Not sure what to do next?" text, where it cannot be
 * missed.
 *
 * The guide asks for a portrait of a smiling Pacific woman in a head scarf.
 * We have no such photograph, so the hero uses a clean illustration in brand
 * colours instead of a stand-in photo.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'For Patients and Families',
    'seo_title' => 'For Patients and Families — Getmeds Vanuatu',
    'desc'      => 'Need a cancer medicine or other prescribed medicine in Vanuatu? Learn how Getmeds '
                 . 'Vanuatu can help, what to send, and what happens next.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<div class="g-patients">

<section class="g-hero g-hero--grad g-wave" aria-labelledby="pat-h1">
  <div class="g-wrap">
    <div class="g-split g-split--7-5">
      <div>
        <span class="g-script">Patient Guide</span>
        <h1 id="pat-h1">For patients and families</h1>
        <p class="g-hero__lede">We help you get the medicine your doctor prescribed. You do not need to know medical words to ask us for help.</p>
        <div class="g-btns">
          <?= g_btn('Patient Enquiry', request_url('patient'), 'primary', 'chat') ?>
          <?= g_call_btn('Call Us', 'white') ?>
        </div>
      </div>
      <div class="g-pat-portrait">
        <svg viewBox="0 0 400 440" role="img" aria-label="Illustration of a smiling Pacific woman wearing a head scarf.">
          <circle cx="200" cy="210" r="188" fill="#FFFFFF" opacity=".92"/>
          <circle cx="200" cy="210" r="188" fill="none" stroke="#E8508A" stroke-width="6" opacity=".9"/>
          <clipPath id="patClip"><circle cx="200" cy="210" r="185"/></clipPath>
          <g clip-path="url(#patClip)">
            <rect x="0" y="0" width="400" height="440" fill="#FDF3EC"/>
            <path d="M0 330 C 80 300, 150 340, 200 320 S 330 290, 400 320 V440 H0 Z" fill="#D8EFE3"/>
            <!-- Shoulders / dress -->
            <path d="M70 440 C 76 360, 130 322, 200 318 C 270 322, 324 360, 330 440 Z" fill="#2FB5A6"/>
            <path d="M92 440 C 100 392, 124 364, 150 352 M308 440 C 300 392, 276 364, 250 352" fill="none" stroke="#FFFFFF" stroke-width="5" opacity=".45"/>
            <g fill="#FFFFFF" opacity=".55"><circle cx="130" cy="400" r="6"/><circle cx="170" cy="420" r="6"/><circle cx="230" cy="420" r="6"/><circle cx="270" cy="400" r="6"/><circle cx="200" cy="396" r="6"/></g>
            <!-- Neck -->
            <path d="M172 280 h56 v48 c-10 14 -46 14 -56 0 Z" fill="#8A5A3C"/>
            <!-- Head scarf, back -->
            <path d="M104 190 C 100 110, 150 66, 200 66 C 250 66, 300 110, 296 190 C 300 240, 286 286, 262 306 L 138 306 C 114 286, 100 240, 104 190 Z" fill="#E8508A"/>
            <!-- Face -->
            <ellipse cx="200" cy="208" rx="70" ry="84" fill="#9B6644"/>
            <!-- Head scarf, front band -->
            <path d="M122 170 C 128 112, 168 92, 200 92 C 232 92, 272 112, 278 170 C 258 146, 230 134, 200 134 C 170 134, 142 146, 122 170 Z" fill="#F07BA6"/>
            <g fill="#FFFFFF" opacity=".7"><circle cx="160" cy="112" r="4"/><circle cx="200" cy="104" r="4"/><circle cx="240" cy="112" r="4"/><circle cx="180" cy="124" r="3"/><circle cx="220" cy="124" r="3"/></g>
            <!-- Knot -->
            <circle cx="286" cy="118" r="18" fill="#F07BA6"/><path d="M296 130 c14 10 20 26 16 42 c-10 -8 -18 -20 -22 -34 Z" fill="#E8508A"/>
            <!-- Features -->
            <path d="M160 196 q12 -10 24 0 M216 196 q12 -10 24 0" fill="none" stroke="#3A2416" stroke-width="5" stroke-linecap="round"/>
            <path d="M156 180 q14 -8 28 -2 M216 178 q14 -6 28 2" fill="none" stroke="#3A2416" stroke-width="4" stroke-linecap="round" opacity=".7"/>
            <path d="M198 206 q-6 18 4 22" fill="none" stroke="#6E4329" stroke-width="4" stroke-linecap="round"/>
            <path d="M170 244 q30 26 60 0" fill="#FFFFFF" stroke="#6E2F2F" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="158" cy="228" r="11" fill="#E8508A" opacity=".22"/><circle cx="242" cy="228" r="11" fill="#E8508A" opacity=".22"/>
            <!-- Flower -->
            <g transform="translate(126 152)"><circle r="9" fill="#FFFFFF"/><circle cx="0" cy="-12" r="8" fill="#FFFFFF"/><circle cx="11" cy="-4" r="8" fill="#FFFFFF"/><circle cx="7" cy="10" r="8" fill="#FFFFFF"/><circle cx="-7" cy="10" r="8" fill="#FFFFFF"/><circle cx="-11" cy="-4" r="8" fill="#FFFFFF"/><circle r="5" fill="#F2A900"/></g>
          </g>
        </svg>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="what">
  <div class="g-wrap g-narrow g-center g-stack">
    <h2 id="what">What we do</h2>
    <p style="margin-inline:auto">Getmeds Vanuatu is a pharmacy in Port Vila. We supply cancer medicines and other important medicines. If a medicine is not in stock, we can often import or source it for you.</p>
  </div>
</section>

<section class="g-sec g-bg-grad g-wave" aria-labelledby="ask">
  <div class="g-wrap">
    <div class="g-head g-head--center"><h2 id="ask">How to ask for help</h2></div>
    <ol class="g-pguide">
      <?php foreach ([
          ['Register', 'Share your basic details so we can understand your situation and give the right support for your treatment journey.'],
          ['Share your prescription or treatment protocol', "Cancer medicines must follow a doctor's prescription or treatment protocol. For your safety, we only supply medicines that match what your doctor prescribed."],
          ['Pharmacy check', 'Our pharmacist checks your prescription, medicine availability and price, and tells you if anything needs special ordering, with the earliest possible date.'],
          ['Pick-up schedule', 'Pay at an affordable price. We give you a schedule for pick-up, counselling and planning of your next treatment cycle, so you never miss a cycle.'],
          ['Pick up your medicine', 'Collect your medicine on the scheduled date. If it is an injectable, go to Vila Central Hospital or Vanuatu Private Hospital for your chemo session.'],
      ] as $i => [$t, $d]): ?>
      <li class="g-pguide__card">
        <span class="g-pguide__num" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
        <h3 class="g-pguide__tab"><?= e($t) ?>.</h3>
        <p><?= e($d) ?></p>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="doctor">
  <div class="g-wrap g-read g-stack">
    <h2 id="doctor">Your doctor's role</h2>
    <p>Your doctor decides your treatment. We supply the medicine. We cannot choose a medicine for you or change your treatment.</p>
    <?php g_box('safety', 'If you do not yet have a prescription, please see a doctor first.'); ?>
  </div>
</section>

<section class="g-sec g-bg-mint" aria-labelledby="handled">
  <div class="g-wrap g-read">
    <div class="g-head"><h2 id="handled">How prescription medicines are handled</h2></div>
    <?php g_check([
        'Prescription medicines need a valid prescription.',
        'Our pharmacist checks every prescription.',
        'We only supply what your doctor prescribed.',
        'A pharmacist explains how to take or handle your medicine.',
    ], 'ticks', 'check-circle'); ?>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="cancer">
  <div class="g-wrap g-read">
    <div class="g-card g-card--pink-left g-stack">
      <h2 id="cancer">If you need a cancer medicine</h2>
      <p>Cancer medicines must follow your doctor's prescription or treatment protocol. For your safety, we can only supply medicines that match what your doctor prescribed.</p>
      <p>If your medicine is an injection or drip, it is given at the hospital: Vila Central Hospital or Vanuatu Private Hospital.</p>
      <p>If you had treatment overseas and need to continue at home, bring the prescription or protocol from the overseas hospital.</p>
    </div>
  </div>
</section>

<section class="g-sec g-bg-sky" aria-labelledby="notavail">
  <div class="g-wrap g-read g-stack">
    <h2 id="notavail">If your medicine is not available in Vanuatu</h2>
    <p>We may be able to order it just for you through Named Patient Supply. It can take longer. We will tell you the price and expected time first.</p>
    <p><a class="g-link-arrow" href="<?= e(url('/named-patient-supply')) ?>">About Named Patient Supply <?= gi('arrow') ?></a></p>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="next">
  <div class="g-wrap g-read g-stack">
    <h2 id="next">Not sure what to do next?</h2>
    <p>Call us or send a message. Tell us what your doctor has told you, and we will explain the next steps in simple words.</p>
    <?php g_box('emergency', 'If you feel very unwell, have a fever over 38 °C during chemotherapy, trouble breathing, or bleeding that will not stop, go to the hospital straight away. Do not wait for a reply from the pharmacy.', 'In an emergency:'); ?>
    <div class="g-btns">
      <?= g_btn('Patient Enquiry', request_url('patient'), 'primary', 'chat') ?>
      <?= g_call_btn('Call Us') ?>
    </div>
  </div>
</section>

</div>

<?php include INC . '/footer.php'; ?>
