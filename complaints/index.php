<?php
/**
 * Complaints.
 *
 * As with Report a Side Effect, the practical route is written and the formal
 * escalation policy is left for a lawyer. A complaints page that cannot tell
 * somebody how to complain is not a complaints page.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Complaints',
    'desc'  => 'How to complain about Getmeds Vanuatu, and what happens after you do.',
    'ref'   => 'GV-LEG-9',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Complaints',
    'If something went wrong, we would rather hear it from you than not hear it at all.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="prose">
    <p>
      You do not need to be polite about it, and you do not need to know whose fault it was.
      Tell us what happened. A wrong medicine, a delay nobody explained, a cold box that
      arrived warm, a price that changed, or being treated badly at the counter are all worth
      reporting.
    </p>
  </div>

  <h2>How to complain</h2>
  <dl class="record">
    <div class="record__row">
      <dt>By phone</dt>
      <dd><a href="tel:<?= e(cfg('phone_href')) ?>" class="num"><?= e(cfg('phone')) ?></a>
          <span class="record__sub">Ask to speak to the responsible pharmacist.</span></dd>
    </div>
    <div class="record__row">
      <dt>By email</dt>
      <dd><a href="mailto:<?= e(cfg('email')) ?>?subject=Complaint"><?= e(cfg('email')) ?></a>
          <span class="record__sub">Put "complaint" in the subject line so it is not read as an enquiry.</span></dd>
    </div>
    <div class="record__row">
      <dt>In person</dt>
      <dd><?= e(cfg('address_line')) ?>, <?= e(cfg('address_city')) ?></dd>
    </div>
    <div class="record__row">
      <dt>Goes to</dt>
      <dd><?= val('pharmacist_name') ?>, the responsible pharmacist
          <span class="record__sub">Registration <span class="num"><?= val('pharmacist_reg_no') ?></span></span></dd>
    </div>
  </dl>

  <h2>What helps</h2>
  <ul class="clauses">
    <li>The date, and your enquiry reference if you have one. It looks like
        <span class="num">GV-2026-0000</span>.</li>
    <li>The medicine involved, if there was one.</li>
    <li>What you expected to happen, and what actually happened.</li>
    <li>What you would like us to do about it. Sometimes that is a refund, sometimes it is an
        explanation, and sometimes it is just making sure it does not happen to the next person.</li>
  </ul>

  <div class="notice">
    <p class="notice__head">A complaint will not affect your supply</p>
    <p>
      Complaining does not put your medicine at risk and will not change how you are treated
      here. If you are worried about that, say so when you complain, and say it to the
      pharmacist directly.
    </p>
  </div>
</div>

<section class="section shell" aria-labelledby="clinical">
  <h2 id="clinical">If your complaint is about clinical care</h2>
  <div class="prose">
    <p>
      We can only answer for what this pharmacy did. A complaint about your diagnosis,
      treatment plan or hospital care belongs to that hospital or clinic, and they have their
      own process. We will tell you plainly when something is outside what we can address,
      rather than absorbing it and going quiet.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="formal">
  <h2 id="formal">Formal complaints process</h2>
  <div class="todo-block">
    <p class="todo-block__head">Complaints policy required</p>
    <p class="todo-block__body">
      Acknowledgement and resolution timeframes, internal escalation, record-keeping
      obligations, and the external body a person may take an unresolved complaint to — the
      pharmacy regulator or health authority in Vanuatu, named, with its contact route. Also
      whether a complaint about a registered pharmacist may be made to the registration body
      directly. To be drafted by a lawyer qualified in Vanuatu. Deliberately not invented here,
      because naming the wrong authority would send someone to a dead end.
    </p>
  </div>
</section>

<?php
parallel_column('Making a complaint, in short', [
    ['en' => 'If something went wrong, tell us. Call ' . cfg('phone') . '.', 'bi' => null],
    ['en' => 'Ask to speak to the responsible pharmacist.', 'bi' => null],
    ['en' => 'Say what happened and what you would like us to do.', 'bi' => null],
    ['en' => 'Complaining will not affect your medicine or how we treat you.', 'bi' => null],
    ['en' => 'A complaint about your treatment belongs to your hospital, not to us.', 'bi' => null],
], 'parallel-complaints');

include INC . '/footer.php';
