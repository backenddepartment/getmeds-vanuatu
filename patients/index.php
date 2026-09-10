<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'For patients and families',
    'desc'  => 'How to order cancer medicine in Port Vila, what papers you need, how prices '
             . 'work, how to reach a pharmacist, and how delivery works.',
    'ref'   => 'GV-PAT',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'For patients and families',
    'Five things people ask us most. Start wherever you are.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="split">
    <div class="split__body">
  <?php chunk_links(nav_children('/patients')); ?>
    </div>
    <div class="split__aside">
      <?php plate('carton', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<section class="section shell" aria-labelledby="just-diagnosed">
  <h2 id="just-diagnosed">If you were diagnosed in the last few days</h2>
  <div class="prose">
    <p>
      You do not have to understand all of this now, and you do not have to do it alone. The
      short version is this. Your doctor writes a prescription. You bring it to us, or send
      us a photograph of it. We tell you whether we have the medicine, what it costs and how
      long it takes. Then you decide.
    </p>
    <p>
      Nothing on this website charges you anything or orders anything. There is no payment
      page here on purpose.
    </p>
  </div>

  <div class="notice">
    <p class="notice__head">The shortest route is the phone</p>
    <p>
      If reading this is too much right now, call <?= phone_link() ?> and talk to a
      pharmacist. <?= e(cfg('hours_long')) ?>. You will speak to a person, not a menu.
    </p>
  </div>
</section>

<?php
parallel_column('The short version', [
    ['en' => 'Your doctor writes a prescription. We supply the medicine.', 'bi' => null],
    ['en' => 'You can bring the prescription to us, or send a photo of it.', 'bi' => null],
    ['en' => 'We will tell you the price and how long it takes before you decide.', 'bi' => null],
    ['en' => 'This website cannot take your money and cannot order for you.', 'bi' => null],
    ['en' => 'You can always call ' . cfg('phone') . ' and talk to a pharmacist.', 'bi' => null],
], 'parallel-patients');
?>

<section class="section shell" aria-labelledby="bring-someone">
  <h2 id="bring-someone">Bringing someone with you</h2>
  <div class="prose">
    <p>
      You are welcome to bring a family member, and most people should. A pharmacist will go
      through the medicine schedule with you, and two people remember it better than one. If
      a relative collects medicine on your behalf, they need to bring the things listed on
      <a href="<?= e(url('/patients/what-you-need')) ?>">What You Need</a>.
    </p>
    <p>
      If English is not the language you are most comfortable in, tell us when you call. We
      will find someone who can help.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/patients/how-to-order')) ?>">Read how ordering works</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
