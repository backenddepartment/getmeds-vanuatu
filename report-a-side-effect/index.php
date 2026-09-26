<?php
/**
 * Report a Side Effect (content guide, page 16).
 *
 * Simple, calm form page with no imagery: the Emergency box at the very top,
 * then the short text, the short form, and what we do next.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/forms.php';

$schema = [
    'name'     => ['label' => 'Your name', 'type' => 'text', 'required' => true],
    'phone'    => ['label' => 'Phone', 'type' => 'tel', 'required' => true],
    'patient'  => ['label' => 'Patient name', 'type' => 'text'],
    'medicine' => ['label' => 'Medicine name', 'type' => 'text', 'required' => true],
    'date_started' => ['label' => 'Date started', 'type' => 'date'],
    'what'     => ['label' => 'What happened?', 'type' => 'textarea', 'required' => true],
    'still'    => ['label' => 'Is the patient still taking the medicine?', 'type' => 'radio',
                   'options' => ['yes' => 'Yes', 'no' => 'No']],
    'doctor'   => ['label' => 'Doctor\'s name', 'type' => 'text'],
    'consent'  => ['label' => 'Consent', 'type' => 'consent', 'required' => true,
                   'html' => 'I agree that Getmeds may use these details to respond to my report. (<a href="'
                           . e(url('/privacy')) . '">Privacy Policy</a>)'],
];

$state = gform_run('side-effect', $schema);

$page = [
    'title'     => 'Report a side effect',
    'seo_title' => 'Report a Side Effect — Getmeds Vanuatu',
    'desc'      => 'Tell Getmeds Vanuatu about a side effect or problem after taking a medicine we supplied.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-sec--tight">
  <div class="g-wrap g-narrow">
    <?php g_box('emergency', 'If the reaction is serious — trouble breathing, swelling of the face or throat, a fever during chemotherapy, or bleeding that will not stop — go to hospital now.'); ?>

    <div class="g-mt-lg">
      <?php g_crumbs([['Home', '/'], ['Report a side effect', null]]); ?>
      <h1>Report a side effect</h1>
    </div>

    <h2 class="g-mt-lg">When to report</h2>
    <p class="g-mt-sm">If you or someone you care for had an unexpected reaction after taking a medicine from Getmeds, please tell us. Also tell your doctor. Your report helps keep other patients safe.</p>

    <h2 class="g-mt-lg">How to report</h2>
    <div class="g-mt">
      <?php gform_render('side-effect', $schema, $state, [
          'id'      => 'report',
          'submit'  => 'Send Report',
          'success' => 'Thank you. A pharmacist will review your report and contact you.',
      ]); ?>
    </div>

    <h2 class="g-mt-lg">What we do next</h2>
    <p class="g-mt-sm">A pharmacist reviews every report, contacts you if we need more details, and passes safety information to the proper health authorities where required.</p>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
