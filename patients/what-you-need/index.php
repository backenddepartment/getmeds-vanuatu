<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'What you need',
    'desc'  => 'The papers and details to bring or send before Getmeds Vanuatu can dispense '
             . 'your medicine.',
    'ref'   => 'GV-PAT-2',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'What you need',
    'A short list. Print this page or take a photograph of it if it helps.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split split--flip">
    <div class="split__body">
  <h2 style="margin-top:0">To start an enquiry</h2>
  <ul class="clauses">
    <li><strong>The medicine name.</strong> As written on the prescription, with the strength
        if you can see it. If you are not sure you have read it correctly, send it anyway and
        a pharmacist will work it out.</li>
    <li><strong>A phone number we can reach you on.</strong> Almost every enquiry ends in a
        phone call, because that is faster and clearer than typing.</li>
  </ul>

  <h2>To collect the medicine</h2>
  <ul class="clauses">
    <li><strong>The original prescription.</strong> A photograph is enough to start the
        enquiry, but we need the paper before we hand medicine over.</li>
    <li><strong>Photo identification</strong> for the person collecting. A passport, driver's
        licence or a Vanuatu ID card.</li>
    <li><strong>A letter from the patient</strong> if you are collecting for somebody else,
        plus your own identification. A message on a phone is fine if it names you.</li>
    <li><strong>Payment.</strong> A pharmacist will have already told you the amount, so
        there are no surprises at the counter. See
        <a href="<?= e(url('/patients/prices-and-payment')) ?>">Prices &amp; Payment</a>.</li>
  </ul>

  <h2>Useful, but not required</h2>
  <ul class="clauses">
    <li>A list of everything else you take, including anything from a market stall, a herbal
        remedy or a relative. Some of these interact with cancer medicine, and a pharmacist
        would rather know.</li>
    <li>The name of the doctor or clinic treating you, so we can check with them directly if
        something on the prescription is unclear.</li>
    <li>Any allergy you know about.</li>
  </ul>
    </div>
    <div class="split__aside">
      <?php plate('script', [
        'ar'    => '4 / 5',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="controlled">
  <h2 id="controlled">Stronger pain medicines have extra rules</h2>
  <div class="prose">
    <p>
      Some pain medicines are controlled by law in Vanuatu. For those we need the original
      prescription in a specific form, we cannot accept a photograph as the final document,
      and we cannot dispense more than the law allows at one time even if the prescription
      asks for more. This is not us being difficult. See
      <a href="<?= e(url('/prescription-policy')) ?>">Prescription Policy</a>.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/patients/prices-and-payment')) ?>">How prices work</a>
  </div>
</section>

<?php
parallel_column('What to bring, in short', [
    ['en' => 'To ask a question: the medicine name and your phone number.', 'bi' => null],
    ['en' => 'To collect medicine: the original prescription paper and photo ID.', 'bi' => null],
    ['en' => 'Collecting for someone else: bring a letter from them, and your own ID.', 'bi' => null],
    ['en' => 'Tell us about every other medicine or herbal remedy you take.', 'bi' => null],
    ['en' => 'Strong pain medicines have extra rules set by law.', 'bi' => null],
], 'parallel-need');

include INC . '/footer.php';
