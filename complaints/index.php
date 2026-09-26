<?php
/**
 * Complaints and Feedback (content guide, page 17).
 *
 * Short, respectful, white: the intro, three ways to tell us (call, email,
 * the form on this page), the short form and the three-step "What happens
 * next".
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/forms.php';

$schema = [
    'name'      => ['label' => 'Name', 'type' => 'text', 'required' => true],
    'phone'     => ['label' => 'Phone', 'type' => 'tel', 'required' => true],
    'email'     => ['label' => 'Email', 'type' => 'email'],
    'reference' => ['label' => 'Order or reference', 'type' => 'text'],
    'what'      => ['label' => 'What happened', 'type' => 'textarea', 'required' => true],
    'wish'      => ['label' => 'What would you like us to do', 'type' => 'textarea'],
    'consent'   => ['label' => 'Consent', 'type' => 'consent', 'required' => true,
                    'html' => 'I agree that Getmeds may use these details to respond to my complaint. (<a href="'
                            . e(url('/privacy')) . '">Privacy Policy</a>)'],
];

$state = gform_run('complaint', $schema);

$page = [
    'title'     => 'Complaints and feedback',
    'seo_title' => 'Complaints and Feedback — Getmeds Vanuatu',
    'desc'      => 'Tell us if something went wrong with your order, medicine or service. We listen and respond.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-sec g-bg-white g-sec--tight">
  <div class="g-wrap g-narrow">
    <?php g_crumbs([['Home', '/'], ['Complaints and feedback', null]]); ?>
    <h1>Complaints and feedback</h1>
    <p class="g-lead g-mt-sm">If something went wrong, we would rather hear it from you. Your feedback helps us improve.</p>

    <h2 class="g-mt-lg">How to tell us</h2>
    <div class="g-grid g-grid--3 g-mt g-tellus">
      <a class="g-card g-card--link g-card--compact g-center" href="<?= e(tel_url()) ?>">
        <span class="g-card__icon"><?= gi('phone') ?></span>
        <span class="g-tellus__txt">Call <?= e(cfg('phone')) ?></span>
      </a>
      <a class="g-card g-card--link g-card--compact g-center" href="mailto:<?= e(cfg('email')) ?>">
        <span class="g-card__icon"><?= gi('mail') ?></span>
        <span class="g-tellus__txt">Email <span><?= e(cfg('email')) ?></span></span>
      </a>
      <a class="g-card g-card--link g-card--compact g-center" href="#complaint">
        <span class="g-card__icon"><?= gi('clipboard') ?></span>
        <span class="g-tellus__txt">Use the form on this page</span>
      </a>
    </div>

    <div class="g-mt-lg">
      <?php gform_render('complaint', $schema, $state, [
          'id'      => 'complaint',
          'submit'  => 'Send Feedback',
          'success' => 'Thank you. We have received your complaint. We will confirm this within two working days.',
      ]); ?>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-sec--tight g-pt-0">
  <div class="g-wrap g-narrow">
    <h2>What happens next</h2>
    <div class="g-mt-lg">
      <?php g_steps([
          ['', 'We confirm we have received your complaint within two working days.'],
          ['', 'A senior team member looks into it.'],
          ['', 'We contact you with what we found and what we will do.'],
      ], 'h'); ?>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
