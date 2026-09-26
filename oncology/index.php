<?php
/**
 * Oncology and Chemotherapy (content guide, page 03). The flagship page.
 *
 * Care Pink accents on the normal navy/blue/green system, and a sticky in-page
 * menu on the left on desktop (guide.js marks the section in view). The menu
 * sits in an absolutely placed rail over the sections, so each section keeps
 * its full-width background; every section leaves the rail's column empty.
 * No needles, IV drips or sick-looking people anywhere on the page.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Oncology and Chemotherapy',
    'seo_title' => 'Chemotherapy and Cancer Medicines in Vanuatu — Getmeds Vanuatu',
    'desc'      => "Getmeds Vanuatu supplies chemotherapy and cancer medicines in Port Vila against a doctor's prescription. In stock, imported or sourced for each patient.",
];

$menu = [
    'local-access'  => 'Why local access matters',
    'supply'        => 'How we supply',
    'types'         => 'Types of cancer medicine',
    'injectable'    => 'Injectable chemotherapy',
    'cycles'        => 'Planning each cycle',
    'professionals' => 'For doctors and hospitals',
    'patients'      => 'For patients',
    'pacific'       => 'Across the Pacific',
    'important'     => 'Important note',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-hero--grad g-wave g-onc-hero">
  <div class="g-wrap g-split g-split--7-5">
    <div>
      <span class="g-pilltag">First chemotherapy pharmacy in the Pacific</span>
      <h1>Cancer and chemotherapy medicines in Vanuatu</h1>
      <p class="g-hero__lede">Getmeds Vanuatu is the first chemotherapy pharmacy in the Pacific. We supply cancer medicines in Port Vila, so patients can continue their treatment closer to home.</p>
      <div class="g-btns">
        <?= g_btn('Request a Cancer Medicine', request_url('cancer'), 'primary') ?>
        <?= g_btn('Healthcare Professional Enquiry', request_url('professional'), 'white') ?>
      </div>
    </div>
    <div class="g-hero__media">
      <?php /* Cropped from assets/img/vanuatutwo.jpg (real Getmeds Vanuatu photo). */ ?>
      <picture>
        <source type="image/webp" srcset="<?= e(asset('/assets/img/oncology-hero-720.webp')) ?>">
        <img src="<?= e(asset('/assets/img/oncology-hero-720.jpg')) ?>" width="720" height="900" loading="eager" decoding="async"
             alt="Three smiling women from Port Vila holding their certificates of completion.">
      </picture>
    </div>
  </div>
</section>

