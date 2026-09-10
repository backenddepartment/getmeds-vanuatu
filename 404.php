<?php
/**
 * Not found.
 *
 * An empty state that gives direction, not an apology. Somebody who lands here is
 * usually looking for one of four things, so this offers those four rather than
 * saying sorry and stopping.
 */
require __DIR__ . '/includes/bootstrap.php';
require_once INC . '/components.php';

http_response_code(404);

$page = [
    'title'   => 'That page is not here',
    'desc'    => 'The page you asked for does not exist on the Getmeds Vanuatu website.',
    'ref'     => 'GV-404',
    'noindex' => true,
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'That page is not here',
    'The address you used does not match a page on this site. Nothing has gone wrong with '
    . 'your medicine or your enquiry.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <h2 style="margin-top:0">Where people are usually going</h2>
  <?php chunk_links([
      ['label' => 'Ask about a medicine',    'url' => '/enquire',
       'blurb' => 'Two short steps. A pharmacist reads every enquiry.'],
      ['label' => 'For Patients',            'url' => '/patients',
       'blurb' => 'How to order, what you need, prices, delivery.'],
      ['label' => 'Medicines',               'url' => '/medicines',
       'blurb' => 'The kinds of medicine we handle.'],
      ['label' => 'Contact',                 'url' => '/contact',
       'blurb' => 'Phone, address and opening hours.'],
  ]); ?>

  <div class="notice">
    <p class="notice__head">Or just call</p>
    <p>
      <?= phone_link() ?>. <?= e(cfg('hours_long')) ?> If you were part-way through an enquiry,
      quote your reference and a pharmacist will pick it up.
    </p>
  </div>
</div>

<?php include INC . '/footer.php'; ?>
