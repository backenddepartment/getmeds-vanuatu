<?php
/**
 * /named-patient-supply — Content & Design Guide, page 05.
 *
 * Explains a new idea to people who have never heard of it: lots of white
 * space, one idea per section, plain icons, and an "In simple words" box right
 * under the hero. The old /patients/named-patient-access redirects here.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Named Patient Supply',
    'seo_title' => 'Named Patient Supply in Vanuatu — Getmeds Vanuatu',
    'desc'      => 'Named Patient Supply helps a patient get a prescribed medicine that is not normally available in Vanuatu. Learn how it works and how Getmeds can help.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-hero--sky g-pg">
  <div class="g-wrap g-split g-split--7-5">
    <div>
      <h1>Named Patient Supply</h1>
      <p class="g-hero__lede">Getting a medicine that is not normally available in Vanuatu, for one named patient.</p>
      <div class="g-btns">
        <?= g_btn('Make a Named Patient Supply Enquiry', request_url('nps')) ?>
        <?= g_call_btn('Call Us') ?>
      </div>
    </div>
    <div class="g-hero__media g-nps-illus">
      <svg viewBox="0 0 360 280" role="img" aria-label="Illustration of a doctor's prescription and a medicine box with a name tag attached">
        <!-- prescription -->
        <g transform="rotate(-6 110 140)">
          <rect x="30" y="40" width="150" height="200" rx="12" fill="#fff" stroke="#DCE6EE" stroke-width="2"/>
          <text x="50" y="84" font-family="Poppins, sans-serif" font-size="30" font-weight="700" fill="#1E9BD7">Rx</text>
          <rect x="50" y="100" width="110" height="8" rx="4" fill="#DCE6EE"/>
          <rect x="50" y="118" width="90" height="8" rx="4" fill="#DCE6EE"/>
          <rect x="50" y="136" width="104" height="8" rx="4" fill="#DCE6EE"/>
          <rect x="50" y="154" width="70" height="8" rx="4" fill="#DCE6EE"/>
          <path d="M52 206c10-14 18 8 28-4s16 6 26-4" fill="none" stroke="#0B2A5B" stroke-width="3" stroke-linecap="round"/>
          <rect x="50" y="214" width="80" height="3" rx="1.5" fill="#0B2A5B" opacity=".4"/>
        </g>
        <!-- medicine box -->
        <rect x="176" y="118" width="130" height="130" rx="12" fill="#1E9BD7"/>
        <path d="M176 152h130" stroke="#fff" stroke-width="3" opacity=".5"/>
        <rect x="196" y="176" width="90" height="40" rx="8" fill="#fff"/>
        <path d="M241 184v24M229 196h24" stroke="#6BB33F" stroke-width="6" stroke-linecap="round"/>
        <!-- string and name tag -->
        <path d="M300 126c24-6 30-30 22-48" fill="none" stroke="#0B2A5B" stroke-width="2.5"/>
        <g transform="rotate(12 300 60)">
          <path d="M262 40h66a8 8 0 0 1 8 8v28a8 8 0 0 1-8 8h-66l-16-22z" fill="#EEF7F2" stroke="#6BB33F" stroke-width="3"/>
          <circle cx="262" cy="62" r="4" fill="#6BB33F"/>
          <rect x="274" y="54" width="52" height="7" rx="3.5" fill="#0B2A5B"/>
          <rect x="274" y="67" width="36" height="6" rx="3" fill="#0B2A5B" opacity=".45"/>
        </g>
      </svg>
    </div>
  </div>
</section>

<section class="g-sec g-sec--tight g-bg-white" aria-label="In simple words">
  <div class="g-wrap">
    <div class="g-box g-box--summary">
      <p><strong>In simple words:</strong> If your doctor prescribes a medicine that Vanuatu does not normally have, Getmeds can try to bring it in just for you. We need your prescription. We tell you the price and time before you pay.</p>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-nps-first" aria-labelledby="mean-h">
  <div class="g-wrap g-read g-stack">
    <h2 id="mean-h">What does Named Patient Supply mean?</h2>
    <p>Some medicines are not usually sold or stocked in Vanuatu. A doctor may still decide a patient needs one of them.</p>
    <p>Named Patient Supply means the medicine is ordered for one patient, by name, based on their doctor's prescription. It is not ordered for general sale.</p>
  </div>
</section>

<section class="g-sec g-bg-mint" aria-labelledby="why-h">
  <div class="g-wrap g-read g-stack">
    <span class="g-card__icon g-nps-bigicon"><?= gi('island') ?></span>
    <h2 id="why-h">Why does it exist?</h2>
    <p>Vanuatu is a small market. Many specialist medicines, including many cancer medicines, are not kept in stock. Named Patient Supply gives patients a way to get the medicine their doctor prescribed, even when it is not normally available here.</p>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="when-h">
  <div class="g-wrap g-narrow">
    <div class="g-head">
      <h2 id="when-h">When may it be used?</h2>
    </div>
    <ul class="g-grid g-grid--2 g-nps-bullets" role="list">
      <?php foreach ([
          'When a medicine is not available in Vanuatu.',
          'When a medicine is often out of stock.',
          'When a patient returns from treatment overseas and needs to continue a medicine at home.',
          'When a doctor prescribes a specialist medicine for a serious condition.',
      ] as $b): ?>
      <li class="g-card g-card--compact g-nps-bullet"><span class="g-nps-dot" aria-hidden="true"></span><span><?= e($b) ?></span></li>
      <?php endforeach; ?>
    </ul>
    <div class="g-mt">
      <?php g_box('info', 'Not every medicine can be supplied this way. Some medicines cannot be exported by the maker, or need papers that are not available. We will tell you honestly what is possible.'); ?>
    </div>
  </div>
</section>

<section class="g-sec g-sec--tight g-bg-white" aria-labelledby="rx-h">
  <div class="g-wrap g-narrow">
    <div class="g-box g-box--safety g-nps-rx">
      <?= gi('clipboard') ?>
      <div>
        <h2 id="rx-h" class="g-nps-rx__h">The prescription comes first</h2>
        <p>Every Named Patient Supply request needs a valid prescription or treatment protocol from the patient's doctor. For your safety, we only supply the medicine your doctor prescribed.</p>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="need-h">
  <div class="g-wrap g-read">
    <div class="g-head">
      <h2 id="need-h">What information do we need?</h2>
    </div>
    <?php g_check([
        'The patient\'s full name and contact details',
        'The doctor\'s prescription or treatment protocol',
        'The medicine name, strength and amount',
        'The doctor\'s or hospital\'s name and contact details',
        'The date the medicine is needed (for example, the next treatment cycle)',
        'Any medical papers needed for the import approval',
    ], 'doc', 'file'); ?>
    <p class="g-pg-mt"><strong>If you do not have everything, contact us anyway. We will tell you what is missing.</strong></p>
  </div>
</section>

<section class="g-sec g-bg-mint" aria-labelledby="how-h">
  <div class="g-wrap">
    <div class="g-head g-head--center">
      <h2 id="how-h">How it works</h2>
    </div>
    <?php g_steps([
        ['Enquiry.', 'The patient, doctor or hospital contacts Getmeds.'],
        ['Prescription check.', 'Our pharmacist reviews the prescription or protocol.'],
        ['Sourcing.', 'We find a supplier and check the paperwork needed.'],
        ['Quotation.', 'We tell you the price and expected timing before you commit.'],
        ['Approval and import.', 'We arrange the import approval and order the medicine.'],
        ['Supply.', 'The medicine is dispensed to the patient, or prepared for the hospital for injectable treatment.'],
    ], 'rows3'); ?>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="helps-h">
  <div class="g-wrap">
    <div class="g-head g-head--center">
      <h2 id="helps-h">How Getmeds helps</h2>
    </div>
    <ul class="g-grid g-grid--4 g-nps-helps" role="list">
      <?php foreach ([
          ['doctor',     'We work with the doctor to confirm what is needed.'],
          ['clip-check', 'We handle sourcing and import paperwork.'],
          ['chat',       'We keep the patient and doctor updated.'],
          ['calendar',   'We plan ahead for the next treatment cycle.'],
      ] as [$ic, $t]): ?>
      <li class="g-card"><span class="g-card__icon"><?= gi($ic) ?></span><p><?= e($t) ?></p></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="g-sec g-bg-sky" aria-labelledby="expect-h">
  <div class="g-wrap g-read">
    <div class="g-head">
      <h2 id="expect-h">What patients can expect</h2>
    </div>
    <?php g_check([
        'A clear answer about whether we can source the medicine.',
        'A price before you pay anything.',
        'An estimated time. This can be longer than for medicines we keep in stock.',
        'A pharmacist to explain how to take or handle your medicine.',
    ]); ?>
  </div>
</section>

<?php g_cta_band(
    'Ask about Named Patient Supply',
    'Send us the prescription and we will check what is possible.',
    [
        ['Make a Named Patient Supply Enquiry', request_url('nps'), 'primary'],
        ['Contact Us', url('/contact'), 'white'],
    ]
); ?>

<?php include INC . '/footer.php'; ?>
