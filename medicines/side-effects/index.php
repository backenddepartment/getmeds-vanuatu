<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$cats = require APP_ROOT . '/data/medicines.php';
$cat  = $cats['side-effects'];

$page = [
    'title' => $cat['title'],
    'desc'  => 'Anti-sickness, pain relief, mouth care, blood count support and nutrition '
             . 'medicines used alongside cancer treatment.',
    // The medicine lookup is this page's primary action.
    'own_primary' => true,
    'ref'   => 'GV-MED-2',
];

include INC . '/head.php';
include INC . '/header.php';

page_open($cat['title'], e($cat['intro']));
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <?php medicine_lookup('side-effects'); ?>

  <h2>The groups we keep</h2>
  <?php chunk_facts($cat['groups']); ?>
    </div>
    <div class="split__aside">
      <?php plate('blister', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="ask-early">
  <h2 id="ask-early">Ask before it gets bad</h2>
  <div class="prose">
    <p>
      Anti-sickness medicine works better taken before nausea starts than after. The same is
      true of most side-effect medicines. If your doctor has prescribed something to take
      before a treatment session, take it on the schedule they gave you, not when you start
      to feel unwell.
    </p>
    <p>
      If a side effect is not being controlled, that is worth a phone call. Sometimes the
      answer is a different medicine, and only your doctor can change the prescription — but
      a pharmacist can often tell you quickly whether what you have is being used the way it
      works best.
    </p>
  </div>

  <div class="notice notice--safety">
    <p class="notice__head">When to stop reading and get help</p>
    <p>
      A fever during chemotherapy can be an emergency. If you have a temperature over
      38&nbsp;degrees, uncontrolled vomiting, difficulty breathing, or bleeding that will not
      stop, contact your treating hospital or go to the emergency department now. Do not wait
      for a pharmacy to open, and do not wait for a reply to an enquiry.
    </p>
  </div>
</section>

<?php
parallel_column('Side-effect medicines, in short', [
    ['en' => 'Take anti-sickness medicine before you feel sick, not after.', 'bi' => null],
    ['en' => 'Follow the times your doctor gave you, even on days you feel well.', 'bi' => null],
    ['en' => 'If a side effect is not getting better, call us or call your doctor.', 'bi' => null],
    ['en' => 'A temperature over 38 degrees during chemotherapy is an emergency. Go to hospital.', 'bi' => null],
    ['en' => 'Only your doctor can change a prescription. A pharmacist can explain it.', 'bi' => null],
], 'parallel-side-effects');
?>

<div class="section shell">
  <div class="actions" style="margin-top:0">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/patients/talk-to-a-pharmacist')) ?>">Talk to a pharmacist</a>
  </div>
</div>

<?php
include INC . '/medicine-disclaimer.php';
include INC . '/footer.php';
