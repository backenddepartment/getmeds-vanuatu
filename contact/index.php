<?php
/**
 * Contact (content guide, page 15).
 *
 * One screen: the four enquiry types and the enquiry form on the left (7/12),
 * a sticky contact card and the Golden Port map on the right (5/12). On a
 * phone: call button first, then enquiry types, form, details, map.
 *
 * Every "Request a Medicine"-style button on the site lands here through
 * request_url($type), which adds ?type=…#enquiry; the type pre-selects the
 * form's Enquiry type. ?country= and ?medicine= pre-fill those fields too.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/forms.php';

// request_url() types -> the value of the form's Enquiry type option.
$typeMap = [
    'medicine'     => 'medicine',
    'cancer'       => 'cancer',
    'nps'          => 'nps',
    'quotation'    => 'quotation',
    'professional' => 'quotation',
    'patient'      => 'medicine',
    'pacific'      => 'medicine',
    'other'        => 'other',
];

$countries = [
    'vanuatu'         => 'Vanuatu',
    'solomon-islands' => 'Solomon Islands',
    'fiji'            => 'Fiji',
    'samoa'           => 'Samoa',
    'tonga'           => 'Tonga',
    'tuvalu'          => 'Tuvalu',
    'nauru'           => 'Nauru',
    'other-pacific'   => 'Other Pacific country',
    'other'           => 'Other',
];

$schema = [
    'iam' => [
        'label' => 'I am a…', 'type' => 'select', 'required' => true,
        'options' => [
            'patient'  => 'Patient or family member',
            'doctor'   => 'Doctor',
            'hospital' => 'Hospital or clinic',
            'pharmacy' => 'Pharmacy',
            'other'    => 'Other organisation',
        ],
    ],
    'name' => ['label' => 'Full name', 'type' => 'text', 'required' => true,
               'help' => 'The name of the person we should contact'],
    'patient' => ['label' => 'Patient name (if different)', 'type' => 'text',
                  'help' => 'Only if you are asking for someone else'],
    'phone' => ['label' => 'Phone or WhatsApp number', 'type' => 'tel', 'required' => true,
                'help' => 'Include your country code, for example +678'],
    'email' => ['label' => 'Email', 'type' => 'email',
                'help' => 'Optional, but helpful for sending quotations'],
    'country' => ['label' => 'Country', 'type' => 'select', 'required' => true,
                  'help' => 'Vanuatu or another Pacific country', 'options' => $countries],
    'island' => ['label' => 'Island or town', 'type' => 'text',
                 'help' => 'Helps us plan collection or delivery'],
    'enquiry_type' => [
        'label' => 'Enquiry type', 'type' => 'select', 'required' => true,
        'options' => [
            'medicine'  => 'Medicine',
            'cancer'    => 'Cancer medicine',
            'nps'       => 'Named Patient Supply',
            'quotation' => 'Quotation',
            'other'     => 'Other',
        ],
    ],
    'medicine' => ['label' => 'Medicine name(s)', 'type' => 'text',
                   'help' => 'Write it as it appears on the prescription'],
    'prescription' => ['label' => 'Upload prescription or treatment protocol', 'type' => 'file',
                       'help' => 'A clear photo or PDF. We need this before we can supply a prescription medicine'],
    'date_needed' => ['label' => 'Date needed', 'type' => 'date',
                      'help' => 'For example, the date of the next treatment cycle'],
    'message' => ['label' => 'Message', 'type' => 'textarea',
                  'help' => 'Anything else we should know'],
    'consent' => ['label' => 'Consent', 'type' => 'consent', 'required' => true,
                  'html' => 'I agree that Getmeds may use these details to respond to my enquiry. (<a href="'
                          . e(url('/privacy')) . '">Privacy Policy</a>)'],
];

// Pre-fill from the link that brought the visitor here (whitelisted values only).
$reqType = isset($_GET['type']) && is_string($_GET['type']) ? strtolower(trim($_GET['type'])) : '';
$prefill = [];
if (isset($typeMap[$reqType])) {
    $prefill['enquiry_type'] = $typeMap[$reqType];
}
if ($reqType === 'professional') {
    $prefill['iam'] = 'doctor';
} elseif ($reqType === 'patient') {
    $prefill['iam'] = 'patient';
}
if (isset($_GET['country']) && is_string($_GET['country'])) {
    $c = strtolower(trim($_GET['country']));
    $c = (string) preg_replace('/[^a-z]+/', '-', $c);
    foreach ($countries as $key => $label) {
        if ($c === $key || $c === (string) preg_replace('/[^a-z]+/', '-', strtolower($label))) {
            $prefill['country'] = $key;
            break;
        }
    }
}
if (isset($_GET['medicine']) && is_string($_GET['medicine'])) {
    $prefill['medicine'] = trim(mb_substr($_GET['medicine'], 0, 120, 'UTF-8'));
}

$state = gform_run('enquiry', $schema, $prefill);

$page = [
    'title'     => 'Contact us',
    'seo_title' => 'Contact Getmeds Vanuatu — Port Vila Pharmacy',
    'desc'      => 'Contact Getmeds Vanuatu in Port Vila about cancer medicines, Named Patient Supply '
                 . 'or other medicines. Patients and healthcare professionals welcome.',
];

include INC . '/head.php';
include INC . '/header.php';

$cards = [
    ['patient',      'user',      'Patient',              'Patient Enquiry',
     'Need a medicine your doctor prescribed? Start here.'],
    ['professional', 'doctor',    'Healthcare professional', 'Healthcare Professional Enquiry',
     'Doctors, hospitals, clinics and pharmacies: request a quotation.'],
    ['medicine',     'pill',      'Medicine',             'Medicine Enquiry',
     'Check if we can supply a medicine and what it costs.'],
    ['nps',          'user-tag',  'Named Patient Supply', 'Named Patient Supply Enquiry',
     'Need a medicine not normally available in Vanuatu?'],
];
$mapSrc = 'https://www.google.com/maps?q=Golden+Port,+Namba+2,+Port+Vila,+Vanuatu&output=embed';
?>

<section class="g-hero g-hero--sky g-hero--short">
  <div class="g-wrap">
    <?php g_crumbs([['Home', '/'], ['Contact', null]]); ?>
    <h1>Contact us</h1>
    <p class="g-hero__lede">Tell us what you need. A member of our team will reply, and a pharmacist checks every medicine request. The phone is the fastest way to reach us.</p>
    <div class="g-btns g-only-m">
      <?= g_btn('Call ' . cfg('phone'), tel_url(), 'primary', 'phone') ?>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-sec--tight">
  <div class="g-wrap g-split g-split--7-5 g-split--top g-contact">
    <div class="g-contact__main">
      <h2>How can we help?</h2>
      <div class="g-typecards g-mt">
        <?php foreach ($cards as [$type, $ic, $kind, $btn, $text]): ?>
        <a class="g-card g-card--link g-card--compact g-typecard<?= ($reqType === $type) ? ' is-selected' : '' ?>"
           href="<?= e(request_url($type)) ?>" data-set-type="<?= e($type) ?>">
          <span class="g-card__icon"><?= gi($ic) ?></span>
          <h3><?= e($kind) ?></h3>
          <p><?= e($text) ?></p>
          <span class="g-card__go"><?= e($btn) ?> <?= gi('arrow') ?></span>
        </a>
        <?php endforeach; ?>
      </div>

      <h2 class="g-mt-lg" id="send-an-enquiry">Send an enquiry</h2>
      <div class="g-mt">
        <?php gform_render('enquiry', $schema, $state, [
            'id'      => 'enquiry',
            'submit'  => 'Send Enquiry',
            'success' => 'Thank you. We have received your enquiry. Our team will contact you within one working day. If your medicine is urgent, please call us.',
            'intro'   => 'Fill in what you can. If you are not sure, leave it blank and we will ask you. Fields marked * are required.',
            'warn'    => true,
        ]); ?>
      </div>
    </div>

    <aside class="g-contact__side" aria-labelledby="visit-or-call">
      <div class="g-contactcard">
        <div class="g-card">
          <h2 id="visit-or-call" class="g-h3">Visit or call</h2>
          <ul class="g-mt">
            <li><?= gi('pin') ?><div><strong>Address</strong><br>Ground Floor, Room 1006, Golden Port, Namba 2 Area, Port Vila, Shefa, Vanuatu</div></li>
            <li><?= gi('phone') ?><div><strong>Phone</strong><br><a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a></div></li>
            <li><?= gi('mail') ?><div><strong>Email</strong><br><a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a> (answered within one working day; do not use email for anything urgent)</div></li>
            <li><?= gi('clock') ?><div><strong>Opening hours</strong><br>Monday to Friday, 8am to 5pm. Closed on public holidays.</div></li>
            <li><?= gi('building') ?><div><strong>Finding us</strong><br>We are on the ground floor of Golden Port in the Namba 2 area, room 1006. No stairs.</div></li>
          </ul>
        </div>
        <iframe class="g-mapframe" src="<?= e($mapSrc) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                title="Map showing Getmeds Vanuatu at Golden Port, Namba 2 Area, Port Vila"></iframe>
      </div>
    </aside>
  </div>
</section>

<section class="g-sec g-bg-mint g-sec--tight">
  <div class="g-wrap g-narrow">
    <h2 class="g-center">What happens next</h2>
    <div class="g-mt-lg">
      <?php g_steps([
          ['We receive your enquiry.', ''],
          ['A pharmacist reviews any prescription.', ''],
          ['We contact you with availability, price and next steps.', ''],
      ], 'h'); ?>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-sec--tight">
  <div class="g-wrap">
    <h2>Other reasons to get in touch</h2>
    <div class="g-grid g-grid--3 g-mt">
      <a class="g-card g-card--link g-card--compact" href="<?= e(url('/report-a-side-effect')) ?>">
        <span class="g-card__icon"><?= gi('pulse') ?></span>
        <h3>Report a side effect</h3>
        <p>Something happened after taking a medicine we supplied.</p>
        <span class="g-card__go">Report a side effect <?= gi('arrow') ?></span>
      </a>
      <a class="g-card g-card--link g-card--compact" href="<?= e(url('/complaints')) ?>">
        <span class="g-card__icon"><?= gi('chat') ?></span>
        <h3>Make a complaint</h3>
        <p>If something went wrong, we want to hear it.</p>
        <span class="g-card__go">Make a complaint <?= gi('arrow') ?></span>
      </a>
      <a class="g-card g-card--link g-card--compact" href="<?= e(url('/policies-and-safety')) ?>#returns">
        <span class="g-card__icon"><?= gi('refresh') ?></span>
        <h3>Return or dispose of medicine</h3>
        <p>Unused cancer medicine must not go in household rubbish.</p>
        <span class="g-card__go">Returns and safe disposal <?= gi('arrow') ?></span>
      </a>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-sec--tight g-pt-0">
  <div class="g-wrap g-narrow">
    <div class="g-box g-box--emergency" role="note">
      <?= gi('alert') ?>
      <div>
        <h2 class="g-box__head">In an emergency</h2>
        <p>Do not contact a pharmacy in a medical emergency. A fever over 38 °C during or after chemotherapy, vomiting you cannot stop, trouble breathing, or bleeding that will not stop: contact your treating hospital or go to the emergency department now.</p>
      </div>
    </div>
  </div>
</section>

<?php /* The enquiry cards carry the site-wide types (patient, professional…);
         the form's Enquiry type has the guide's five options. guide.js sets the
         select to the card's type; this maps it to the matching option. */ ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var map = <?= json_encode($typeMap) ?>;
  var sel = document.querySelector('select[name="enquiry_type"]');
  var iam = document.querySelector('select[name="iam"]');
  if (!sel) { return; }
  Array.prototype.forEach.call(document.querySelectorAll('[data-set-type]'), function (card) {
    card.addEventListener('click', function () {
      var t = card.getAttribute('data-set-type');
      sel.value = map[t] || '';
      if (iam && iam.value === '') {
        if (t === 'professional') { iam.value = 'doctor'; }
        if (t === 'patient') { iam.value = 'patient'; }
      }
    });
  });
});
</script>

<?php include INC . '/footer.php'; ?>
