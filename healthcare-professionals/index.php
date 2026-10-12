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

<?php /* Full-screen photo hero, same build as the home page (guide.css
         "Home hero"): the navbar sits see-through over the photo
         (includes/header.php), headline left, lede and buttons right, then a
         rule and the kicker. .g-hcphero adds a navy gradient over the grey
         photo so the white text stays readable. */ ?>
<section class="g-homehero g-homehero--split g-hcphero" aria-labelledby="hcp-h1">
  <div class="g-homehero__media">
    <picture>
      <source type="image/webp" srcset="<?= e(asset('/assets/img/photo/notes-800.webp')) ?> 800w, <?= e(asset('/assets/img/photo/notes-1280.webp')) ?> 1280w" sizes="100vw">
      <img src="<?= e(asset('/assets/img/photo/notes-1280.jpg')) ?>"
           srcset="<?= e(asset('/assets/img/photo/notes-800.jpg')) ?> 800w, <?= e(asset('/assets/img/photo/notes-1280.jpg')) ?> 1280w" sizes="100vw"
           width="1280" height="854" alt="" loading="eager" fetchpriority="high" decoding="async">
    </picture>
  </div>
  <div class="g-wrap g-homehero__inner">
    <div class="g-homehero__row">
      <h1 class="g-homehero__title" id="hcp-h1"><span>For healthcare</span> <span>professionals</span></h1>
      <div class="g-homehero__side">
        <p class="g-homehero__lede">A local source for cancer medicines, specialist medicines and medical supplies in Vanuatu and the Pacific. Let us quote for your requirement.</p>
        <div class="g-homehero__btns">
          <a class="g-homehero__btn g-homehero__btn--fill" href="#quote">Request a Quotation</a>
          <a class="g-homehero__btn g-homehero__btn--line" href="<?= e(tel_url()) ?>">Talk to Our Pharmacist</a>
        </div>
      </div>
    </div>
    <div class="g-homehero__foot">
      <p class="g-homehero__kicker">Licensed pharmacy · <?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></p>
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

<section class="g-sec g-bg-white" aria-labelledby="supply">
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
