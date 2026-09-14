<?php
/**
 * Named Patient Access. From the 2026 content brief: how a prescribed medicine
 * that is not held in Vanuatu can be sourced individually for one patient.
 * This matches the named-patient supply already described on /providers.
 */
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Named Patient Access',
    'desc'  => 'How Getmeds Vanuatu-Pacific can source a prescribed cancer medicine individually '
             . 'when it is not available in Vanuatu.',
    'ref'   => 'GV-PAT-6',
];

include INC . '/head.php';
include INC . '/header.php';

page_open('Named Patient Access', 'Need a cancer medicine that isn’t available locally?');
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
      <div class="prose">
        <p>
          Sometimes the medicine a doctor has prescribed isn’t something we already hold in
          Vanuatu. When that happens, Getmeds Vanuatu-Pacific can support eligible patients and
          healthcare providers in sourcing that specific medicine individually — a process
          often called <strong>Named Patient Access</strong>. It means the medicine is ordered
          for you, by name, against your prescription, rather than pulled from general stock —
          which is also how our institutional supply to hospitals already works.
        </p>
        <p>
          This isn’t a shortcut around a doctor or a pharmacist. It’s a way of getting a
          specific, prescribed medicine to a specific patient when it isn’t already sitting on
          a shelf in Vanuatu.
        </p>
      </div>
    </div>
    <div class="split__aside">
      <?php plate('carton', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="we-check">
  <h2 id="we-check">What we check before we can help</h2>
  <ul class="clauses" style="max-width:var(--measure)">
    <li>A valid prescription or treatment protocol from your doctor.</li>
    <li>What the medicine actually is, and whether it can be sourced at all.</li>
    <li>Whether anything about it needs special ordering, and how long that will realistically take.</li>
    <li>The applicable import and regulatory requirements, which apply to every supplier and every
        patient equally.</li>
  </ul>
</section>

<section class="section shell" aria-labelledby="six-steps">
  <h2 id="six-steps">How it works, in six steps</h2>
  <?php step_cards([
      ['icon' => 'script', 'title' => 'Prescription',
       'text' => 'You bring us the prescription or treatment protocol, or send a clear photo of it.'],
      ['icon' => 'search', 'title' => 'Medicine check',
       'text' => 'We check whether the medicine is available locally, and if not, whether and how it '
               . 'can be sourced.'],
      ['icon' => 'person', 'title' => 'Pharmacist review',
       'text' => 'A pharmacist reviews the prescription against what’s being requested, and flags '
               . 'anything that needs special ordering.'],
      ['icon' => 'card', 'title' => 'Quotation',
       'text' => 'We tell you the price and the realistic timeline before you commit to anything.'],
      ['icon' => 'check', 'title' => 'Confirmation',
       'text' => 'You or an authorised family member confirms the order.'],
      ['icon' => 'box', 'title' => 'Supply and dispensing',
       'text' => 'The medicine is sourced, received, recorded, and dispensed to you or your hospital '
               . 'according to the normal procedures — with the paperwork kept, the same as any '
               . 'other prescription medicine.'],
  ]); ?>

  <div class="prose" style="margin-top:var(--s-6)">
    <p>
      We will not encourage anyone to buy a prescription medicine, cancer medicine included,
      without proper medical authorisation. If a medicine can’t be sourced, or the cost or
      timeline isn’t workable, we’ll tell you plainly rather than stringing the request along.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="ask-availability">
  <h2 id="ask-availability">Ask about medicine availability</h2>
  <div class="prose">
    <p>
      Call <?= phone_link('body-link') ?>, <?= e(cfg('hours_long')) ?> Or send us the details in
      writing and a pharmacist will answer you.
    </p>
  </div>
  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="tel:<?= e(cfg('phone_href')) ?>">Call <?= e(cfg('phone')) ?></a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
