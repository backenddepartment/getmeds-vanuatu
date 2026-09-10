<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/provider-gate.php';

// A form on this page needs CSRF, so the session must start before any output.
ensure_session();

$page = [
    'title'   => 'Order for your hospital',
    'desc'    => 'Institutional ordering, documentation and accounts for hospitals, ministries '
               . 'and NGOs across the Pacific.',
    'ref'     => 'GV-PRV-2',
    'noindex' => true,
    // The gate's continue button, or this page's form, is the primary action.
    'own_primary' => true,
];

include INC . '/head.php';
include INC . '/header.php';
provider_gate();

page_open(
    'Order for your hospital',
    'Accounts, purchase orders, documentation, and how a consignment is received.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <h2 style="margin-top:0">Setting up an account</h2>
  <ol class="clauses">
    <li>Send us the institution's name, its registration or ministry reference, the delivery
        address, and the pharmacist or clinician who will authorise orders.</li>
    <li>We confirm what we can supply against your formulary, and where lead times will need
        planning.</li>
    <li>We agree payment terms in writing before the first order, including who is invoiced and
        in what currency.</li>
    <li>You order against a purchase order from then on. Named individuals authorise; we hold a
        record of who they are.</li>
  </ol>

  <div class="notice">
    <p class="notice__head">Outside Vanuatu</p>
    <p>
      We supply institutions in Fiji, Solomon Islands and the wider Pacific. Import permits and
      customs documentation are the receiving country's requirement and we will need those
      details before we can quote a delivered price. See
      <a href="<?= e(url('/shipping-rules')) ?>">Shipping &amp; Import Rules</a>.
    </p>
  </div>
    </div>
    <div class="split__aside">
      <?php plate('warehouse', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="docs">
  <h2 id="docs">Documentation we provide</h2>
  <dl class="record">
    <div class="record__row">
      <dt>With every consignment</dt>
      <dd>Itemised packing list, batch numbers and expiry dates, and the supplying pharmacy's
          licence reference.</dd>
    </div>
    <div class="record__row">
      <dt>Cold-chain lines</dt>
      <dd>The temperature log for the consignment, and the monitor record from the box, on
          request or as standard if you ask us to make it standard.</dd>
    </div>
    <div class="record__row">
      <dt>Controlled lines</dt>
      <dd>The register documentation Vanuatu law requires, and a named recipient identified
          before dispatch.</dd>
    </div>
    <div class="record__row">
      <dt>On request</dt>
      <dd>Certificate of analysis where the manufacturer issues one, and supply-chain
          provenance for a tender submission.</dd>
    </div>
  </dl>
</section>

<section class="section shell" aria-labelledby="receiving">
  <h2 id="receiving">Receiving a consignment</h2>
  <div class="prose">
    <p>
      Check the packing list against the delivery and the monitor record on any cold box before
      signing. A discrepancy raised at receipt is straightforward for us to resolve. One raised
      a fortnight later, after the medicine has been on a ward shelf, often is not.
    </p>
    <p>
      Report a cold-chain excursion immediately and quarantine the affected stock rather than
      refrigerating it. Call <?= phone_link() ?>.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/providers/request-a-quote')) ?>">Request a Quote</a>
    <a class="next-link" href="<?= e(url('/providers/storage-and-handling')) ?>">Storage and handling</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
