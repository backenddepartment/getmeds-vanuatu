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
                   'html' => 'I agree that Getmeds Vanuatu may use these details to reply to this request, as set out in the <a href="' . e(url('/privacy')) . '" data-modal>Privacy Policy</a>.'],
];
$quote = gform_run('quotation', $quoteSchema, ['country' => 'vanuatu']);

include INC . '/head.php';
include INC . '/header.php';
?>

<?php /* The first two sections share one motion system (guide.css "Healthcare
         Professionals", guide.js 7f): parts marked data-rv rise in as they
         reach the screen, with data-d as their place in the stagger. Without
         JS, or with reduced motion, everything simply shows. */ ?>

<?php /* Hero (white): eyebrow and title on the left; lede, text and buttons
         on the right (.hx-hero--split), styled as the Named Patient Supply
         hero (.g-nps-hero). The header is solid. */ ?>
<section class="hx hx-hero hx-hero--split g-nps-hero" aria-labelledby="hcp-h1">
  <div class="g-wrap hx-hero__grid">
    <div class="hx-hero__text">
      <span class="g-label" data-rv style="--d:0">Doctors, Hospitals, Clinics and Pharmacies</span>
      <h1 id="hcp-h1">
        <span class="hx-mask" data-rv style="--d:1"><span>For Healthcare</span></span>
        <span class="hx-mask" data-rv style="--d:2"><span>Professionals</span></span>
      </h1>
      <ul class="hx-hero__trust" data-rv style="--d:3">
        <li><?= gi('check-circle') ?>Named-patient requests</li>
        <li><?= gi('check-circle') ?>Regular and tender orders</li>
        <li><?= gi('check-circle') ?>Vanuatu and the Pacific</li>
      </ul>
    </div>

    <div class="hx-hero__body">
      <p class="g-hero__lede" data-rv style="--d:3">A local source for cancer medicines, specialist medicines and medical supplies in Vanuatu and the Pacific.</p>
      <p class="g-nps-simple" data-rv style="--d:4">Getmeds Vanuatu works with doctors, hospitals, clinics and pharmacies to source the medicines your patients need, including oncology treatments, hard-to-find specialist medicines and essential supplies. Send us a prescription, treatment protocol or product list and our pharmacist will confirm availability, price and delivery time before you order. We supply single named-patient requests as well as regular and tender orders.</p>
      <div class="hx-hero__btns" data-rv style="--d:5">
        <a class="g-btn g-btn--grad" href="#quote"><span>Request a Quotation</span></a>
        <?= g_btn('Talk to Our Pharmacist', tel_url(), 'secondary') ?>
      </div>
    </div>
  </div>
</section>

<?php /* What we supply and Who we work with, side by side on one blue band:
         the supply list on the left, the three groups of partners on the
         right, each group with its partner tags and a rule that draws in
         under it. */
$supply = [
    ['ribbon', 'Cancer medicines', 'Oncology and chemotherapy medicines, supplied by prescription or treatment protocol.'],
    ['drop',  'Haematology, hormonal and supportive-care medicines', ''],
    ['pill',  'Essential medicines', 'Including antibiotics and cardiometabolic medicines.'],
    ['heart', 'Medicines for dialysis and heart disease', ''],
    ['syringe', 'Single-use medical consumables', ''],
    ['flask', 'Medical devices, equipment, laboratory supplies and contrast media', ''],
];
$partners = [
    [[['doctor', 'Doctors and GPs'], ['ribbon', 'Oncologists'], ['drop', 'Haematologists']],
     'Doctors and GPs ask us to check whether a prescribed medicine is available and to supply it to their patient. Oncologists and haematologists rely on us for cancer medicines supplied by treatment protocol, cycle by cycle.'],
    [[['hospital', 'Hospitals'], ['building', 'Clinics'], ['pharmacist', 'Pharmacies']],
     'Hospitals order supply for wards and treatment units, including injectable chemotherapy, and clinics come to us for the medicines, consumables and devices their patients need. Pharmacists and pharmacies use us for access to medicines they do not normally stock.'],
    [[['receipt', 'Procurement teams'], ['plane', 'Overseas treatment agencies']],
     'We also prepare quotations for procurement and purchasing teams covering medicines, supplies and equipment, and arrange local supply for overseas treatment agencies whose patients return home to continue treatment.'],
]; ?>
<section class="hx hx-who" aria-labelledby="supply who">
  <div class="g-wrap hx-who__grid">
    <div class="hx-who__supply">
      <span class="hx-kicker" data-rv>Products and Supplies</span>
      <h2 id="supply" class="hx-mask" data-rv style="--d:1"><span>What we supply</span></h2>
      <p class="hx-who__lede" data-rv style="--d:2">We source a wide range of products through flexible procurement. Some items may be limited by freight costs or the availability of export documents. We will tell you early if this applies.</p>
      <ul class="hx-supply-list">
        <?php foreach ($supply as $i => [$icon, $title, $note]): ?>
        <li data-rv style="--d:<?= $i ?>">
          <span class="hx-supply-list__icon" aria-hidden="true"><?= gi($icon) ?></span>
          <div>
            <h3><?= e($title) ?></h3>
            <?php if ($note !== ''): ?><p><?= e($note) ?></p><?php endif; ?>
            <?php if ($icon === 'ribbon'): ?><a class="hx-supply-list__link" href="<?= e(url('/oncology')) ?>">Cancer care<?= gi('arrow') ?></a><?php endif; ?>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="hx-who__text">
      <span class="hx-kicker" data-rv>Our Partners</span>
      <h2 id="who" class="hx-mask" data-rv style="--d:1"><span>Who we work with</span></h2>
      <ol class="hx-rows">
        <?php foreach ($partners as $i => [$tags, $text]): ?>
        <li class="hx-row" data-rv style="--d:<?= $i ?>">
          <div>
            <ul class="hx-tags">
              <?php foreach ($tags as [, $tag]): ?>
              <li><?= e($tag) ?></li>
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
