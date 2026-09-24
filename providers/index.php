<?php
/**
 * /providers — one page replacing the landing page and its four children.
 *
 * What We Stock, Order for Your Hospital, Storage & Handling and Request a
 * Quote are now sections here. A prescriber checking availability mid-clinic
 * should not be navigating a section index.
 *
 * The healthcare-professional gate is unchanged: config.php still decides it,
 * and PRODUCT.md still records why it is an interstitial while legal advice is
 * pending. Cutting the site down was not a reason to quietly drop it.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/provider-gate.php';
require_once INC . '/icons.php';

// The gate posts, so the session must start before any output.
ensure_session();

$page = [
    'title'   => 'For doctors and hospitals',
    'desc'    => 'Formulary scope, institutional ordering, cold-chain handling and quotes for '
               . 'prescribers, pharmacists and hospital buyers across the Pacific.',
    'ref'     => 'GV-PRV',
    'noindex' => true,
    'own_primary' => true,
];

include INC . '/head.php';
include INC . '/header.php';

page_hero([
    'photo'   => 'warehouse',
    'pos'     => '50% 50%',
    'kicker'  => 'For doctors and hospitals',
    'title'   => 'Oncology supply for hospitals and prescribers.',
    'lede'    => 'Scope, ordering, cold-chain handling and quotes, for prescribers, nurses, '
               . 'pharmacists and institutional buyers across the Pacific.',
    'actions' => [
        ['label' => 'Request a Quote', 'href' => url('/order') . '?need=other', 'fill' => true],
        ['label' => 'Call ' . cfg('phone'), 'href' => 'tel:' . cfg('phone_href'), 'icon' => 'phone'],
    ],
    'points'  => [
        ['hosp', 'Named-patient supply'],
        ['cold', 'Cold chain logged'],
        ['box',  'Tender and ward quotes'],
    ],
]);

provider_gate('h2');
?>

<section class="section shell" aria-labelledby="fast" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <h2 id="fast" style="margin-top:0">The short version</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Scope</dt>
      <dd>Cytotoxics, oral antineoplastics, hormonal and targeted agents, supportive care, and
          the cold-chain and controlled lines that go with them. Also essential medicines,
          consumables and healthcare equipment.</dd>
    </div>
    <div class="record__row">
      <dt>Named-patient supply</dt>
      <dd>Standard. Most oncology lines are ordered per patient against a prescription rather
          than held as floor stock.</dd>
    </div>
    <div class="record__row">
      <dt>Cold chain</dt>
      <dd>2 to 8&nbsp;degrees, monitored and logged end to end, with the log available on
          request for any consignment.</dd>
    </div>
    <div class="record__row">
      <dt>Outside Vanuatu</dt>
      <dd>We supply institutions in Fiji, Solomon Islands and the wider Pacific.</dd>
    </div>
  </dl>
</section>

<section class="section shell" aria-labelledby="stock">
  <h2 id="stock">What we stock</h2>
  <div class="prose">
    <p>
      We do not publish a line-item formulary. An unconfirmed oncology stock list would
      misrepresent availability, and availability is exactly the thing you need to be right.
    </p>
    <p>
      For a specific molecule, presentation and strength, ask. A pharmacist will confirm
      whether it is held, the pack size, and the realistic lead time.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="ordering">
  <h2 id="ordering">Ordering for your hospital</h2>
  <ol class="clauses">
    <li>Send the institution's name, its registration or ministry reference, the delivery
        address, and the pharmacist or clinician who will authorise orders.</li>
    <li>We confirm what we can supply against your formulary, and where lead times will need
        planning.</li>
    <li>We agree payment terms in writing before the first order, including who is invoiced
        and in what currency.</li>
    <li>You order against a purchase order from then on. Named individuals authorise, and we
        hold a record of who they are.</li>
  </ol>
  <p class="small quiet">
    Import permits and customs documentation are the receiving country's requirement. We need
    those details before we can quote a delivered price.
    <a href="<?= e(url('/policies')) ?>#delivery-rules">Delivery and import</a>.
  </p>
</section>

<section class="section shell" aria-labelledby="storage">
  <h2 id="storage">Storage and handling</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Range held</dt>
      <dd>2 to 8&nbsp;degrees Celsius for refrigerated lines, with continuous monitoring
          rather than spot checks.</dd>
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
</section>

<section class="section shell" aria-labelledby="quote">
  <h2 id="quote">Requesting a quote</h2>
  <div class="prose">
    <p>
      For a tender, ward stock or a named patient, send the molecule, presentation, strength
      and the quantity or number of cycles. A pharmacist prices the full requirement, not one
      pack, and tells you the lead time with it.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/order')) ?>?need=other">Request a Quote</a>
    <a class="btn btn--secondary" href="tel:<?= e(cfg('phone_href')) ?>">Call <?= e(cfg('phone')) ?></a>
  </div>

  <p class="small quiet">
    For an urgent availability question during a clinic, call rather than emailing — a
    pharmacist will check stock while you are on the line.
    <?= e(cfg('hours_long')) ?>
    Email <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a> for anything that
    can wait a working day.
  </p>
</section>

<?php include INC . '/footer.php'; ?>
