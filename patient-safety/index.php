<?php
/**
 * Patient Safety. From the 2026 content brief: a short page, linked from every
 * Medicines and Patients page (see section_notes()) and from the footer.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Patient safety',
    'desc'  => 'Getmeds Vanuatu-Pacific does not replace the advice of your doctor. Use prescription '
             . 'medicines only under medical supervision.',
    'ref'   => 'GV-SAFE',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Patient safety comes first',
    'Getmeds Vanuatu-Pacific does not replace the advice of a doctor, oncologist, pharmacist, '
    . 'or other qualified healthcare professional.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="notice notice--safety">
    <p class="notice__head"><?= icon('alert') ?>Only under medical supervision</p>
    <p>
      Prescription medicines — cancer and chemotherapy medicines especially — should only be
      used under appropriate medical supervision. Please don’t change your medicine, dose,
      treatment schedule, or chemotherapy protocol without talking to your healthcare provider
      first.
    </p>
  </div>

  <div class="prose">
    <p>
      If anything on this website is unclear, or you’re not sure what a medicine is for, call a
      pharmacist on <?= phone_link('body-link') ?> rather than guessing.
      <?= e(cfg('hours_long')) ?>
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="tel:<?= e(cfg('phone_href')) ?>">Call <?= e(cfg('phone')) ?></a>
    <a class="next-link" href="<?= e(url('/report-side-effect')) ?>">Report a side effect</a>
  </div>
</div>

<section class="section shell" aria-labelledby="related">
  <h2 id="related">Related</h2>
  <?php chunk_links([
      ['label' => 'Medical Disclaimer', 'url' => '/disclaimer',
       'blurb' => 'What the information on this site is for, and what it must not be used for.'],
      ['label' => 'Report a Side Effect', 'url' => '/report-side-effect',
       'blurb' => 'Something that happened after taking a medicine we supplied.'],
      ['label' => 'Talk to a Pharmacist', 'url' => '/patients/talk-to-a-pharmacist',
       'blurb' => 'When to call, and what a pharmacist can help with.'],
  ]); ?>
</section>

<?php include INC . '/footer.php'; ?>