<div class="g-onc">
  <div class="g-onc__rail">
    <nav class="g-sidemenu" data-spy aria-label="On this page">
      <ul>
        <?php foreach ($menu as $id => $label): ?>
        <li><a href="#<?= e($id) ?>"><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>
  </div>

  <section class="g-sec g-bg-white" id="local-access" aria-labelledby="local-h">
    <div class="g-wrap g-withside"><div class="g-onc__sp"></div>
      <div class="g-split">
        <div>
          <h2 id="local-h" class="g-onc-h">Why local access matters</h2>
          <div class="g-stack g-mt">
            <p>Cancer treatment is often given in cycles. Each cycle needs the right medicine at the right time.</p>
            <p>In the past, many patients in Vanuatu travelled overseas for treatment. When they came home, the medicine for their next cycle was often not available. Some waited for a traveller to bring it. Some missed cycles.</p>
            <p>Having cancer medicines in Vanuatu helps patients stay on the schedule their doctor planned.</p>
          </div>
        </div>
        <div class="g-onc-cycle">
          <svg viewBox="0 0 360 380" role="img" aria-label="A treatment cycle: Cycle 1, Cycle 2, Cycle 3 and Cycle 4 in a circle. Cycle 3 is greyed out and labelled missed cycle.">
            <defs>
              <marker id="ah-g" viewBox="0 0 10 10" refX="7" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse"><path d="M0 0 10 5 0 10z" fill="#6BB33F"/></marker>
              <marker id="ah-x" viewBox="0 0 10 10" refX="7" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse"><path d="M0 0 10 5 0 10z" fill="#9AA7B3"/></marker>
            </defs>
            <circle cx="180" cy="180" r="120" fill="none" stroke="#EEF7F2" stroke-width="18"/>
            <path d="M217.1 65.9 A120 120 0 0 1 294.1 142.9" fill="none" stroke="#6BB33F" stroke-width="3" marker-end="url(#ah-g)"/>
            <path d="M294.1 217.1 A120 120 0 0 1 217.1 294.1" fill="none" stroke="#9AA7B3" stroke-width="3" stroke-dasharray="5 6" marker-end="url(#ah-x)"/>
            <path d="M142.9 294.1 A120 120 0 0 1 65.9 217.1" fill="none" stroke="#9AA7B3" stroke-width="3" stroke-dasharray="5 6" marker-end="url(#ah-x)"/>
            <path d="M65.9 142.9 A120 120 0 0 1 142.9 65.9" fill="none" stroke="#6BB33F" stroke-width="3" marker-end="url(#ah-g)"/>
            <text x="180" y="174" text-anchor="middle" font-family="Poppins, sans-serif" font-size="17" font-weight="600" fill="#0B2A5B">Treatment</text>
            <text x="180" y="196" text-anchor="middle" font-family="Poppins, sans-serif" font-size="17" font-weight="600" fill="#0B2A5B">cycle</text>
            <?php foreach ([[1, 180, 60], [2, 300, 180], [4, 60, 180]] as [$n, $x, $y]): ?>
            <circle cx="<?= $x ?>" cy="<?= $y ?>" r="36" fill="#3D7F27"/>
            <text x="<?= $x ?>" y="<?= $y - 4 ?>" text-anchor="middle" font-family="Inter, sans-serif" font-size="12" font-weight="600" fill="#fff">Cycle</text>
            <text x="<?= $x ?>" y="<?= $y + 17 ?>" text-anchor="middle" font-family="Poppins, sans-serif" font-size="22" font-weight="700" fill="#fff"><?= $n ?></text>
            <?php endforeach; ?>
            <circle cx="180" cy="300" r="36" fill="#EEF1F4" stroke="#9AA7B3" stroke-width="2" stroke-dasharray="5 5"/>
            <text x="180" y="296" text-anchor="middle" font-family="Inter, sans-serif" font-size="12" font-weight="600" fill="#5A6B7B">Cycle</text>
            <text x="180" y="317" text-anchor="middle" font-family="Poppins, sans-serif" font-size="22" font-weight="700" fill="#5A6B7B">3</text>
            <rect x="122" y="344" width="116" height="28" rx="14" fill="#FDEEF4" stroke="#E8508A" stroke-width="1.5"/>
            <text x="180" y="363" text-anchor="middle" font-family="Inter, sans-serif" font-size="14" font-weight="600" fill="#B8245C">Missed cycle</text>
          </svg>
        </div>
      </div>
    </div>
  </section>

  <section class="g-sec g-bg-mint" id="supply" aria-labelledby="supply-h">
    <div class="g-wrap g-withside"><div class="g-onc__sp"></div>
      <div>
        <div class="g-head"><h2 id="supply-h" class="g-onc-h">How we supply cancer medicines</h2></div>
        <div class="g-grid g-grid--4 g-onc-cards">
          <?= g_card('shelf', 'In stock', 'The medicine is kept at our pharmacy in Port Vila.') ?>
          <?= g_card('plane', 'For import', 'We order the medicine for you from our suppliers.') ?>
          <?= g_card('search', 'Sourced on request', 'We search for a medicine that is hard to find or often out of stock.') ?>
          <?= g_card('user-tag', 'Named Patient Supply', 'We help arrange a medicine that is not normally available in Vanuatu, for one named patient.') ?>
        </div>
        <p class="g-mt-lg g-lead">Our pharmacist will tell you which route applies to your medicine and how long it may take.</p>
      </div>
    </div>
  </section>

  <section class="g-sec g-bg-white" id="types" aria-labelledby="types-h">
    <div class="g-wrap g-withside"><div class="g-onc__sp"></div>
      <div>
        <div class="g-head">
          <h2 id="types-h" class="g-onc-h">Types of cancer medicine we handle</h2>
          <p>We supply cancer medicines from first-line treatments through to more advanced medicines. They include:</p>
        </div>
        <div class="g-grid g-grid--3 g-onc-cards">
          <?= g_card('flask', 'Chemotherapy', 'Given as an injection or drip in hospital, or as tablets at home', 'g-card--compact') ?>
          <?= g_card('pill', 'Hormone therapy', 'Medicines used for cancers that respond to hormones', 'g-card--compact') ?>
          <?= g_card('scan', 'Targeted and biological medicines', 'Newer medicines aimed at specific features of a cancer', 'g-card--compact') ?>
          <?= g_card('drop', 'Medicines for blood cancers', 'Used in cancers such as leukaemia, lymphoma and myeloma', 'g-card--compact') ?>
          <?= g_card('heart', 'Supportive care medicines', 'Medicines used alongside treatment, such as anti-sickness medicine', 'g-card--compact') ?>
          <?= g_card('box', 'Single-use medical consumables used during cancer treatment', '', 'g-card--compact') ?>
        </div>
        <div class="g-mt-lg"><?php g_box('info', 'We do not list every medicine on this website because stock changes. Please contact us to check a specific medicine.'); ?></div>
      </div>
    </div>
  </section>

  <section class="g-sec g-bg-sky" id="injectable" aria-labelledby="inj-h">
    <div class="g-wrap g-withside"><div class="g-onc__sp"></div>
      <div class="g-onc-read">
        <h2 id="inj-h" class="g-onc-h">Injectable chemotherapy</h2>
        <div class="g-stack g-mt">
          <p>Some chemotherapy is given by injection or drip. These medicines must be given by trained staff in a hospital.</p>
          <p>If your medicine is an injectable, we prepare the supply for your session. Your chemotherapy session takes place at Vila Central Hospital or Vanuatu Private Hospital, as arranged by your treating team.</p>
        </div>
        <ul class="g-chips g-mt" role="list">
          <li class="g-chip g-chip--stat"><?= gi('building') ?>Vila Central Hospital</li>
          <li class="g-chip g-chip--stat"><?= gi('building') ?>Vanuatu Private Hospital</li>
        </ul>
      </div>
    </div>
  </section>

  <section class="g-sec g-bg-white" id="cycles" aria-labelledby="cyc-h">
    <div class="g-wrap g-withside"><div class="g-onc__sp"></div>
      <div>
        <div class="g-head">
          <h2 id="cyc-h" class="g-onc-h">Planning each treatment cycle</h2>
          <p>We do not just supply one order. We help you plan ahead.</p>
        </div>
        <div class="g-split g-split--7-5">
          <div class="g-onc-cal" role="img" aria-label="Four calendar pages, one for each treatment cycle, each with its pick-up date ticked in green.">
            <?php for ($c = 1; $c <= 4; $c++): ?>
            <div class="g-onc-cal__m">
              <span class="g-onc-cal__h">Cycle <?= $c ?></span>
              <span class="g-onc-cal__grid">
                <?php for ($d = 0; $d < 15; $d++): ?><i<?= $d === 7 ? ' class="is-pick"' : '' ?>><?= $d === 7 ? gi('check') : '' ?></i><?php endfor; ?>
              </span>
              <span class="g-onc-cal__f">Pick-up</span>
            </div>
            <?php endfor; ?>
          </div>
          <ul class="g-check g-check--doc" role="list">
            <li><?= gi('calendar') ?><span>We give you a pick-up date for each cycle.</span></li>
            <li><?= gi('pharmacist') ?><span>A pharmacist explains how to handle your medicine.</span></li>
            <li><?= gi('refresh') ?><span>We plan your next cycle with you, so your medicine is ready on time.</span></li>
            <li><?= gi('chat') ?><span>If your doctor changes your treatment, tell us and we will update your supply.</span></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="g-sec g-bg-white g-onc-flush" id="professionals" aria-labelledby="pro-h">
    <div class="g-wrap g-withside"><div class="g-onc__sp"></div>
      <div class="g-card g-card--navy g-onc-pro">
        <span class="g-card__icon"><?= gi('doctor') ?></span>
        <h2 id="pro-h">For doctors, oncologists and hospitals</h2>
        <p class="g-mt-sm">Send us the prescription or treatment protocol. Our pharmacist will confirm availability, supply route and price. We support single patients and ongoing supply to wards and clinics.</p>
        <div class="g-btns"><?= g_btn('Healthcare Professional Enquiry', request_url('professional'), 'primary') ?></div>
      </div>
    </div>
  </section>

  <section class="g-sec g-bg-white g-onc-flush" id="patients" aria-labelledby="pat-h">
    <div class="g-wrap g-withside"><div class="g-onc__sp"></div>
      <div class="g-card g-card--pink-left g-onc-pat">
        <span class="g-card__icon"><?= gi('heart') ?></span>
        <h2 id="pat-h">For patients and families</h2>
        <div class="g-stack g-mt-sm">
          <p>You need a prescription or treatment protocol from your doctor. We can only supply the medicine your doctor prescribed. This keeps you safe.</p>
          <p>If you are not sure what to send, call us. We will help you.</p>
        </div>
        <div class="g-btns"><?= g_btn('Patient Enquiry', request_url('patient'), 'primary') ?></div>
      </div>
    </div>
  </section>

  <section class="g-sec g-bg-mint" id="pacific" aria-labelledby="pac-h">
    <div class="g-wrap g-withside"><div class="g-onc__sp"></div>
      <div class="g-onc-read g-onc-pacific">
        <span class="g-card__icon"><?= gi('map') ?></span>
        <div>
          <h2 id="pac-h" class="g-onc-h">Cancer medicine access across the Pacific</h2>
          <p class="g-mt">Many Pacific Islands have the same gaps in cancer medicine supply. Getmeds is working to extend access from Vanuatu to other Pacific countries. Contact us to ask what is possible for your country.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="g-sec g-sec--tight g-bg-white" id="important" aria-label="Important note">
    <div class="g-wrap g-withside"><div class="g-onc__sp"></div>
      <div><?php g_box('safety', '<strong>Important:</strong> Getmeds Vanuatu supplies medicines. We do not diagnose cancer or choose treatment. Always follow the advice of your doctor or oncologist. Do not change a medicine, dose or schedule without speaking to them first.'); ?></div>
    </div>
  </section>
</div>

<?php g_cta_band(
    'Ask about a cancer medicine',
    'Send the prescription and we will check what we can supply.',
    [
        ['Request a Cancer Medicine', request_url('cancer'), 'primary'],
        ['Call Us', tel_url(), 'white', 'phone'],
    ]
); ?>

<?php include INC . '/footer.php'; ?>
