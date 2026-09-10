<?php
require __DIR__ . '/../../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/provider-gate.php';

// A form on this page needs CSRF, so the session must start before any output.
ensure_session();

$page = [
    'title'   => 'What we stock',
    'desc'    => 'Formulary scope, availability behaviour and lead times for oncology supply '
               . 'from Getmeds Vanuatu.',
    'ref'     => 'GV-PRV-1',
    'noindex' => true,
    // The gate's continue button, or this page's form, is the primary action.
    'own_primary' => true,
];

include INC . '/head.php';
include INC . '/header.php';
provider_gate();

page_open(
    'What we stock',
    'Scope, how availability actually behaves, and lead times you can plan a clinic around.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <div class="todo-block">
    <p class="todo-block__head">Formulary list required</p>
    <p class="todo-block__body">
      A line-item formulary has deliberately not been published. It must be compiled and
      confirmed by the responsible pharmacist, and kept current, before it appears here.
      Publishing an unconfirmed oncology stock list would misrepresent availability.
    </p>
  </div>

  <div class="prose">
    <p>
      Our scope covers intravenous cytotoxics, oral antineoplastics, hormonal and targeted
      agents, the supportive-care lines given alongside them, and the cold-chain and
      controlled products those courses require.
    </p>
    <p>
      For a specific molecule, presentation and strength, ask. A pharmacist will confirm
      whether it is held, what the pack size is, and the realistic lead time.
    </p>
  </div>

  <?php medicine_lookup('providers-stock'); ?>
    </div>
    <div class="split__aside">
      <?php plate('vialtray', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="behaviour">
  <h2 id="behaviour">How availability behaves</h2>
  <div class="prose">
    <p>
      Oncology supply in a market this size does not behave like a general formulary. Most
      lines are ordered per patient against a prescription rather than held as floor stock,
      because holding slow-moving cytotoxics to expiry wastes medicine that is hard to
      replace. Planning around that is usually better than pushing against it.
    </p>
  </div>
  <dl class="record">
    <div class="record__row">
      <dt>Held locally</dt>
      <dd>Supportive care, anti-emetics, common oral agents, and the lines with predictable
          local demand. Same-day for a prescription presented in Port Vila.</dd>
    </div>
    <div class="record__row">
      <dt>Ordered per patient</dt>
      <dd>Most parenteral cytotoxics, targeted and biological agents. Typically a few days to
          two weeks depending on origin and flight schedule.</dd>
    </div>
    <div class="record__row">
      <dt>Sourced through the group</dt>
      <dd>Lines with no Vanuatu supply route. Longer, and quoted with a real date rather than
          an optimistic one.</dd>
    </div>
    <div class="record__row">
      <dt>Not supplied</dt>
      <dd>Investigational product, and anything requiring a licence we do not hold. We will say
          so immediately rather than let a request sit.</dd>
    </div>
  </dl>
</section>

<section class="section shell" aria-labelledby="planning">
  <h2 id="planning">If you are planning a course</h2>
  <div class="notice">
    <p class="notice__head">Tell us the whole regimen, not the first cycle</p>
    <p>
      Give us the protocol, the number of cycles and the intended start date and we will secure
      the whole course rather than the first dose. Cycle-by-cycle ordering is where continuity
      of supply fails in a market dependent on flights, and it is avoidable.
    </p>
  </div>
  <div class="prose">
    <p>
      We will also tell you when a substitution is likely to be needed later in a course, so
      that decision reaches you at the planning stage rather than the morning of a treatment
      day.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--primary" href="<?= e(url('/providers/request-a-quote')) ?>">Request a Quote</a>
    <a class="next-link" href="<?= e(url('/providers/storage-and-handling')) ?>">Storage and handling</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
