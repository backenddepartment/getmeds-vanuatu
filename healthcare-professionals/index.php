<?php
/**
 * For Healthcare Professionals (content guide, page 11).
 *
 * Denser and navy-led: professionals scan for scope, process and contact. The
 * Request a Quotation form sits on this page (#quote), not only on Contact.
 * The old /providers pages redirect here.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/forms.php';

$page = [
    'title'     => 'For Healthcare Professionals',
    'seo_title' => 'For Doctors, Hospitals and Pharmacies — Getmeds Vanuatu',
    'desc'      => 'Getmeds Vanuatu supplies cancer, specialist and essential medicines to doctors, '
                 . 'hospitals, clinics and pharmacies in Vanuatu and the Pacific. Request a quotation.',
];

$quoteSchema = [
    'facility' => ['label' => 'Facility name', 'type' => 'text', 'required' => true],
    'contact'  => ['label' => 'Contact person', 'type' => 'text', 'required' => true],
    'role'     => ['label' => 'Role', 'type' => 'text'],
    'email'    => ['label' => 'Email', 'type' => 'email', 'required' => true],
    'phone'    => ['label' => 'Phone', 'type' => 'tel', 'required' => true],
    'country'  => ['label' => 'Country', 'type' => 'select', 'required' => true, 'options' => [
        'vanuatu' => 'Vanuatu', 'solomon' => 'Solomon Islands', 'fiji' => 'Fiji', 'samoa' => 'Samoa',
        'tonga' => 'Tonga', 'tuvalu' => 'Tuvalu', 'nauru' => 'Nauru', 'other' => 'Other Pacific country',
    ]],
    'products' => ['label' => 'Product list or protocol (upload)', 'type' => 'file',
                   'help' => 'Photo, scan or PDF of the prescription, treatment protocol or product list.'],
    'needed'   => ['label' => 'Date needed', 'type' => 'date'],
    'notes'    => ['label' => 'Notes', 'type' => 'textarea'],
    'consent'  => ['label' => 'Consent', 'type' => 'consent', 'required' => true,
                   'html' => 'I agree that Getmeds Vanuatu may use these details to reply to this request, as set out in the <a href="' . e(url('/privacy')) . '">Privacy Policy</a>.'],
];
$quote = gform_run('quotation', $quoteSchema, ['country' => 'vanuatu']);

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-hero--navy" aria-labelledby="hcp-h1">
  <div class="g-wrap">
    <div class="g-split g-split--7-5">
      <div>
        <h1 id="hcp-h1">For healthcare professionals</h1>
        <p class="g-hero__lede">A local source for cancer medicines, specialist medicines and medical supplies in Vanuatu and the Pacific. Let us quote for your requirement.</p>
        <div class="g-btns">
          <?= g_btn('Request a Quotation', '#quote', 'primary', 'receipt') ?>
          <?= g_call_btn('Talk to Our Pharmacist', 'white') ?>
        </div>
      </div>
      <div class="g-hero__media g-hcp-hero__media">
        <?= g_photo('notes', 'A doctor writing notes at a desk beside a stethoscope.', '', true) ?>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="who">
  <div class="g-wrap">
    <div class="g-head"><h2 id="who">Who we work with</h2></div>
    <?php g_table(['You are', 'How we can help'], [
        ['Doctors and GPs', 'Check if a prescribed medicine is available and supply it to your patient'],
        ['Oncologists and haematologists', 'Supply of cancer medicines by treatment protocol, cycle by cycle'],
        ['Hospitals', 'Supply to wards and treatment units, including injectable chemotherapy'],
        ['Clinics', 'Medicines, consumables and devices for your patients'],
        ['Pharmacists and pharmacies', 'Access to medicines you do not normally stock'],
        ['Procurement and purchasing teams', 'Quotations for medicines, supplies and equipment'],
        ['Overseas treatment agencies', 'Local medicine supply for patients who return home to continue treatment'],
    ]); ?>
  </div>
</section>

<section class="g-sec g-bg-mint" aria-labelledby="supply">
  <div class="g-wrap">
    <div class="g-head"><h2 id="supply">What we supply</h2></div>
    <ul class="g-hcp-supply" role="list">
      <?php foreach ([
          ['ribbon',    'Cancer medicines — our main specialisation'],
          ['drop',      'Haematology, hormonal and supportive-care medicines'],
          ['pill',      'Essential medicines, including antibiotics and cardiometabolic medicines'],
          ['kidney',    'Medicines for dialysis and heart disease'],
          ['box',       'Single-use medical consumables'],
          ['flask',     'Medical devices, equipment, laboratory supplies and contrast media'],
      ] as [$ic, $t]): ?>
      <li><span class="g-hcp-supply__icon"><?= gi($ic) ?></span><span><?= e($t) ?></span></li>
      <?php endforeach; ?>
    </ul>
    <p class="g-mt-lg">We source a wide range of products through flexible procurement. Some items may be limited by freight costs or the availability of export documents. We will tell you early if this applies.</p>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="onco">
  <div class="g-wrap">
    <div class="g-split">
      <div class="g-stack">
        <h2 id="onco">Oncology and chemotherapy supply</h2>
        <p>Send us the prescription or treatment protocol. We confirm which medicines are in stock, which must be imported and which must be sourced. We give you a quotation and a supply date for each cycle.</p>
        <p>We also plan supply ahead for patients on long treatment courses, and update supply when a protocol changes.</p>
      </div>
      <div class="g-diagram g-hcp-flow" role="img" aria-label="Process: protocol, then quote, then supply per cycle.">
        <div class="g-diagram__step" aria-hidden="true"><?= gi('clipboard') ?><strong>Protocol</strong></div>
        <div class="g-diagram__arrow" aria-hidden="true"><?= gi('arrow') ?></div>
        <div class="g-diagram__step" aria-hidden="true"><?= gi('receipt') ?><strong>Quote</strong></div>
        <div class="g-diagram__arrow" aria-hidden="true"><?= gi('arrow') ?></div>
        <div class="g-diagram__step" aria-hidden="true"><?= gi('refresh') ?><strong>Supply per cycle</strong></div>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-sec--tight g-bg-white g-hcp-nps" aria-labelledby="nps">
  <div class="g-wrap">
    <div class="g-card g-card--sky g-stack">
      <h2 id="nps" class="g-h3">Named Patient Supply</h2>
      <p>If your patient needs a medicine that is not normally available in Vanuatu, we help arrange it for that named patient. We handle sourcing, the import approval and the paperwork.</p>
      <p><a class="g-link-arrow" href="<?= e(url('/named-patient-supply')) ?>">How Named Patient Supply works <?= gi('arrow') ?></a></p>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-hcp-submit" aria-labelledby="submit">
  <div class="g-wrap">
    <div class="g-head"><h2 id="submit">How to submit a request</h2></div>
    <?php g_steps([
        ['', 'Send the prescription, treatment protocol or product list by email or the form below.'],
        ['', 'Include the patient or facility name, quantities and the date needed.'],
        ['', 'Our pharmacist reviews the request.'],
        ['', 'We send a quotation with availability and timing.'],
        ['', 'You confirm the order. We source, receive, supply and record it.'],
    ], 'h'); ?>

    <div class="g-card g-hcp-form g-mt-lg">
      <h3>Request a Quotation</h3>
      <?php gform_render('quotation', $quoteSchema, $quote, [
          'id'      => 'quote',
          'submit'  => 'Request a Quotation',
          'success' => 'Thank you. We have received your request for a quotation.',
          'warn'    => true,
      ]); ?>
    </div>
  </div>
</section>

<section class="g-sec g-bg-navy g-wave g-hcp-expect" aria-labelledby="expect">
  <div class="g-wrap">
    <div class="g-head g-head--center"><h2 id="expect">What you can expect</h2></div>
    <div class="g-grid g-grid--3">
      <?php foreach ([
          ['box',    'Availability', 'A wide range of products, reliable availability, fewer stock-outs and fast order fulfilment'],
          ['shield', 'Assurance', 'Consistent service, accurate orders, professional communication and reliable follow-up'],
          ['star',   'Advantage', 'Affordable or competitive prices, flexible solutions and long-term partnership'],
      ] as [$ic, $t, $d]): ?>
      <div class="g-hcp-a">
        <span class="g-hcp-a__icon"><?= gi($ic) ?></span>
        <h3><?= e($t) ?></h3>
        <p><?= e($d) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="g-hcp-partner">
      <?= gi('users') ?>
      <p><strong>Partnership</strong> We work with you as a long-term partner.</p>
    </div>
  </div>
</section>

<?php g_cta_band('Let us quote for your requirement', '', [
    ['Request a Quotation', '#quote', 'primary', 'receipt'],
    ['Healthcare Professional Enquiry', request_url('professional'), 'white', 'mail'],
]); ?>

<?php include INC . '/footer.php'; ?>
