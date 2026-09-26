<?php
/**
 * Services (content guide, page 13).
 *
 * An overview: H1 and intro, then eight service cards (two columns on
 * desktop, one on mobile). Each card is a <details>: the closed card shows the
 * icon, the service name (H2), a one-line explanation, the "Who it is for"
 * tag and an arrow; opened, it shows the details, the how-it-works mini steps
 * and the CTA button. Service 1 has the Care Pink top border (flagship);
 * Service 7 is the navy card for professionals.
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
     'Healthcare Professional Enquiry', request_url('professional'), 'g-card--navy'],
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

<section class="g-sec g-bg-white g-svc-intro" aria-labelledby="svc-h1">
  <div class="g-wrap g-narrow g-center">
    <h1 id="svc-h1">Our services</h1>
    <p class="g-hero__lede" style="margin-inline:auto">Everything we do is about one thing: helping people get the medicines they need.</p>
  </div>
</section>

<section class="g-sec g-bg-sky" aria-label="Our eight services">
  <div class="g-wrap">
    <div class="g-svc-grid">
      <?php foreach ($services as [$id, $ic, $name, $line, $details, $who, $how, $cta, $href, $cls]): ?>
      <details class="g-card g-svc <?= e($cls) ?>" id="<?= e($id) ?>">
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
