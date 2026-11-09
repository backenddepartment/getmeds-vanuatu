<?php
/**
 * Policies and Safety (content guide, page 18).
 *
 * A reference page: a sticky in-page menu on the left on desktop, and each
 * policy a short block with an icon. On a phone and tablet the menu hides and
 * the blocks become accordions (they start closed there, data-mobile-closed;
 * a link to #returns etc. opens its block). Old policy addresses redirect
 * here with the matching anchor. The same policies, as plain text, are the
 * pop-up the footer link opens (the template at the end).
 */
require __DIR__ . '/../includes/bootstrap.php';

// Opens as a pop-up from data-modal links (MODAL_PAGES), and as a full page when opened directly.

$page = [
    'title'     => 'Policies and patient safety',
    'seo_title' => 'Policies and Patient Safety — Getmeds Vanuatu',
    'desc'      => 'How Getmeds Vanuatu keeps medicines safe: prescriptions, pharmacist checks, cold storage, '
                 . 'returns and safe disposal.',
];

// Each policy: [id, icon, title, its text (trusted HTML), a highlighted note
// ([kind, text, icon]) or null]. The page and the pop-up both read this.
$policies = [
    ['prescriptions', 'file', 'Prescriptions',
     'Every prescription medicine needs a valid prescription from a doctor. Cancer medicines must follow a prescription or treatment protocol. We do not sell prescription medicines without one.',
     null],
    ['pharmacist-checks', 'pharmacist', 'Pharmacist checks',
     'A pharmacist checks every prescription for the right medicine, strength and amount before supply, and explains how to take or handle the medicine.',
     null],
    ['cold-chain', 'thermo', 'Storage and cold chain',
     'Medicines are stored as the maker directs.',
     ['info', 'Medicines that must stay between 2 and 8 °C are kept in monitored refrigerators and travel in monitored cold boxes, logged from supplier to patient.', 'thermo']],
    ['returns', 'refresh', 'Returns and safe disposal',
     'For safety, we cannot resell medicines once they have left the pharmacy.',
     ['safety', 'Unused cancer medicines must not go in household rubbish or down the toilet. Bring them back to us in their original pack and we will dispose of them safely.', 'alert']],
    ['your-information', 'lock', 'Your information',
     'We keep your prescription and personal details private and use them only to supply your medicine. See our <a href="' . e(url('/privacy')) . '" data-modal>Privacy Policy</a>.',
     null],
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-hero--sky g-hero--short">
  <div class="g-wrap">
    <?php g_crumbs([['Home', '/'], ['Policies and patient safety', null]]); ?>
    <h1>Policies and patient safety</h1>
  </div>
</section>

<section class="g-sec g-bg-white g-sec--tight">
  <div class="g-wrap g-withside">
    <nav class="g-sidemenu g-sidemenu--blue" aria-label="On this page" data-spy>
      <ul>
        <?php foreach ($policies as [$id, , $title]): ?>
        <li><a href="#<?= e($id) ?>"><?= e($title) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <?php /* Each policy: a card on desktop, an accordion on smaller screens. */ ?>
    <div class="g-acc g-acc--cards g-policies">
      <?php foreach ($policies as [$id, $icon, $title, $text, $note]): ?>
      <details class="g-acc__item g-policy" id="<?= e($id) ?>" open data-mobile-closed>
        <summary><span class="g-acc__title"><?= gi($icon) ?><h2><?= e($title) ?></h2></span></summary>
        <div class="g-acc__body">
          <p><?= $text ?></p>
          <?php if ($note) { g_box($note[0], $note[1], '', $note[2]); } ?>
        </div>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
  <?php /* The text-only version the footer's pop-up shows (guide.js,
           data-modal), so it matches the Privacy Policy pop-up. */ ?>
  <template data-modal-body>
    <h1>Policies and patient safety</h1>
    <?php foreach ($policies as [, , $title, $text, $note]): ?>
    <h2><?= e($title) ?></h2>
    <p><?= $text ?></p>
    <?php if ($note): ?><p><?= e($note[1]) ?></p><?php endif; ?>
    <?php endforeach; ?>
  </template>
</section>

<?php include INC . '/footer.php'; ?>
