<?php
/**
 * For Patients and Families (content guide, page 12).
 *
 * In the site's current style (as Pacific Access and Healthcare
 * Professionals): white and Getmeds-blue sections in turn, colour photos,
 * and the shared scroll-in motion (.hx, guide.js 7f; guide.css "Patients").
 * The emergency box sits at the end, directly under "Not sure what to do
 * next?", where it cannot be missed.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'For Patients and Families',
    'seo_title' => 'For Patients and Families — Getmeds Vanuatu',
    'desc'      => 'Need a cancer medicine or other prescribed medicine in Vanuatu? Learn how Getmeds '
                 . 'Vanuatu can help, what to send, and what happens next.',
];

$steps = [
    ['Register', 'Share your basic details so we can understand your situation and give the right support for your treatment journey.'],
    ['Share your prescription or treatment protocol', "Cancer medicines must follow a doctor's prescription or treatment protocol. For your safety, we only supply medicines that match what your doctor prescribed."],
    ['Pharmacy check', 'Our pharmacist checks your prescription, medicine availability and price, and tells you if anything needs special ordering, with the earliest possible date.'],
    ['Pick-up schedule', 'Once you agree to the price, we give you a schedule for pick-up, counselling and planning of your next treatment cycle, so you never miss a cycle.'],
    ['Pick up your medicine', 'Collect your medicine on the scheduled date. If it is an injectable, go to Vila Central Hospital or Vanuatu Private Hospital for your chemo session.'],
];

include INC . '/head.php';
include INC . '/header.php';
?>

<?php /* Hero (white): eyebrow and title on the left; the lede, what we do
         and the buttons on the right (.hx-hero--split). */ ?>
<section class="hx hx-hero hx-hero--split g-nps-hero" aria-labelledby="pat-h1">
  <div class="g-wrap hx-hero__grid">
    <div class="hx-hero__text">
      <span class="g-label" data-rv style="--d:0">Patient Guide</span>
      <h1 id="pat-h1">
        <span class="hx-mask" data-rv style="--d:1"><span>For patients</span></span>
        <span class="hx-mask" data-rv style="--d:2"><span>and families</span></span>
      </h1>
    </div>
    <div class="hx-hero__body">
      <p class="g-hero__lede" data-rv style="--d:3">We help you get the medicine your doctor prescribed. You do not need to know medical words to ask us for help.</p>
      <p class="g-nps-simple" data-rv style="--d:4">Getmeds Vanuatu is a pharmacy in Port Vila. We supply cancer medicines and other important medicines. If a medicine is not in stock, we can often import or source it for you.</p>
      <div class="hx-hero__btns" data-rv style="--d:5">
        <?= g_btn('Patient Enquiry', request_url('patient')) ?>
        <?= g_btn('Call Us', tel_url()) ?>
      </div>
    </div>
  </div>
</section>

<?php /* How to ask for help (blue): the five steps side by side, each with a
         gently moving icon, joined by a flowing dashed line. */ ?>
<section class="hx px-sec px-blue pt-steps" aria-labelledby="ask">
  <div class="g-wrap">
    <div class="px-head">
      <div>
        <span class="hx-kicker" data-rv>Step by Step</span>
        <h2 id="ask" class="hx-mask" data-rv style="--d:1"><span>How to ask for help</span></h2>
      </div>
      <p data-rv style="--d:2">Five simple steps. A pharmacist checks every request, and you know the price before you pay.</p>
    </div>
    <ol class="pt-list">
      <?php $icons = ['user-tag', 'file', 'pharmacist', 'calendar', 'bag'];
      foreach ($steps as $i => [$t, $d]): ?>
      <li class="pt-step" data-rv style="--d:<?= $i ?>; --i:<?= $i ?>">
        <span class="pt-step__icon" aria-hidden="true"><?= gi($icons[$i]) ?><span class="pt-step__no"><?= $i + 1 ?></span></span>
        <div>
          <h3><?= e($t) ?></h3>
          <p><?= e($d) ?></p>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<?php /* Your doctor's role, and how prescription medicines are handled (white). */ ?>
