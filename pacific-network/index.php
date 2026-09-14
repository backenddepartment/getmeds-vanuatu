<?php
/**
 * Pacific Network. From the 2026 content brief: the regional access and
 * distribution story. Written as a staged plan, country by country, never as
 * a claim that Getmeds already operates everywhere it names.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Pacific Network',
    'desc'  => 'How Getmeds Vanuatu-Pacific is building cancer medicine access and supply '
             . 'pathways across the Pacific, starting in Vanuatu.',
    'ref'   => 'GV-PAC',
];

include INC . '/head.php';
include INC . '/header.php';

page_hero([
    'short'   => true,
    'photo'   => 'wharf',
    'pos'     => '50% 55%',
    'kicker'  => 'Pacific Network',
    'title'   => 'Connecting cancer medicine access across the Pacific.',
    'lede'    => 'Getmeds Vanuatu-Pacific started in Vanuatu, but the problem it’s built around is a '
               . 'Pacific-wide one.',
    'actions' => [
        ['label' => 'Contact Getmeds', 'href' => url('/contact'), 'fill' => true],
        ['label' => 'For Healthcare Providers', 'href' => url('/providers')],
    ],
]);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
      <div class="prose">
        <p>
          Getmeds Vanuatu-Pacific started in Vanuatu, but the problem it’s built around — small,
          scattered populations that make individual medicine importation slow and expensive —
          is a Pacific-wide one, not just a Vanuatu one. Our longer-term work is building
          partnerships and supply pathways designed to improve access to cancer medicines more
          broadly across the region.
        </p>
        <p>That includes:</p>
      </div>
      <ul class="clauses">
        <li>Working with healthcare providers, hospitals, pharmacies, and government health agencies
            in more than one Pacific country.</li>
        <li>Supporting more reliable sourcing and distribution so a single delayed shipment doesn’t
            stop treatment.</li>
        <li>Helping reduce treatment interruptions caused by medicine shortages.</li>
        <li>Developing a regional network for cancer medicines and other essential medicines,
            rather than a country-by-country one.</li>
        <li>Supporting patients who return home after overseas cancer treatment and need to continue
            their medicines locally, so treatment doesn’t lapse between the last overseas dose and
            the next local one.</li>
      </ul>
    </div>
    <div class="split__aside">
      <?php plate('island', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="heading">
  <h2 id="heading">Where this is heading</h2>
  <div class="prose">
    <p>
      Vanuatu is our founding market. We’re also assessing opportunity and building
      relationships in Fiji, and the countries identified for possible future expansion include
      Solomon Islands, Samoa, Tonga, Nauru and Tuvalu. This is a staged process — country by
      country, partnership by partnership — not a claim that we already operate in all of them.
      Each new country brings its own regulatory pathway, its own hospital and pharmacy
      partners, and its own supply chain to build properly before we say we’re there.
    </p>
  </div>
  <dl class="record" style="margin-top:var(--s-6)">
    <div class="record__row">
      <dt><?= icon('pin') ?>Founding market</dt>
      <dd>Vanuatu</dd>
    </div>
    <div class="record__row">
      <dt><?= icon('ship') ?>Assessing and building relationships</dt>
      <dd>Fiji</dd>
    </div>
    <div class="record__row">
      <dt><?= icon('cal') ?>Identified for possible future expansion</dt>
      <dd>Solomon Islands, Samoa, Tonga, Nauru and Tuvalu</dd>
    </div>
  </dl>
</section>

<section class="section shell" aria-labelledby="partners">
  <h2 id="partners">Who we’re working with</h2>
  <div class="prose">
    <p>
      Across the region this looks like partnerships with hospitals and clinics, retail
      pharmacies, government health ministries, overseas referral and medical-mission partners,
      and (where useful) regional distributors — so that a patient’s medicine access doesn’t
      depend on one single supply line.
    </p>
  </div>
  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/contact')) ?>">Contact Getmeds</a>
    <a class="next-link" href="<?= e(url('/providers')) ?>">For healthcare providers</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
