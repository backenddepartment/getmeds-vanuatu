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
    'facility' => ['label' => 'Facility name', 'type' => 'text', 'required' => true,
                   'placeholder' => 'e.g. Vila Central Hospital'],
    'contact'  => ['label' => 'Contact person', 'type' => 'text', 'required' => true,
                   'placeholder' => 'e.g. Dr Marie Kalo'],
    'role'     => ['label' => 'Role', 'type' => 'text',
                   'placeholder' => 'e.g. Chief Pharmacist'],
    'email'    => ['label' => 'Email', 'type' => 'email', 'required' => true,
                   'placeholder' => 'e.g. pharmacy@hospital.vu'],
    'phone'    => ['label' => 'Phone', 'type' => 'tel', 'required' => true,
                   'placeholder' => 'e.g. +678 555 1234'],
    'country'  => ['label' => 'Country', 'type' => 'select', 'required' => true, 'options' => [
        'vanuatu' => 'Vanuatu', 'solomon' => 'Solomon Islands', 'fiji' => 'Fiji', 'samoa' => 'Samoa',
        'tonga' => 'Tonga', 'tuvalu' => 'Tuvalu', 'nauru' => 'Nauru', 'other' => 'Other Pacific country',
    ]],
    'products' => ['label' => 'Product list or protocol (upload)', 'type' => 'file',
                   'help' => 'Photo, scan or PDF of the prescription, treatment protocol or product list.'],
    'needed'   => ['label' => 'Date needed', 'type' => 'date'],
    'notes'    => ['label' => 'Notes', 'type' => 'textarea',
                   'placeholder' => 'e.g. Paclitaxel 100 mg x 6 vials, preferred brand, deliver to Vila Central Hospital, tender ref 2026-14'],
    'consent'  => ['label' => 'Consent', 'type' => 'consent', 'required' => true,
                   'html' => 'I agree that Getmeds Vanuatu may use these details to reply to this request, as set out in the <a href="' . e(url('/privacy')) . '">Privacy Policy</a>.'],
];
$quote = gform_run('quotation', $quoteSchema, ['country' => 'vanuatu']);

include INC . '/head.php';
include INC . '/header.php';
?>

<?php /* Page intro: eyebrow, title and lede, type as the Named Patient
         Supply hero (guide.css .g-nps-hero). The header is solid here. */ ?>
<section class="g-bg-white g-pg g-nps-hero g-hcp-hero" aria-labelledby="hcp-h1">
  <div class="g-wrap">
    <div class="g-hcp-hero__text">
      <span class="g-label">Doctors, Hospitals, Clinics and Pharmacies</span>
      <h1 id="hcp-h1">For Healthcare Professionals</h1>
      <p class="g-hero__lede">A local source for cancer medicines, specialist medicines and medical supplies in Vanuatu and the Pacific.</p>
      <p class="g-nps-simple">Getmeds Vanuatu works with doctors, hospitals, clinics and pharmacies to source the medicines your patients need, including oncology treatments, hard-to-find specialist medicines and essential supplies. Send us a prescription, treatment protocol or product list and our pharmacist will confirm availability, price and delivery time before you order. We supply single named-patient requests as well as regular and tender orders.</p>
    </div>
  </div>
</section>

<?php /* Same layout as the oncology page's "How we supply cancer medicines"
         (guide.css "g-onc-supply"): eyebrow, title and text on the left, the
         six supply areas stacked as blue cards with outline icons on the right. */ ?>
<section class="g-sec g-bg-white g-onc-supply g-hcp-supplysec" aria-labelledby="supply">
  <div class="g-wrap g-onc-supply__split">
    <div class="g-onc-supply__intro">
      <span class="g-label">Products and Supplies</span>
      <h2 id="supply">What we supply</h2>
      <p>We source a wide range of products through flexible procurement. Some items may be limited by freight costs or the availability of export documents. We will tell you early if this applies.</p>
    </div>
    <div class="g-onc-supply__panel">
      <?php foreach ([
          ['ribbon',    'Cancer medicines — our main specialisation'],
          ['drop',      'Haematology, hormonal and supportive-care medicines'],
          ['pill',      'Essential medicines, including antibiotics and cardiometabolic medicines'],
          ['kidney',    'Medicines for dialysis and heart disease'],
          ['box',       'Single-use medical consumables'],
          ['flask',     'Medical devices, equipment, laboratory supplies and contrast media'],
      ] as [$ic, $t]): ?>
      <?= g_card($ic, $t, '') ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="who">
  <div class="g-wrap">
    <div class="g-split g-who-split">
      <div class="g-stack">
        <span class="g-label">Our Partners</span>
        <h2 id="who">Who we work with</h2>
        <p>Doctors and GPs ask us to check whether a prescribed medicine is available and to supply it to their patient. Oncologists and haematologists rely on us for cancer medicines supplied by treatment protocol, cycle by cycle.</p>
        <p>Hospitals order supply for wards and treatment units, including injectable chemotherapy, and clinics come to us for the medicines, consumables and devices their patients need. Pharmacists and pharmacies use us for access to medicines they do not normally stock.</p>
        <p>We also prepare quotations for procurement and purchasing teams covering medicines, supplies and equipment, and arrange local supply for overseas treatment agencies whose patients return home to continue treatment.</p>
      </div>
      <figure class="g-who-split__media">
        <img src="<?= e(asset('/assets/img/ordermedicinehospitals.jpg')) ?>"
             alt="Medicines being prepared for a hospital order" loading="lazy">
      </figure>
    </div>
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

<?php /* Blue-gradient band, same style as the About "Medicine Safety" band:
         eyebrow and title left, the three points stacked right. */ ?>
<section class="g-sec g-about-safe" aria-labelledby="expect">
  <div class="g-wrap g-split g-split--top">
    <div>
      <span class="g-label">Working With Us</span>
      <h2 id="expect">What you can expect</h2>
    </div>
    <div class="g-about-safe__text">
      <?php foreach ([
          ['Availability', 'A wide range of products, reliable availability, fewer stock-outs and fast order fulfilment.'],
          ['Assurance', 'Consistent service, accurate orders, professional communication and reliable follow-up.'],
          ['Advantage', 'Affordable or competitive prices, flexible solutions and long-term partnership.'],
      ] as [$t, $d]): ?>
      <div class="g-expect__item">
        <h3><?= e($t) ?></h3>
        <p><?= e($d) ?></p>
      </div>
      <?php endforeach; ?>
      <p class="g-expect__note"><strong>Partnership</strong> &mdash; we work with you as a long-term partner.</p>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
