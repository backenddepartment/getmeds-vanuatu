<?php
/**
 * Condition page template (content guide, page 08).
 *
 * All 53 pages under /conditions/<slug>/ use this one template so they read as
 * one system. Each page file sets $slug and includes this; the copy comes from
 * data/conditions.php, word for word from the guide.
 *
 * Layout: breadcrumb and short hero (H1, one-line summary, one calm icon), then
 * an 8/12 main column of separate H2 blocks and a 4/12 sticky "Need this
 * medicine?" card. On a phone the card follows the main column, so it lands at
 * the end of the page; the global sticky bottom bar carries the buttons.
 * Cancer and blood pages take the Care Pink accent, the rest Getmeds Blue.
 */

$slug = isset($slug) ? (string) $slug : '';
$cond = null;
$group = null;
foreach (require APP_ROOT . '/data/conditions.php' as $g) {
    foreach ($g['conditions'] as $c) {
        if ($c['slug'] === $slug) {
            $cond = $c;
            $group = $g;
            break 2;
        }
    }
}

// Unknown slug: 404. The site's 404.php requires bootstrap itself (not
// require_once), so it cannot be included from here without redeclaring
// every helper; answer with the status and a short pointer to the hub.
if ($cond === null) {
    http_response_code(404);
    $page = [
        'title'   => 'That page is not here',
        'desc'    => 'The page you asked for does not exist on the Getmeds Vanuatu website.',
        'noindex' => true,
    ];
    include INC . '/head.php';
    include INC . '/header.php';
    ?>
    <section class="g-hero g-hero--short g-hero--sky">
      <div class="g-wrap">
        <h1>That page is not here</h1>
        <p class="g-hero__lede">The address you used does not match a condition page.</p>
        <div class="g-btns"><?= g_btn('Medicine access by condition', url('/conditions'), 'primary', 'arrow') ?></div>
      </div>
    </section>
    <?php
    include INC . '/footer.php';
    return;
}

/** The related medicine group labels the guide uses, and their /medicines anchors. */
$anchors = [
    'Oncology'                => 'oncology',
    'Haematology'             => 'haematology',
    'Anaemia'                 => 'anaemia',
    'Supportive care'         => 'supportive-care',
    'Hormonal therapy'        => 'hormonal-therapy',
    'Diabetes'                => 'diabetes',
    'Cardiology'              => 'cardiology',
    'Renal'                   => 'renal',
    'Bone health'             => 'bone-health',
    'Antibiotics'             => 'antibiotics',
    'Pain management'         => 'pain-management',
    'Anti-inflammatory'       => 'anti-inflammatory',
    'Allergy and respiratory' => 'allergy-respiratory',
];

$name  = $cond['name'];
$pink  = $cond['accent'] === 'pink';
// Inside a sentence the name reads in lower case ("Need a medicine for breast
// cancer?"); Hodgkin is a person's name and keeps its capital.
$lower = preg_match('/^(Hodgkin|Non-Hodgkin)/', $name) ? $name : lcfirst($name);
// Names that are plural read "What are …?".
$plural = in_array($slug, ['brain-tumours', 'childhood-cancers', 'bacterial-infections',
    'serious-infections', 'allergies', 'blood-clots', 'mouth-sores', 'myeloproliferative-disorders'], true);

// The one-line summary under the H1 is the first sentence of "What is it?".
$summary = preg_match('/^.+?[.!?](?=\s|$)/u', $cond['what'], $m) ? $m[0] : $cond['what'];

$page = [
    'title'     => $name,
    'seo_title' => $name . ' Medicines in Vanuatu — Getmeds Vanuatu',
    'desc'      => 'Information about access to medicines for ' . $lower
                 . ' in Vanuatu and the Pacific. Send your prescription to Getmeds Vanuatu to check availability.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<div class="g-cond <?= $pink ? 'g-accent-pink' : 'g-accent-blue' ?>">

<section class="g-hero g-hero--short g-hero--sky g-cond-hero">
  <div class="g-wrap">
    <?php g_crumbs([['Home', '/'], ['Conditions', '/conditions'], [$name, null]]); ?>
    <div class="g-cond-hero__row">
      <span class="g-cond-icon"><?= gi($group['icon']) ?></span>
      <div>
        <h1><?= e($name) ?></h1>
        <p class="g-hero__lede"><?= e($summary) ?></p>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-cond-body">
  <div class="g-wrap g-split g-split--8-4">

    <div class="g-cond-main">
      <div class="g-cond-block">
        <h2><?= $plural ? 'What are ' : 'What is ' ?><?= e($lower) ?>?</h2>
        <p><?= e($cond['what']) ?></p>
      </div>

      <div class="g-cond-block">
        <h2>How it is usually treated</h2>
        <p><?= e($cond['treated']) ?></p>
      </div>

      <div class="g-cond-block">
        <h2>How Getmeds can help</h2>
        <p><?= e($cond['help']) ?></p>
      </div>

      <div class="g-cond-block">
        <h2>Related medicines</h2>
        <ul class="g-chips g-cond-tags" role="list">
          <?php foreach ($cond['groups'] as $label): ?>
          <li><a class="g-chip<?= $pink ? ' g-chip--pink' : '' ?>" href="<?= e(url('/medicines') . '#' . ($anchors[$label] ?? '')) ?>"><?= gi('pill') ?><?= e($label) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="g-cond-block">
        <h2>Named Patient Supply</h2>
        <p><?= e($cond['nps']) ?></p>
        <p><a class="g-link-arrow" href="<?= e(url('/named-patient-supply')) ?>">Named Patient Supply<?= gi('arrow') ?></a></p>
      </div>

      <div class="g-cond-block">
        <?php g_box('safety', 'This page gives general information only. It is not medical advice. Please speak to a doctor, nurse or pharmacist about your own health. Do not start, stop or change a medicine without their advice.', 'Important note'); ?>
      </div>

      <div class="g-cond-block g-bg-grad g-wave g-cta g-cta--card">
        <h2>Need a medicine for <?= e($lower) ?>?</h2>
        <p>Getmeds may be able to assist with access to appropriate medicines for this condition. Contact the Getmeds team to check availability and supply requirements.</p>
        <div class="g-btns g-btns--center">
          <?= g_btn('Request a Medicine', request_url('medicine'), 'primary') ?>
          <?= g_call_btn('Talk to Our Team', 'white') ?>
        </div>
      </div>
    </div>

    <aside class="g-cond-side" aria-label="Need this medicine?">
      <div class="g-card g-sidecard">
        <h2 class="g-h3">Need this medicine?</h2>
        <div class="g-btns g-mt-sm">
          <?= g_btn('Request a Medicine', request_url('medicine'), 'primary') ?>
          <?= g_call_btn('Talk to Our Team', 'secondary') ?>
        </div>
        <p class="g-cond-side__phone"><?= gi('phone') ?><?= phone_link() ?></p>
      </div>
    </aside>

  </div>
</section>

</div>

<?php include INC . '/footer.php'; ?>
