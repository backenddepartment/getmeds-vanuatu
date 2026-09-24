<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Contact',
    'desc'  => 'Phone, email, address and opening hours for Getmeds Vanuatu in Port Vila.',
    'ref'   => 'GV-CON',
];

include INC . '/head.php';
include INC . '/header.php';

page_hero([
    'photo'   => 'notes',
    'pos'     => '50% 50%',
    'kicker'  => 'Contact',
    'title'   => 'Talk to a pharmacist.',
    'lede'    => 'The phone is the fastest way to reach us. You will speak to a person, not a menu.',
    'actions' => [
        ['label' => 'Call ' . cfg('phone'), 'href' => 'tel:' . cfg('phone_href'), 'icon' => 'phone', 'fill' => true],
        ['label' => 'Email us', 'href' => 'mailto:' . cfg('email'), 'icon' => 'mail'],
    ],
    'points'  => [
        ['mail',   cfg('email')],
        ['pin',    'Golden Port, ' . cfg('address_city')],
        ['script', 'Pharmacist replies'],
    ],
]);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="notice">
    <p class="notice__head">Call the pharmacy</p>
    <p style="font-size:var(--t-1);font-weight:700;line-height:1.1;margin:0 0 var(--s-3)">
      <a href="tel:<?= e(cfg('phone_href')) ?>" class="num"><?= e(cfg('phone')) ?></a>
    </p>
    <p style="margin:0"><?= e(cfg('hours_long')) ?> You will speak to a person, not a menu.</p>
  </div>

  <?php /* Contact details are things you act on, not facts you read, so each
           row is the target itself: the whole row is the tap area, and it clears
           48px. A definition list made the phone number a 20px-tall link. */ ?>
  <ul class="channels">
    <li>
      <a class="channels__row" href="tel:<?= e(cfg('phone_href')) ?>">
        <?= icon('phone') ?>
        <span>
          <span class="channels__name">Phone</span>
          <span class="channels__val num"><?= e(cfg('phone')) ?></span>
          <span class="channels__sub"><?= e(cfg('hours_long')) ?></span>
        </span>
      </a>
    </li>
    <li>
      <a class="channels__row" href="mailto:<?= e(cfg('email')) ?>">
        <?= icon('mail') ?>
        <span>
          <span class="channels__name">Email</span>
          <span class="channels__val"><?= e(cfg('email')) ?></span>
          <span class="channels__sub">Answered within one working day. Do not use email
            for anything urgent.</span>
        </span>
      </a>
    </li>
    <li>
      <a class="channels__row" href="<?= e(url('/order')) ?>">
        <?= icon('script') ?>
        <span>
          <span class="channels__name">Written enquiry</span>
          <span class="channels__val">Ask about a medicine</span>
          <span class="channels__sub">Two short steps. A pharmacist reads every one.</span>
        </span>
      </a>
    </li>
    <li>
      <div class="channels__row">
        <?= icon('pin') ?>
        <span>
          <span class="channels__name">Address</span>
          <span class="channels__val"><?= e(cfg('address_line')) ?></span>
          <span class="channels__sub"><?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></span>
        </span>
      </div>
    </li>
    <li>
      <div class="channels__row">
        <?= icon('clock') ?>
        <span>
          <span class="channels__name">Opening hours</span>
          <span class="channels__val"><?= e(cfg('hours_short')) ?></span>
          <span class="channels__sub"><?= e(cfg('hours_long')) ?></span>
        </span>
      </div>
    </li>
  </ul>
</div>

<section class="section shell" aria-labelledby="finding">
  <h2 id="finding">Finding us</h2>
  <div class="prose">
    <p>
      We are on the ground floor of Golden Port, in the Namba 2 area of Port Vila, in room
      1006. Ground floor means no stairs, which matters if you are coming in tired.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="emergency">
  <h2 id="emergency">If it is an emergency</h2>
  <div class="notice notice--safety">
    <p class="notice__head">Do not contact a pharmacy in an emergency</p>
    <p>
      A temperature over 38&nbsp;degrees during or after chemotherapy, vomiting you cannot
      stop, trouble breathing, or bleeding that will not stop: contact your treating hospital
      or go to the emergency department now. We keep office hours and an enquiry may sit
      overnight. Do not spend an emergency waiting for us.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="other-reasons">
  <h2 id="other-reasons">Other reasons to get in touch</h2>
  <?php chunk_links([
      ['label' => 'Report a side effect', 'url' => '/report-side-effect',
       'blurb' => 'Something that happened after taking a medicine we supplied.'],
      ['label' => 'Make a complaint', 'url' => '/complaints',
       'blurb' => 'If something went wrong, we would rather hear it from you than not.'],
      ['label' => 'Return or dispose of medicine', 'url' => '/returns',
       'blurb' => 'Unused cancer medicine must not go in household rubbish.'],
  ]); ?>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/order')) ?>">Ask About a Medicine</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
