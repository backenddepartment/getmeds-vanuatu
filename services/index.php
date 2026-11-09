<?php
/**
 * Services (content guide, page 13).
 *
 * An overview: H1 and intro, then eight expandable service cards (two columns
 * on desktop, one on mobile), followed by one clear site-wide call to action.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Our Services',
    'seo_title' => 'Our Services — Getmeds Vanuatu',
    'desc'      => 'Cancer medicine supply, Named Patient Supply, medicine sourcing, essential medicines, '
                 . 'medical supplies and health checks from Getmeds Vanuatu in Port Vila.',
];

// [id, icon, name, one line, details, who, how (array = arrow steps, string = one line), cta label, cta href, extra class]
$services = [
    ['cancer-medicine-supply', 'bottle', 'Cancer medicine supply',
     'Cancer and chemotherapy medicines in Port Vila.',
     'Our main work. Medicines in stock, for import, or sourced on request, supplied against a prescription or treatment protocol.',
     'Patients, oncologists, hospitals',
     ['Send the prescription', 'pharmacist check', 'quotation', 'supply'],
     'Request a Cancer Medicine', request_url('cancer'), 'g-card--pink-top'],
    ['treatment-cycle-planning', 'calendar', 'Treatment cycle planning',
     'Your medicine ready for every cycle.',
     'We schedule pick-up, explain your medicine and plan the next cycle. We update supply if the protocol changes.',
     'Patients on ongoing treatment',
     'Set up with your first order, then we contact you before each cycle.',
     'Patient Enquiry', request_url('patient'), ''],
    ['named-patient-supply', 'user-tag', 'Named Patient Supply',
     'Medicines not normally available in Vanuatu, for one named patient.',
     'We help arrange the medicine, the import approval and the paperwork.',
     'Patients and their doctors',
     ['Prescription', 'sourcing', 'quotation', 'import', 'supply'],
     'Named Patient Supply Enquiry', request_url('nps'), ''],
    ['medicine-sourcing-and-import', 'globe', 'Medicine sourcing and import',
     'Hard-to-find medicines, found for you.',
     'Flexible procurement through our suppliers and the wider Getmeds group. Freight costs and export papers may affect some items; we tell you first.',
     'Patients, pharmacies, hospitals',
     ['Enquiry', 'quotation', 'order confirmation', 'sourcing', 'receiving', 'supply'],
     'Request a Quotation', request_url('quotation'), ''],
    ['essential-medicines', 'pill', 'Essential medicines',
     'Everyday and long-term medicines.',
     'Antibiotics, over-the-counter medicines, and medicines for diabetes, blood pressure, cholesterol, heart and kidney care.',
     'Patients, clinics, pharmacies',
     'Visit us or send a prescription.',
     'Enquire About Medicines', request_url('medicine'), ''],
    ['medical-supplies-and-devices', 'clip-gear', 'Medical supplies and devices',
     'Consumables, devices and equipment.',
     'Single-use consumables, devices for asthma, diabetes and blood pressure, medical equipment and laboratory supplies.',
     'Hospitals, clinics, patients',
     ['Send your list', 'quotation', 'supply'],
     'Request a Quotation', request_url('quotation'), ''],
    ['institutional-supply', 'hospital', 'Institutional supply',
     'Supply for hospitals, clinics and agencies.',
     'Quotations and ongoing supply for facilities, and for overseas treatment agencies whose patients return home.',
     'Hospitals, clinics, pharmacies, agencies',
     ['Product list', 'quotation', 'supply', 'follow-up'],
     'Healthcare Professional Enquiry', request_url('professional'), ''],
    ['basic-health-checks', 'pulse', 'Basic health checks',
     'Blood pressure and blood sugar checks.',
     'Simple checks at our pharmacy. If a result needs attention, we advise you to see a doctor.',
     'Members of the public',
     'Visit during opening hours. No appointment needed.',
     'Contact Us', url('/contact'), ''],
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-svc-hero" aria-labelledby="svc-h1">
  <div class="g-wrap g-svc-hero__grid">
    <div>
      <span class="g-label">How We Help</span>
      <h1 id="svc-h1">Our services</h1>
    </div>
    <div class="g-svc-hero__intro">
      <p class="g-hero__lede">Everything we do is about one thing: helping people get the medicines and medical supplies they need.</p>
      <p>Choose a service below to see who it is for, how it works and the best way to contact our team.</p>
    </div>
  </div>
</section>

<section class="g-sec g-svc-list" aria-labelledby="svc-list-h">
  <div class="g-wrap">
    <div class="g-svc-list__head">
      <span class="g-label">Choose a Service</span>
      <h2 id="svc-list-h">What can we help you with?</h2>
    </div>
    <div class="g-svc-grid">
      <?php foreach ($services as [$id, $ic, $name, $line, $details, $who, $how, $cta, $href, $cls]): ?>
      <details class="g-card g-svc <?= e($cls) ?>" id="<?= e($id) ?>" data-smooth>
        <summary>
          <span class="g-card__icon"><?= gi($ic) ?></span>
          <span class="g-svc__head">
            <h2 class="g-svc__name"><?= e($name) ?></h2>
            <span class="g-svc__line"><?= e($line) ?></span>
            <span class="g-tag g-svc__who">Who it is for: <?= e($who) ?></span>
          </span>
          <span class="g-svc__arrow" aria-hidden="true"><?= gi('chev-down') ?></span>
        </summary>
        <div class="g-svc__body">
          <p><?= e($details) ?></p>
          <p class="g-svc__howlabel">How it works</p>
          <?php if (is_array($how)): ?>
          <ol class="g-svc__flow">
            <?php foreach ($how as $k => $step): ?>
            <li><?php if ($k > 0): ?><?= gi('arrow', 'g-svc__sep') ?><?php endif; ?><span><?= e($step) ?></span></li>
            <?php endforeach; ?>
          </ol>
          <?php else: ?>
          <p><?= e($how) ?></p>
          <?php endif; ?>
          <div class="g-btns">
            <?= g_btn($cta, $href, 'primary') ?>
          </div>
        </div>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="g-sec g-home-cta" aria-label="Start your enquiry">
  <div class="g-wrap g-home-cta__inner">
    <div class="g-home-cta__text">
      <h2>Not sure which service you need?</h2>
      <p>Tell us the medicine, medical supply or support you are looking for. Our team will guide you to the right next step.</p>
      <p class="g-home-cta__hours"><?= gi('clock') ?><?= e(cfg('reply_time')) ?> Open <?= e(cfg('hours_long')) ?></p>
    </div>
    <div class="g-home-cta__btns">
      <a class="g-home-cta__btn g-home-cta__btn--white" href="<?= e(request_url('medicine')) ?>">Send an Enquiry</a>
      <a class="g-home-cta__btn g-home-cta__btn--dark" href="<?= e(tel_url()) ?>"><?= gi('phone') ?><span>Call <?= e(cfg('phone')) ?></span></a>
    </div>
  </div>
</section>

<script>
/* A link to /services#named-patient-supply (for example) opens that card. */
(function () {
  function openFromHash() {
    var id = decodeURIComponent(location.hash.slice(1));
    var el = id && document.getElementById(id);
    if (el && el.tagName === 'DETAILS') { el.open = true; }
  }
  openFromHash();
  window.addEventListener('hashchange', openFromHash);
})();
</script>

<?php include INC . '/footer.php'; ?>
