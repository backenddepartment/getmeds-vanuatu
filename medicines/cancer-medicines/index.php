<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$cats = require APP_ROOT . '/data/medicines.php';
$cat  = $cats['cancer-medicines'];

$page = [
    'title' => $cat['title'],
    'desc'  => 'The groups of cancer medicine Getmeds Vanuatu supplies in Port Vila, against '
             . 'a valid prescription. No stock list or prices are published.',
    // The medicine lookup is this page's primary action.
    'own_primary' => true,
    'ref'   => 'GV-MED-1',
];

include INC . '/head.php';
include INC . '/header.php';

page_open($cat['title'], e($cat['intro']));
?>

<?php /* Added from the 2026 content brief, ahead of the existing sections, which
         are kept exactly as they were. */ ?>
<section class="section shell" aria-labelledby="within-reach" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <h2 id="within-reach" style="margin-top:0">Cancer medicines within reach</h2>
  <div class="prose">
    <p>
      Getting cancer treatment shouldn’t depend on being able to fly overseas or import
      medicine on your own. Cancer patients in Vanuatu can face high treatment costs, medicine
      stockouts, long delivery times, and real difficulty continuing treatment once they’ve
      returned home from overseas care. Getmeds Vanuatu-Pacific exists to help close that gap —
      improving access to cancer medicines locally, and supporting patients so treatment
      doesn’t have to stop and restart.
    </p>
    <p>
      Our product approach covers both medicines that are already available locally and
      medicines that may need to be imported or sourced individually for a specific patient.
      We do not publish a list of medicines held in stock at any given time — availability
      changes, and the honest answer for a specific medicine is always to ask a pharmacist
      directly.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="reduce-cost">
  <h2 id="reduce-cost">Helping reduce the cost of cancer treatment</h2>
  <div class="prose">
    <p>
      Cancer treatment can place a heavy financial burden on a patient and their family, on
      top of everything else it asks of them. In Vanuatu, cancer medicines are typically an
      out-of-pocket cost, and overseas referral for treatment — most commonly to India —
      carries its own significant expense once travel, accommodation and a caretaker’s costs
      are added to the medicine itself.
    </p>
    <p>
      Getmeds Vanuatu-Pacific is working to be part of the answer to that cost, specifically
      through:
    </p>
  </div>
  <ul class="clauses" style="max-width:var(--measure)">
    <li>Reduced-cost and competitive pricing on cancer medicines, where we’re able to offer it.</li>
    <li>Local access, so a patient isn’t paying for an individual import each time.</li>
    <li>Sourcing support for medicines not already available in Vanuatu.</li>
    <li>Reducing how often a patient or family has to arrange an overseas purchase on their own.</li>
    <li>Supporting continuity, so a treatment cycle doesn’t stop because a medicine hasn’t arrived.</li>
  </ul>
  <div class="prose" style="margin-top:var(--s-5)">
    <p>
      We can’t promise every medicine will always be the lowest-cost option available, or that
      every medicine will always be in stock. What we can promise is that we’ll tell you the
      actual price and the actual timeline before you decide anything.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="pathway">
  <h2 id="pathway">Building Vanuatu’s first dedicated chemotherapy pathway</h2>
  <div class="prose">
    <p>
      Vanuatu does not currently have a dedicated chemotherapy unit. Vila Central Hospital, the
      country’s national referral hospital, does not yet have a medical oncologist or
      haematologist on staff, a cytotoxic safety cabinet for preparing chemotherapy medicines
      safely, a dedicated chemotherapy unit, or a system for tracking its cancer patient
      population — cancer patients are currently cared for within the general ward. Patients
      are often referred overseas, most often to India, at a cost that can run from several
      thousand into the tens of thousands of US dollars per patient, excluding a caretaker’s
      own costs and the medicines themselves.
    </p>
    <p>
      Getmeds Vanuatu-Pacific is working with Vanuatu’s Ministry of Health toward changing that
      locally: supporting the country’s first dedicated chemotherapy pharmacy and treatment
      pathway, anchored at Vila Central Hospital. This work is underway rather than finished,
      and includes a Ministry of Health commitment of hospital space for the country’s first
      chemotherapy unit, alongside a broader push for locally available cancer medicines,
      cytotoxic safety equipment, and trained oncology staff. We’re also coordinating with
      international partners — including a health mission from the Republic of Indonesia that
      has committed to installing cytotoxic safety cabinet equipment and bringing an
      oncologist, nurse and pharmacist as part of a capacity-building visit.
    </p>
    <p>
      We describe this carefully because it matters to be accurate: this is a pathway being
      built, in partnership with government and international partners, not a claim that the
      unit already exists. We’ll update this page as each stage is completed.
    </p>
  </div>
</section>

<div class="section shell">
  <div class="split split--flip">
    <div class="split__body">
  <?php medicine_lookup('cancer-medicines'); ?>

  <h2>The groups we handle</h2>
  <?php chunk_facts($cat['groups']); ?>
    </div>
    <div class="split__aside">
      <?php plate('line', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="how-supplied">
  <h2 id="how-supplied">Who we give the medicine to</h2>
  <div class="prose">
    <p>
      This surprises people, so it is worth saying plainly. Medicines given by drip or
      injection go to the hospital or clinic that will give them to you. They do not come
      home with you. Your ward or your oncology nurse orders them, and we supply them there.
    </p>
    <p>
      Tablets and capsules you take at home are dispensed to you, in person, with a
      pharmacist going through the schedule with you before you leave.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="cyto">
  <h2 id="cyto">Handling and safety</h2>
  <div class="notice notice--safety">
    <p class="notice__head">Cancer medicines are hazardous to handle</p>
    <p>
      Do not crush, split or open a cancer tablet or capsule unless a pharmacist or your
      doctor has told you to. Keep them in the container they came in, out of reach of
      children, and away from anyone who is pregnant or breastfeeding. If a tablet breaks or
      spills, do not sweep it up with your hands. Call us on <?= phone_link() ?> and we will
      tell you what to do.
    </p>
  </div>
  <div class="prose">
    <p>
      When a course finishes, unused cancer medicine must not go in household rubbish or a
      toilet. Bring it back to us. See
      <a href="<?= e(url('/returns')) ?>">Returns &amp; Medicine Disposal</a>.
    </p>
  </div>
</section>

<?php
parallel_column('Cancer medicines, in short', [
    ['en' => 'Every cancer medicine needs a prescription from your doctor.', 'bi' => null],
    ['en' => 'We do not sell medicine on this website. A pharmacist checks every request.', 'bi' => null],
    ['en' => 'Medicine given by drip goes to your hospital, not to your house.', 'bi' => null],
    ['en' => 'Do not break or open cancer tablets. Keep them away from children.', 'bi' => null],
    ['en' => 'Bring unused cancer medicine back to the pharmacy. Do not throw it away.', 'bi' => null],
    ['en' => 'If you are unsure about anything, call ' . cfg('phone') . '.', 'bi' => null],
], 'parallel-cancer');
?>

<div class="section shell">
  <div class="actions" style="margin-top:0">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/medicines/side-effects')) ?>">Medicines for side effects</a>
  </div>
</div>

<?php
include INC . '/medicine-disclaimer.php';
include INC . '/footer.php';
