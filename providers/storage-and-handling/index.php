<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/provider-gate.php';

// A form on this page needs CSRF, so the session must start before any output.
ensure_session();

$page = [
    'title'   => 'Storage and handling',
    'desc'    => 'Cold chain, cytotoxic handling, transport and spill procedure at Getmeds '
               . 'Vanuatu.',
    'ref'     => 'GV-PRV-3',
    'noindex' => true,
    // The gate's continue button, or this page's form, is the primary action.
    'own_primary' => true,
];

include INC . '/head.php';
include INC . '/header.php';
provider_gate();

page_open(
    'Storage and handling',
    'What we do, and what we can evidence. The second half matters more than the first.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split split--flip">
    <div class="split__body">
  <h2 style="margin-top:0">Cold chain</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Range held</dt>
      <dd><span class="num">2</span> to <span class="num">8</span>&nbsp;degrees Celsius for
          refrigerated lines, with continuous monitoring rather than spot checks.</dd>
    </div>
    <div class="record__row">
      <dt>Monitoring</dt>
      <dd>Logged continuously, with alarm thresholds and a named person responsible for
          responding to an excursion.</dd>
    </div>
    <div class="record__row">
      <dt>Power failure</dt>
      <dd>Backup power on the refrigeration, and a documented procedure for extended outages.
          Vanuatu makes this a real requirement rather than a formality.</dd>
    </div>
    <div class="record__row">
      <dt>Transport</dt>
      <dd>Validated cold boxes with an in-box monitor. We will not dispatch a cold-chain
          consignment into an arrival where nobody can receive it.</dd>
    </div>
    <div class="record__row">
      <dt>Evidence</dt>
      <dd>The log for any consignment is available to the receiving pharmacist on request.</dd>
    </div>
  </dl>
    </div>
    <div class="split__aside">
      <?php plate('drawer', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="cyto">
  <h2 id="cyto">Cytotoxic handling</h2>
  <ul class="clauses">
    <li>Cytotoxics are segregated in storage, labelled as hazardous, and picked by staff
        trained for them rather than by whoever is nearest.</li>
    <li>Transport is in sealed secondary containment, so a breakage does not contaminate the
        rest of a consignment.</li>
    <li>A spill kit and a written spill procedure are held on the premises, and the procedure
        is available to any institution that wants to align theirs with ours.</li>
    <li>Waste and returns are handled under
        <a href="<?= e(url('/returns')) ?>">Returns &amp; Medicine Disposal</a>. Cytotoxic waste
        does not enter general waste at any point.</li>
  </ul>

  <div class="notice notice--safety">
    <p class="notice__head">If a cytotoxic consignment is damaged in transit</p>
    <p>
      Do not unpack it. Do not attempt to clean a leak with general cleaning materials. Isolate
      the package, keep people away from it, and call <?= phone_link() ?>. We will talk your
      staff through containment and arrange collection and replacement.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="controlled">
  <h2 id="controlled">Controlled medicines</h2>
  <div class="prose">
    <p>
      Controlled lines are stored and recorded to the standard Vanuatu law requires, with a
      register, restricted access and a named recipient identified before dispatch. Quantities
      are limited by law regardless of what a prescription requests, and we will tell you before
      you plan around a quantity we cannot legally supply.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/providers/request-a-quote')) ?>">Request a Quote</a>
    <a class="next-link" href="<?= e(url('/providers/order-for-your-hospital')) ?>">Order for your hospital</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
