<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/provider-gate.php';

// A form on this page needs CSRF, so the session must start before any output.
ensure_session();

$page = [
    'title'   => 'For doctors and hospitals',
    'desc'    => 'Information for prescribers, nurses, pharmacists and institutional buyers.',
    'ref'     => 'GV-PRV',
    'noindex' => true,
    // The gate's continue button, or this page's form, is the primary action.
    'own_primary' => true,
];

include INC . '/head.php';
include INC . '/header.php';

// The hero shows to every visitor, before the gate. The gate's notice follows
// it (with an h2, since the hero carries the page's h1) until the visitor
// confirms; after that the section's content follows it instead.
page_hero([
    'photo'   => 'warehouse',
    'pos'     => '50% 50%',
    'kicker'  => 'For doctors and hospitals',
    'title'   => 'Oncology supply for hospitals and prescribers.',
    'lede'    => 'Formulary scope, ordering, handling and quotes. Written for prescribers, nurses, '
               . 'pharmacists and institutional buyers across the Pacific.',
    'actions' => [
        ['label' => 'Request a Quote', 'href' => url('/providers/request-a-quote'), 'fill' => true],
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

<?php /* Added from the 2026 content brief, ahead of the section links. */ ?>
<section class="section shell" aria-labelledby="hcp-support" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <h2 id="hcp-support" style="margin-top:0">For healthcare providers</h2>
  <div class="prose">
    <p>
      Getmeds Vanuatu-Pacific supports doctors, pharmacists, hospitals, clinics and other
      healthcare organisations across Vanuatu and the wider Pacific with:
    </p>
  </div>
  <ul class="clauses" style="max-width:var(--measure)">
    <li>Cancer medicine sourcing, including Named Patient Access ordering for a specific patient.</li>
    <li>Essential medicines.</li>
    <li>Medical consumables and supplies.</li>
    <li>Healthcare equipment.</li>
    <li>Medicine quotations for tenders, ward stock, or a named patient.</li>
    <li>Regional sourcing and distribution support.</li>
  </ul>
  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/providers/request-a-quote')) ?>">Submit a Medicine Request</a>
    <a class="next-link" href="tel:<?= e(cfg('phone_href')) ?>">Or call <?= e(cfg('phone')) ?></a>
  </div>
</section>

<div class="section shell">
  <div class="split">
    <div class="split__body">
  <h2 style="margin-top:0">In this section</h2>
  <?php chunk_links(nav_children('/providers')); ?>
    </div>
    <div class="split__aside">
      <?php plate('corridor', [
        'ar'    => '1 / 1',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="fast">
  <h2 id="fast">The short version</h2>
  <dl class="record">
    <div class="record__row">
      <dt>Scope</dt>
      <dd>Cytotoxics, oral antineoplastics, hormonal and targeted agents, supportive care, and
          the cold-chain and controlled lines that go with them.</dd>
    </div>
    <div class="record__row">
      <dt>Named-patient supply</dt>
      <dd>Standard. Most oncology lines are ordered per patient against a prescription rather
          than held as floor stock.</dd>
    </div>
    <div class="record__row">
      <dt>Ward and institutional stock</dt>
      <dd>Supplied on a purchase order with an account. See
          <a href="<?= e(url('/providers/order-for-your-hospital')) ?>">Order for Your Hospital</a>.</dd>
    </div>
    <div class="record__row">
      <dt>Cold chain</dt>
      <dd>2 to 8&nbsp;degrees, monitored and logged end to end, with the log available on
          request for any consignment.</dd>
    </div>
    <div class="record__row">
      <dt>Quotes</dt>
      <dd>For a tender, ward stock or a named patient. See
          <a href="<?= e(url('/providers/request-a-quote')) ?>">Request a Quote</a>.</dd>
    </div>
  </dl>
</section>

<section class="section shell" aria-labelledby="direct">
  <h2 id="direct">Reaching a pharmacist directly</h2>
  <div class="prose">
    <p>
      For an urgent availability question during a clinic, call rather than emailing. A
      pharmacist will check stock while you are on the line.
    </p>
  </div>
  <dl class="record">
    <div class="record__row">
      <dt>Phone</dt>
      <dd><a href="tel:<?= e(cfg('phone_href')) ?>" class="num"><?= e(cfg('phone')) ?></a>
          <span class="record__sub"><?= e(cfg('hours_long')) ?></span></dd>
    </div>
    <div class="record__row">
      <dt>Email</dt>
      <dd><a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a></dd>
    </div>
    <div class="record__row">
      <dt>Responsible pharmacist</dt>
      <dd><?= val('pharmacist_name') ?>
          <span class="record__sub">Registration <span class="num"><?= val('pharmacist_reg_no') ?></span></span></dd>
    </div>
  </dl>

  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/providers/request-a-quote')) ?>">Request a Quote</a>
    <a class="next-link" href="<?= e(url('/providers/what-we-stock')) ?>">What we stock</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
