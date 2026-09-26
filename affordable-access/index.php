<?php
/**
 * Affordable Access to Medicines (content guide, page 10).
 *
 * Honest and reassuring: no price tags, discount badges, percentages or sale
 * colours. Calm blues and greens. The hero illustration compares travelling
 * overseas for medicine with medicine available at home; the Getmeds side
 * carries no numbers.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Affordable Access to Medicines',
    'seo_title' => 'More Affordable Access to Medicines — Getmeds Vanuatu',
    'desc'      => 'Getmeds Vanuatu works to reduce the barriers to important medicines in Vanuatu and '
                 . 'the Pacific, with clear prices before you commit.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-hero--mint" aria-labelledby="aff-h1">
  <div class="g-wrap">
    <div class="g-split g-split--7-5">
      <div>
        <h1 id="aff-h1">More affordable access to medicines</h1>
        <p class="g-hero__lede">Important medicines should be within reach. We work to lower the barriers.</p>
      </div>
      <div class="g-aff-compare" role="img" aria-label="Comparison. Travel overseas for medicine: a plane and many separate costs. Medicine available at home: a local pharmacy and one clear price.">
        <div class="g-aff-compare__side g-aff-compare__side--away" aria-hidden="true">
          <svg viewBox="0 0 220 150">
            <circle cx="110" cy="70" r="58" fill="#E6EEF4"/>
            <path d="M52 86 C 70 80, 140 78, 164 80 C 174 81, 174 90, 164 91 C 140 94, 70 94, 52 90 Z" fill="#fff" stroke="#5A6B7B" stroke-width="3"/>
            <path d="M96 80 L 118 52 L 130 52 L 124 80 Z M96 92 L 118 118 L 130 118 L 124 92 Z" fill="#CBD6DF" stroke="#5A6B7B" stroke-width="3" stroke-linejoin="round"/>
            <path d="M58 80 L 50 62 L 60 62 L 70 80 Z" fill="#CBD6DF" stroke="#5A6B7B" stroke-width="3" stroke-linejoin="round"/>
            <g fill="#fff" stroke="#5A6B7B" stroke-width="2.5" stroke-linejoin="round">
              <path d="M16 12h26v30l-4.3-3-4.3 3-4.4-3-4.3 3-4.4-3-4.3 3z"/>
              <path d="M176 14h26v30l-4.3-3-4.3 3-4.4-3-4.3 3-4.4-3-4.3 3z"/>
              <path d="M12 102h26v30l-4.3-3-4.3 3-4.4-3-4.3 3-4.4-3-4.3 3z"/>
              <path d="M180 100h26v30l-4.3-3-4.3 3-4.4-3-4.3 3-4.4-3-4.3 3z"/>
            </g>
            <g stroke="#5A6B7B" stroke-width="2.5" stroke-linecap="round">
              <path d="M22 21h14M22 28h10M182 23h14M182 30h10M18 111h14M18 118h10M186 109h14M186 116h10"/>
            </g>
          </svg>
          <strong>Travel overseas for medicine</strong>
          <span>Many costs</span>
        </div>
        <div class="g-aff-compare__side g-aff-compare__side--home" aria-hidden="true">
          <svg viewBox="0 0 220 150">
            <circle cx="110" cy="70" r="58" fill="#D8EFE3"/>
            <path d="M62 64 L 110 32 L 158 64" fill="none" stroke="#0B2A5B" stroke-width="3" stroke-linejoin="round"/>
            <rect x="70" y="62" width="80" height="58" rx="4" fill="#fff" stroke="#0B2A5B" stroke-width="3"/>
            <rect x="100" y="90" width="20" height="30" fill="#EEF7F2" stroke="#0B2A5B" stroke-width="3"/>
            <rect x="104" y="68" width="12" height="16" rx="1.5" fill="#6BB33F"/>
            <rect x="102" y="70" width="16" height="12" rx="1.5" fill="#6BB33F"/>
            <path d="M40 124 H180" stroke="#2FB5A6" stroke-width="4" stroke-linecap="round"/>
            <g transform="translate(160 22)">
              <rect width="40" height="28" rx="6" fill="#fff" stroke="#1E9BD7" stroke-width="3"/>
              <path d="M10 14 l6 6 l14 -12" fill="none" stroke="#3D7F27" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
          </svg>
          <strong>Medicine available at home</strong>
          <span>One clear price</span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="cost">
  <div class="g-wrap">
    <div class="g-split g-split--7-5">
      <div class="g-stack">
        <h2 id="cost">The cost problem</h2>
        <p>In Vanuatu, cancer and dialysis medicines are usually paid for by the patient. There is no national health insurance.</p>
        <p>Many cancer patients are referred overseas. This can cost an estimated 3 to 6 million vatu per patient, before the cost of medicines and family support. The stress on families is also high.</p>
      </div>
      <div class="g-aff-figure">
        <p class="g-stat">3 to 6 million vatu</p>
        <span class="g-stat__cap">Estimated cost of overseas cancer referral per patient</span>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-sky" aria-labelledby="help">
  <div class="g-wrap">
    <div class="g-head">
      <h2 id="help">How we help</h2>
    </div>
    <div class="g-grid g-grid--3 g-aff-help">
      <?php foreach ([
          ['home',    'Local supply.', 'Having medicines in Vanuatu can reduce the need to travel for them.'],
          ['search',  'Wide sourcing.', 'We compare sources to find more affordable options where possible.'],
          ['calendar','Planning.', 'Planning each cycle ahead helps avoid costly urgent orders.'],
          ['receipt', 'Clear prices.', 'We tell you the price before you commit.'],
          ['chat',    'Options.', 'If a medicine is very costly, we can talk with your doctor about available options. Your doctor decides the treatment.'],
      ] as [$ic, $t, $d]): ?>
      <?= g_card($ic, $t, e($d)) ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="pricing">
  <div class="g-wrap g-read g-stack">
    <h2 id="pricing">How pricing works</h2>
    <p>We do not publish prices on this website. The price depends on the medicine, the strength, the amount and where it comes from. Import costs, freight and paperwork can also affect the price.</p>
    <?php g_box('info', 'Send us the prescription and we will give you a clear quotation. You pay nothing until you agree.'); ?>
  </div>
</section>

<?php g_cta_band('Ask for a quotation', '', [
    ['Request a Quotation', request_url('quotation'), 'primary', 'receipt'],
    ['Talk to Our Team', tel_url(), 'white', 'phone'],
]); ?>

<?php include INC . '/footer.php'; ?>