<section class="hx px-sec pt-doctor" aria-labelledby="doctor">
  <div class="g-wrap pt-two">
    <div>
      <span class="hx-kicker" data-rv>Your Doctor</span>
      <h2 id="doctor" class="hx-mask" data-rv style="--d:1"><span>Your doctor's role</span></h2>
      <p class="pt-text" data-rv style="--d:2">Your doctor decides your treatment. We supply the medicine. We cannot choose a medicine for you or change your treatment.</p>
      <p class="pt-note" data-rv style="--d:3">If you do not yet have a prescription, please see a doctor first.</p>
    </div>
    <div>
      <span class="hx-kicker" data-rv>Your Safety</span>
      <h2 class="hx-mask" data-rv style="--d:1"><span>How prescription medicines are handled</span></h2>
      <ul class="pt-checks">
        <?php foreach ([
            'Prescription medicines need a valid prescription.',
            'Our pharmacist checks every prescription.',
            'We only supply what your doctor prescribed.',
            'A pharmacist explains how to take or handle your medicine.',
        ] as $i => $t): ?>
        <li data-rv style="--d:<?= $i + 2 ?>"><?= gi('check-circle') ?><?= e($t) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<?php /* Cancer medicines (white, as the sections either side of it). */ ?>
<section class="hx px-sec pt-cancer-sec" aria-labelledby="cancer">
  <div class="g-wrap">
    <div class="pt-cancer">
      <span class="hx-kicker" data-rv>Cancer Care</span>
      <h2 id="cancer" class="hx-mask" data-rv style="--d:1"><span>If you need a cancer medicine</span></h2>
      <p class="pt-text" data-rv style="--d:2">Cancer medicines must follow your doctor's prescription or treatment protocol. For your safety, we can only supply medicines that match what your doctor prescribed. If your medicine is an injection or drip, it is given at the hospital: Vila Central Hospital or Vanuatu Private Hospital. If you had treatment overseas and need to continue at home, bring the prescription or protocol from the overseas hospital.</p>
    </div>
  </div>
</section>

<?php /* Medicines not available in Vanuatu (white): a rounded photo banner with
         the text on its empty right side, then the details in two columns. */ ?>
<section class="hx px-sec pt-nps" aria-labelledby="nps">
  <div class="g-wrap">
    <div class="pt-nps__banner">
      <div class="pt-nps__bg" data-rv="clip">
        <img src="<?= e(asset('/assets/img/namedpatientbg.jpg')) ?>" width="1914" height="822" alt="A smiling man in a light blue hospital gown, arms folded, with an IV line taped to his hand." loading="lazy" decoding="async">
      </div>
      <div class="pt-nps__text">
        <span class="hx-kicker" data-rv>Named Patient Supply</span>
        <h2 id="nps" class="hx-mask" data-rv style="--d:1"><span>If your medicine is not available in Vanuatu</span></h2>
        <p class="pt-text" data-rv style="--d:2">We may be able to order it just for you through Named Patient Supply. It can take longer. We will tell you the price and expected time first.</p>
        <div class="px-btns" data-rv style="--d:3">
          <?= g_btn('About Named Patient Supply', url('/named-patient-supply')) ?>
        </div>
      </div>
    </div>
    <div class="pt-two pt-nps__more">
      <p class="pt-text" data-rv>This is for a medicine your doctor has prescribed that Vanuatu does not normally have. We order it for you by name and handle the sourcing and the import paperwork. While it is on its way, we keep you and your doctor updated.</p>
      <p class="pt-text" data-rv style="--d:1">Not every medicine can be supplied this way. Some medicines cannot be exported by the maker, or need papers that are not available. We will tell you honestly what is possible.</p>
    </div>
  </div>
</section>

<?php /* Not sure what to do next: a green banner sitting on the footer,
         text left and glassy buttons right. */ ?>
<section class="hx pt-next" aria-labelledby="next">
  <div class="g-wrap pt-next__row">
    <div class="pt-next__text">
      <span class="hx-kicker" data-rv>Need Help?</span>
      <h2 id="next" class="hx-mask" data-rv style="--d:1"><span>Not sure what to do next?</span></h2>
      <p class="pt-text" data-rv style="--d:2">Call us or send a message. Tell us what your doctor has told you, and we will explain the next steps in simple words.</p>
    </div>
    <div class="px-btns" data-rv style="--d:3">
      <?= g_btn('Patient Enquiry', request_url('patient')) ?>
      <?= g_btn('Call Us', tel_url()) ?>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
