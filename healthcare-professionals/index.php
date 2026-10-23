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

<?php /* The first four sections share one motion system (guide.css "Healthcare
         Professionals", guide.js 7f): parts marked data-rv rise in as they
         reach the screen, with data-d as their place in the stagger. Without
         JS, or with reduced motion, everything simply shows. */ ?>

<?php /* Hero: text left; on the right two blue-toned photos with a floating
         "before you order" card and a pharmacist badge. The header is solid. */ ?>
<section class="hx hx-hero" aria-labelledby="hcp-h1">
  <div class="hx-hero__bg" aria-hidden="true"><span class="hx-orb hx-orb--a"></span><span class="hx-orb hx-orb--b"></span></div>
  <div class="g-wrap hx-hero__grid">
    <div class="hx-hero__text">
      <span class="hx-eyebrow" data-rv style="--d:0"><span class="hx-eyebrow__dot" aria-hidden="true"></span>Doctors, Hospitals, Clinics and Pharmacies</span>
      <h1 id="hcp-h1" class="hx-hero__title">
        <span class="hx-mask" data-rv style="--d:1"><span>For Healthcare</span></span>
        <span class="hx-mask" data-rv style="--d:2"><span class="hx-grad">Professionals</span></span>
      </h1>
      <p class="hx-hero__lede" data-rv style="--d:3">A local source for cancer medicines, specialist medicines and medical supplies in Vanuatu and the Pacific.</p>
      <p class="hx-hero__body" data-rv style="--d:4">Getmeds Vanuatu works with doctors, hospitals, clinics and pharmacies to source the medicines your patients need, including oncology treatments, hard-to-find specialist medicines and essential supplies. Send us a prescription, treatment protocol or product list and our pharmacist will confirm availability, price and delivery time before you order. We supply single named-patient requests as well as regular and tender orders.</p>
      <div class="hx-hero__btns" data-rv style="--d:5">
        <a class="g-btn g-btn--grad" href="#quote"><span>Request a Quotation</span><?= gi('arrow') ?></a>
        <?= g_call_btn('Talk to Our Pharmacist', 'secondary') ?>
      </div>
      <ul class="hx-hero__trust" data-rv style="--d:6">
        <li><?= gi('check-circle') ?>Named-patient requests</li>
        <li><?= gi('check-circle') ?>Regular and tender orders</li>
        <li><?= gi('check-circle') ?>Vanuatu and the Pacific</li>
      </ul>
    </div>

    <div class="hx-hero__media" aria-hidden="true">
      <span class="hx-ph hx-ph--a" data-rv="clip" style="--d:2"><img src="<?= e(asset('/assets/img/photo/dispensary-800.jpg')) ?>" alt="" width="800" height="1200" loading="eager" fetchpriority="high" decoding="async"></span>
      <span class="hx-ph hx-ph--b" data-rv="clip" style="--d:4"><img src="<?= e(asset('/assets/img/photo/vialtray-800.jpg')) ?>" alt="" width="800" height="533" loading="eager" decoding="async"></span>
      <div class="hx-float hx-float--card" data-rv style="--d:6">
        <p class="hx-float__h">Before you order</p>
        <ul>
          <li><span class="hx-tick"><?= gi('check') ?></span>Availability</li>
          <li><span class="hx-tick"><?= gi('check') ?></span>Price</li>
          <li><span class="hx-tick"><?= gi('check') ?></span>Delivery time</li>
        </ul>
      </div>
      <div class="hx-float hx-float--badge" data-rv style="--d:7">
        <span class="hx-float__icon"><?= gi('pharmacist') ?></span>
        <span><strong>Pharmacist</strong> confirms every request</span>
      </div>
    </div>
  </div>
</section>

<?php /* What we supply: heading row, then a bento grid. Cancer medicines, the
         main specialisation, is the tall navy card; the rest light up under
         the pointer. */
$supply = [
    ['drop',  'Haematology, hormonal and supportive-care medicines', ''],
    ['pill',  'Essential medicines', 'Including antibiotics and cardiometabolic medicines.'],
    ['heart', 'Medicines for dialysis and heart disease', ''],
    ['syringe', 'Single-use medical consumables', ''],
]; ?>
<section class="hx hx-supply" aria-labelledby="supply">
  <div class="g-wrap">
    <div class="hx-head">
      <div>
        <span class="hx-kicker" data-rv>Products and Supplies</span>
        <h2 id="supply" class="hx-mask" data-rv style="--d:1"><span>What we supply</span></h2>
      </div>
      <p data-rv style="--d:2">We source a wide range of products through flexible procurement. Some items may be limited by freight costs or the availability of export documents. We will tell you early if this applies.</p>
    </div>

    <ul class="hx-bento">
      <li class="hx-card hx-card--lead" data-rv style="--d:0">
        <span class="hx-card__glyph" aria-hidden="true"><?= gi('ribbon') ?></span>
        <div>
          <span class="hx-card__icon"><?= gi('ribbon') ?></span>
          <span class="hx-card__tag">Our main specialisation</span>
        </div>
        <div>
          <h3>Cancer medicines</h3>
          <p>Oncology and chemotherapy medicines, supplied by prescription or treatment protocol.</p>
          <a class="hx-card__link" href="<?= e(url('/oncology')) ?>">Cancer care<?= gi('arrow') ?></a>
        </div>
      </li>
      <?php foreach ($supply as $i => [$icon, $title, $note]): ?>
      <li class="hx-card" data-rv style="--d:<?= $i + 1 ?>">
        <span class="hx-card__no" aria-hidden="true">0<?= $i + 2 ?></span>
        <span class="hx-card__icon"><?= gi($icon) ?></span>
        <div>
          <h3><?= e($title) ?></h3>
          <?php if ($note !== ''): ?><p><?= e($note) ?></p><?php endif; ?>
        </div>
      </li>
      <?php endforeach; ?>
      <li class="hx-card hx-card--wide" data-rv style="--d:5">
        <span class="hx-card__no" aria-hidden="true">06</span>
        <span class="hx-card__icon"><?= gi('flask') ?></span>
        <h3>Medical devices, equipment, laboratory supplies and contrast media</h3>
      </li>
    </ul>
  </div>
