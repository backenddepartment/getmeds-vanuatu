<?php
/**
 * /how-it-works — Content & Design Guide, page 06 (How It Works / Request a
 * Medicine).
 *
 * The five steps are the whole page: a vertical timeline on every screen size,
 * a green line joining the numbers, each step a large numbered block with an
 * icon. On desktop a sticky "Request a Medicine" button sits to the right. The
 * old patient sub-pages (delivery, how-to-order, what-you-need) redirect here.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'How to request a medicine',
    'seo_title' => 'How to Request a Medicine — Getmeds Vanuatu',
    'desc'      => 'Request a medicine from Getmeds Vanuatu in five simple steps. Send your prescription, a pharmacist checks it, and you get a clear price before you commit.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-bg-white g-hiw-hero g-pg">
  <div class="g-wrap">
    <h1>How to request a medicine</h1>
    <p class="g-hero__lede">Five simple steps. A pharmacist checks every request. You see the price before you pay anything.</p>
    <?php g_trust([
        ['shield',  'Pharmacist-checked'],
        ['receipt', 'Price before you pay'],
        ['file',    'Prescription required'],
    ]); ?>
  </div>
</section>

<section class="g-sec g-bg-white g-hiw-main" aria-label="Five steps">
  <div class="g-wrap g-hiw-layout">
    <ol class="g-tl" role="list">

      <li class="g-tl__step" id="step-1">
        <span class="g-tl__num" aria-hidden="true">1</span>
        <div class="g-tl__body">
          <span class="g-tl__icon"><?= gi('phone') ?><?= gi('chat') ?></span>
          <h2 class="g-tl__title">Step 1 — Contact Getmeds</h2>
          <p>Start your enquiry by phone, email, the online form, or in person at our pharmacy in Port Vila. Tell us your name, how to reach you and which medicine you need.</p>
          <ul class="g-chips g-mt-sm" role="list">
            <li><a class="g-chip" href="<?= e(tel_url()) ?>"><?= gi('phone') ?>Phone</a></li>
            <li><a class="g-chip" href="<?= e(request_url('medicine')) ?>"><?= gi('mail') ?>Email / online form</a></li>
            <li><a class="g-chip" href="<?= e(url('/contact')) ?>"><?= gi('pin') ?>Visit us</a></li>
          </ul>
        </div>
      </li>

      <li class="g-tl__step" id="step-2">
        <span class="g-tl__num" aria-hidden="true">2</span>
        <div class="g-tl__body">
          <span class="g-tl__icon"><?= gi('clipboard') ?></span>
          <h2 class="g-tl__title">Step 2 — Provide the required information</h2>
          <p>Send us the doctor's prescription or treatment protocol. A clear photo is fine. Depending on the medicine, we may also ask for:</p>
          <div class="g-mt-sm">
            <?php g_check([
                'patient details',
                'the doctor\'s or hospital\'s details',
                'the date you need the medicine',
                'other medical papers, if needed for import',
            ]); ?>
          </div>
          <div class="g-mt">
            <?php g_box('info', 'A clear phone photo of the prescription is fine.', '', 'upload'); ?>
          </div>
        </div>
      </li>

      <li class="g-tl__step" id="step-3">
        <span class="g-tl__num" aria-hidden="true">3</span>
        <div class="g-tl__body">
          <span class="g-tl__icon"><?= gi('pharmacist') ?></span>
          <h2 class="g-tl__title">Step 3 — Availability is checked</h2>
          <p>Our pharmacist checks the prescription, whether the medicine is in stock, and the price. If the medicine needs to be imported or specially ordered, we tell you and give you the earliest date we can.</p>
        </div>
      </li>

      <li class="g-tl__step" id="step-4">
        <span class="g-tl__num" aria-hidden="true">4</span>
        <div class="g-tl__body">
          <span class="g-tl__icon"><?= gi('calendar') ?></span>
          <h2 class="g-tl__title">Step 4 — Supply arrangements</h2>
          <p>If you agree to the price, you confirm your order and pay. We then:</p>
          <ul class="g-check g-check--doc g-mt-sm" role="list">
            <li><?= gi('calendar') ?><span>give you a date to collect your medicine</span></li>
            <li><?= gi('pharmacist') ?><span>book time for a pharmacist to explain your medicine</span></li>
            <li><?= gi('refresh') ?><span>plan your next treatment cycle with you, where this applies</span></li>
          </ul>
          <p class="g-pg-mt">Timing depends on the medicine and where it comes from. We will not promise a date we cannot keep.</p>
        </div>
      </li>

      <li class="g-tl__step" id="step-5">
        <span class="g-tl__num" aria-hidden="true">5</span>
        <div class="g-tl__body">
          <span class="g-tl__icon"><?= gi('bag') ?></span>
          <h2 class="g-tl__title">Step 5 — Medicine access</h2>
          <p>Collect your medicine on the agreed date in Port Vila, or ask us about delivery to your island. Every medicine is supplied through the proper prescription and regulatory process.</p>
          <div class="g-mt">
            <?php g_box('info', 'If your medicine is an injectable, it is given at the hospital. It is used at Vila Central Hospital or Vanuatu Private Hospital for your chemotherapy session, as arranged with your treating team.', '', 'hospital'); ?>
          </div>
        </div>
      </li>

    </ol>

    <aside class="g-hiw-side" aria-label="Request a Medicine">
      <div class="g-sidecard">
        <?= g_btn('Request a Medicine', request_url('medicine'), 'primary', 'pill') ?>
      </div>
    </aside>
  </div>
</section>

<section class="g-sec g-bg-mint" aria-labelledby="after-h">
  <div class="g-wrap g-narrow g-hiw-after">
    <span class="g-card__icon g-hiw-loop"><?= gi('refresh') ?></span>
    <div class="g-stack">
      <h2 id="after-h">After your first order</h2>
      <p>For ongoing treatment, we keep in touch before each cycle. If your doctor changes your medicine or dose, send us the new prescription and we will update your supply.</p>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="hosp-h">
  <div class="g-wrap g-narrow">
    <div class="g-card g-card--navy g-hiw-hosp">
      <span class="g-card__icon"><?= gi('hospital') ?></span>
      <h2 id="hosp-h">For hospitals, clinics and pharmacies</h2>
      <p class="g-pg-mt">Send your requirement or product list. We reply with a quotation. After you confirm, we source, receive and supply the order, and keep a full record.</p>
      <div class="g-btns">
        <?= g_btn('Request a Quotation', request_url('quotation'), 'white') ?>
      </div>
    </div>
  </div>
</section>

<?php g_cta_band(
    'Start your request',
    '',
    [
        ['Request a Medicine', request_url('medicine'), 'primary'],
        ['Call Us', tel_url(), 'white', 'phone'],
    ]
); ?>

<?php include INC . '/footer.php'; ?>
