<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Talk to a pharmacist',
    'desc'  => 'When to call Getmeds Vanuatu, what a pharmacist can help with, and what needs '
             . 'your doctor or the hospital instead.',
    'ref'   => 'GV-PAT-4',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Talk to a pharmacist',
    'A pharmacist can answer more than most people expect, and there are a few things only '
    . 'your doctor can do.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <div class="notice">
    <p class="notice__head">Call the pharmacy</p>
    <p style="font-size:var(--t-2);font-weight:700;margin:0 0 var(--s-2)">
      <a href="tel:<?= e(cfg('phone_href')) ?>" class="num"><?= e(cfg('phone')) ?></a>
    </p>
    <p><?= e(cfg('hours_long')) ?> You will speak to a person.</p>
  </div>

  <h2>What a pharmacist can help with</h2>
  <ul class="clauses">
    <li>Whether we can supply a medicine, what it costs, and how long it takes.</li>
    <li>How and when to take what you have been prescribed, and what to do if you miss a dose.</li>
    <li>Whether a side effect you are having is expected, and whether the medicine you have for
        it is being used the way it works best.</li>
    <li>Whether something else you take — including herbal remedies and supplements — is safe
        alongside your treatment.</li>
    <li>How to store your medicine at home, and how to dispose of what you do not use.</li>
  </ul>

  <h2>What needs your doctor instead</h2>
  <ul class="clauses">
    <li>Changing a dose, changing a medicine, or stopping treatment. A pharmacist cannot do
        any of these, and will not.</li>
    <li>Whether a treatment is working, and what the plan is from here.</li>
    <li>A new prescription, or a repeat of an expired one.</li>
    <li>Anything about your diagnosis or prognosis.</li>
  </ul>
    </div>
    <div class="split__aside">
      <?php plate('notes', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="urgent">
  <h2 id="urgent">When not to call us</h2>
  <div class="notice notice--safety">
    <p class="notice__head">These are emergencies. Go to hospital.</p>
    <p>
      A temperature over 38&nbsp;degrees during or after chemotherapy. Vomiting you cannot
      stop. Trouble breathing. Bleeding that will not stop. Confusion or a sudden severe
      headache. Contact your treating hospital or go to the emergency department immediately.
    </p>
    <p>
      A pharmacy has opening hours. Do not spend any of an emergency waiting for us to open,
      and do not wait for a reply to an enquiry form.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="written">
  <h2 id="written">If you would rather write than call</h2>
  <div class="prose">
    <p>
      Use the enquiry form and a pharmacist will read it. It is slower than the phone, and it
      is the right choice if you want the answer in writing, if English is easier for you to
      write than to speak, or if you simply do not have the energy for a conversation today.
      All three are good reasons.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/contact')) ?>">Where to find us</a>
  </div>
</section>

<?php
parallel_column('When to call, in short', [
    ['en' => 'Call ' . cfg('phone') . ' to speak to a pharmacist.', 'bi' => null],
    ['en' => 'A pharmacist can explain how to take your medicine and what side effects to expect.', 'bi' => null],
    ['en' => 'Only your doctor can change your dose or stop your treatment.', 'bi' => null],
    ['en' => 'Tell us about herbal medicine or supplements you take.', 'bi' => null],
    ['en' => 'For a fever over 38 degrees or heavy bleeding, go to hospital now. Do not call us first.', 'bi' => null],
], 'parallel-talk');

include INC . '/footer.php';