</section>

<?php /* Who we work with: the photo stays in view (sticky) while the three
         groups of partners scroll past, each with its partner tags and a rule
         that draws in under it. */
$partners = [
    [[['doctor', 'Doctors and GPs'], ['ribbon', 'Oncologists'], ['drop', 'Haematologists']],
     'Doctors and GPs ask us to check whether a prescribed medicine is available and to supply it to their patient. Oncologists and haematologists rely on us for cancer medicines supplied by treatment protocol, cycle by cycle.'],
    [[['hospital', 'Hospitals'], ['building', 'Clinics'], ['pharmacist', 'Pharmacies']],
     'Hospitals order supply for wards and treatment units, including injectable chemotherapy, and clinics come to us for the medicines, consumables and devices their patients need. Pharmacists and pharmacies use us for access to medicines they do not normally stock.'],
    [[['receipt', 'Procurement teams'], ['plane', 'Overseas treatment agencies']],
     'We also prepare quotations for procurement and purchasing teams covering medicines, supplies and equipment, and arrange local supply for overseas treatment agencies whose patients return home to continue treatment.'],
]; ?>
<section class="hx hx-who" aria-labelledby="who">
  <div class="g-wrap hx-who__grid">
    <figure class="hx-who__media">
      <span class="hx-who__img" data-rv="clip">
        <img src="<?= e(asset('/assets/img/ordermedicinehospitals.jpg')) ?>" alt="Medicines being prepared for a hospital order" loading="lazy" decoding="async" data-par="40">
      </span>
      <figcaption class="hx-who__cap" data-rv style="--d:3"><?= gi('hospital') ?>Medicines being prepared for a hospital order</figcaption>
    </figure>
    <div class="hx-who__text">
      <span class="hx-kicker" data-rv>Our Partners</span>
      <h2 id="who" class="hx-mask" data-rv style="--d:1"><span>Who we work with</span></h2>
      <ol class="hx-rows">
        <?php foreach ($partners as $i => [$tags, $text]): ?>
        <li class="hx-row" data-rv style="--d:<?= $i ?>">
          <span class="hx-row__no" aria-hidden="true">0<?= $i + 1 ?></span>
          <div>
            <ul class="hx-tags">
              <?php foreach ($tags as [$icon, $tag]): ?>
              <li><?= gi($icon) ?><?= e($tag) ?></li>
              <?php endforeach; ?>
            </ul>
            <p><?= e($text) ?></p>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>

<?php /* Cancer care: a deep navy band. Text left; on the right the three steps
         on a rail that fills as the band scrolls past, the last step's icon
         turning to show it repeats each cycle. */
$flow = [
    ['clipboard', 'Protocol', 'Send us the prescription or treatment protocol.'],
    ['receipt',   'Quote',    'In stock, imported or sourced: a quotation and a supply date for each cycle.'],
    ['refresh',   'Supply per cycle', 'Planned ahead for long treatment courses, and updated when a protocol changes.'],
]; ?>
<section class="hx hx-onc" aria-labelledby="onco">
  <div class="hx-onc__bg" aria-hidden="true"></div>
  <div class="g-wrap hx-onc__grid">
    <div class="hx-onc__text">
      <span class="hx-kicker" data-rv>Cancer Care</span>
      <h2 id="onco" data-rv style="--d:1">Oncology and chemotherapy supply</h2>
      <p data-rv style="--d:2">Send us the prescription or treatment protocol. We confirm which medicines are in stock, which must be imported and which must be sourced. We give you a quotation and a supply date for each cycle.</p>
      <p data-rv style="--d:3">We also plan supply ahead for patients on long treatment courses, and update supply when a protocol changes.</p>
    </div>
    <div class="hx-flow">
    <span class="hx-flow__rail" aria-hidden="true"><span class="hx-flow__fill"></span></span>
    <ol class="hx-flow__list" aria-label="Process: protocol, then quote, then supply per cycle.">
      <?php foreach ($flow as $i => [$icon, $label, $text]): ?>
      <li class="hx-step" data-rv style="--d:<?= $i * 2 ?>">
        <span class="hx-step__node<?= $icon === 'refresh' ? ' hx-step__node--spin' : '' ?>" aria-hidden="true"><?= gi($icon) ?></span>
        <div class="hx-step__card">
          <span class="hx-step__no">Step 0<?= $i + 1 ?></span>
          <h3><?= e($label) ?></h3>
          <p><?= e($text) ?></p>
          <?php if ($icon === 'refresh'): ?><span class="hx-step__loop"><?= gi('refresh') ?>Repeats every cycle</span><?php endif; ?>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>
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
