<?php
/**
 * Contact (content guide, page 15) — enquiry-hub layout.
 *
 * Top to bottom: a light gradient hero (text left, diamond photo collage
 * right), the four ways to reach us, one big enquiry card where a type
 * selector swaps between five separate forms (each enquiry type asks only
 * for what it needs), the Golden Port location with the map, a "before you
 * reach out" band with gradient accordion cards, the other-reasons cards and
 * the emergency note.
 *
 * Every "Request a Medicine"-style button on the site lands here through
 * request_url($type), which adds ?type=…#enquiry; the type picks which form
 * shows. ?country= and ?medicine= pre-fill those fields. Without JavaScript
 * all five forms show stacked, each under its own heading, and the selector
 * hides; each form posts its own _form kind, so only the submitted one runs.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/forms.php';

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

$consent = static fn(): array => [
    'label' => 'Consent', 'type' => 'consent', 'required' => true,
    'html'  => 'I agree that Getmeds may use these details to respond to my enquiry. (<a href="'
             . e(url('/privacy')) . '">Privacy Policy</a>)',
];

/* The five enquiry types. Each is its own form with its own gform kind, so
   each asks only the questions that type needs. 'kind' must stay unique per
   form; 'enquiry' and 'quotation' keep their historic names so the log and
   email subjects read the same as before. */
$forms = [
    'medicine' => [
        'option' => 'Medicine Enquiry (Patients & Families)',
        'title'  => 'Medicine enquiry',
        'quote'  => '“I need a medicine my doctor prescribed, or I want to know if you can supply it and what it costs.”',
        'kind'   => 'enquiry',
        'submit' => 'Send Medicine Enquiry',
        'schema' => [
            'iam' => ['label' => 'I am', 'type' => 'select', 'required' => true, 'options' => [
                'patient' => 'The patient', 'family' => 'A family member or friend', 'other' => 'Someone else',
            ]],
            'name'    => ['label' => 'Full name', 'type' => 'text', 'required' => true,
                          'help' => 'The name of the person we should contact'],
            'patient' => ['label' => 'Patient name (if different)', 'type' => 'text',
                          'help' => 'Only if you are asking for someone else'],
            'phone'   => ['label' => 'Phone or WhatsApp number', 'type' => 'tel', 'required' => true,
                          'help' => 'Include your country code, for example +678'],
            'email'   => ['label' => 'Email', 'type' => 'email'],
            'country' => ['label' => 'Country', 'type' => 'select', 'required' => true, 'options' => []],
            'island'  => ['label' => 'Island or town', 'type' => 'text',
                          'help' => 'Helps us plan collection or delivery'],
            'medicine' => ['label' => 'Medicine name(s)', 'type' => 'text', 'required' => true,
                           'help' => 'Write it as it appears on the prescription'],
            'prescription' => ['label' => 'Upload prescription', 'type' => 'file',
                               'help' => 'A clear photo or PDF. We need this before we can supply a prescription medicine'],
            'date_needed'  => ['label' => 'Date needed', 'type' => 'date'],
            'message'      => ['label' => 'Message', 'type' => 'textarea', 'help' => 'Anything else we should know'],
            'consent'      => [],
        ],
    ],
    'cancer' => [
        'option' => 'Cancer Medicine Enquiry',
        'title'  => 'Cancer medicine enquiry',
        'quote'  => '“A cancer medicine has been prescribed and I need it supplied in Vanuatu, cycle by cycle.”',
        'kind'   => 'cancer-enquiry',
        'submit' => 'Send Cancer Medicine Enquiry',
        'schema' => [
            'name'    => ['label' => 'Full name', 'type' => 'text', 'required' => true],
            'phone'   => ['label' => 'Phone or WhatsApp number', 'type' => 'tel', 'required' => true,
                          'help' => 'Include your country code, for example +678'],
            'email'   => ['label' => 'Email', 'type' => 'email'],
            'country' => ['label' => 'Country', 'type' => 'select', 'required' => true, 'options' => []],
            'hospital' => ['label' => 'Treating doctor or hospital', 'type' => 'text',
                           'help' => 'Where the treatment is planned or happening'],
            'medicine' => ['label' => 'Cancer medicine name(s)', 'type' => 'text', 'required' => true,
                           'help' => 'As written on the prescription or protocol'],
            'prescription' => ['label' => 'Upload prescription or treatment protocol', 'type' => 'file',
                               'help' => 'A clear photo or PDF. A pharmacist reviews it before anything is supplied'],
            'cycle'   => ['label' => 'Date of the next treatment cycle', 'type' => 'date',
                          'help' => 'So we can plan the supply around your treatment'],
            'message' => ['label' => 'Message', 'type' => 'textarea'],
            'consent' => [],
        ],
    ],
    'nps' => [
        'option' => 'Named Patient Supply',
        'title'  => 'Named Patient Supply enquiry',
        'quote'  => '“The medicine is not normally available in Vanuatu and needs to be sourced for a named patient.”',
        'kind'   => 'nps-enquiry',
        'submit' => 'Send Named Patient Supply Enquiry',
        'schema' => [
            'iam' => ['label' => 'I am', 'type' => 'select', 'required' => true, 'options' => [
                'patient' => 'The patient', 'family' => 'A family member or friend',
                'doctor' => 'A doctor', 'pharmacist' => 'A pharmacist', 'other' => 'Someone else',
            ]],
            'name'    => ['label' => 'Full name', 'type' => 'text', 'required' => true],
            'phone'   => ['label' => 'Phone or WhatsApp number', 'type' => 'tel', 'required' => true,
                          'help' => 'Include your country code, for example +678'],
            'email'   => ['label' => 'Email', 'type' => 'email'],
            'country' => ['label' => 'Country', 'type' => 'select', 'required' => true, 'options' => []],
            'medicine' => ['label' => 'Medicine name and strength', 'type' => 'text', 'required' => true,
                           'help' => 'For example the brand or generic name, and the dose'],
            'doctor'  => ['label' => 'Prescribing doctor', 'type' => 'text', 'required' => true,
                          'help' => 'Named Patient Supply always starts from a prescription'],
            'prescription' => ['label' => 'Upload prescription', 'type' => 'file',
                               'help' => 'A clear photo or PDF'],
            'why'     => ['label' => 'Why is it hard to get? (if you know)', 'type' => 'textarea',
                          'help' => 'For example: not registered here, out of stock, or no local supplier'],
            'consent' => [],
        ],
    ],
    'quotation' => [
        'option' => 'Quotation (Healthcare Professionals)',
        'title'  => 'Request a quotation',
        'quote'  => '“We are a hospital, clinic or pharmacy and need a line-by-line quotation for medicines or supplies.”',
        'kind'   => 'quotation',
        'submit' => 'Request a Quotation',
        'schema' => [
            'facility' => ['label' => 'Facility name', 'type' => 'text', 'required' => true],
            'contact'  => ['label' => 'Contact person', 'type' => 'text', 'required' => true],
            'role'     => ['label' => 'Role', 'type' => 'text'],
            'email'    => ['label' => 'Email', 'type' => 'email', 'required' => true],
            'phone'    => ['label' => 'Phone', 'type' => 'tel', 'required' => true],
            'country'  => ['label' => 'Country', 'type' => 'select', 'required' => true, 'options' => []],
            'products' => ['label' => 'Product list or protocol (upload)', 'type' => 'file',
                           'help' => 'Photo, scan or PDF of the product list, tender or treatment protocol'],
            'needed'   => ['label' => 'Date needed', 'type' => 'date'],
            'notes'    => ['label' => 'Notes', 'type' => 'textarea',
                           'help' => 'Quantities, preferred brands, delivery location, tender references'],
            'consent'  => [],
        ],
    ],
    'other' => [
        'option' => 'Something Else',
        'title'  => 'General enquiry',
        'quote'  => '“I have a question that does not fit the other forms.”',
        'kind'   => 'general-enquiry',
        'submit' => 'Send Enquiry',
        'schema' => [
            'name'    => ['label' => 'Full name', 'type' => 'text', 'required' => true],
            'phone'   => ['label' => 'Phone or WhatsApp number', 'type' => 'tel', 'required' => true,
                          'help' => 'Include your country code, for example +678'],
            'email'   => ['label' => 'Email', 'type' => 'email'],
            'message' => ['label' => 'How can we help?', 'type' => 'textarea', 'required' => true],
            'consent' => [],
        ],
    ],
];
foreach ($forms as &$f) {
    if (isset($f['schema']['country'])) { $f['schema']['country']['options'] = $countries; }
    $f['schema']['consent'] = $consent();
}
unset($f);

// request_url() types -> which form shows.
$typeMap = [
    'medicine'     => 'medicine',
    'patient'      => 'medicine',
    'pacific'      => 'medicine',
    'cancer'       => 'cancer',
    'nps'          => 'nps',
    'quotation'    => 'quotation',
    'professional' => 'quotation',
    'other'        => 'other',
];

// Pre-fill from the link that brought the visitor here (whitelisted values only).
$reqType = isset($_GET['type']) && is_string($_GET['type']) ? strtolower(trim($_GET['type'])) : '';
$active  = $typeMap[$reqType] ?? 'medicine';
$prefill = [];
if ($reqType === 'patient') {
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

// Run every form; each only processes a POST whose _form matches its kind.
$states = [];
foreach ($forms as $key => $f) {
    $states[$key] = gform_run($f['kind'], $f['schema'], $prefill);
}
// A posted form becomes the one on screen, so its errors or thank-you show.
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    foreach ($forms as $key => $f) {
        if (($_POST['_form'] ?? '') === $f['kind']) { $active = $key; }
    }
}

$page = [
    'title'     => 'Contact us',
    'seo_title' => 'Contact Getmeds Vanuatu — Port Vila Pharmacy',
    'desc'      => 'Contact Getmeds Vanuatu in Port Vila about cancer medicines, Named Patient Supply '
                 . 'or other medicines. Patients and healthcare professionals welcome.',
];

include INC . '/head.php';
include INC . '/header.php';

?>

<?php /* Light gradient hero: label, two-tone headline and the two actions on
         the left, a diamond photo collage on the right (guide.css
         "Contact enquiry hub"). */ ?>
<section class="g-chero" aria-labelledby="contact-hero-title">
  <div class="g-wrap g-chero__grid">
    <div class="g-chero__text">
      <span class="g-label">Contact Us</span>
      <h1 id="contact-hero-title"><em>One team</em> for medicines, quotations and partnerships</h1>
      <p class="g-chero__lede">Send us a prescription, a medicine question or a product list. The more detail you share, the faster we can come back with availability, price and next steps. A pharmacist checks every medicine request.</p>
      <div class="g-btns g-chero__btns">
        <a class="g-btn g-btn--grad" href="#enquiry">Send Us Your Enquiry</a>
        <?= g_call_btn('Talk to Our Team', 'secondary') ?>
      </div>
    </div>
    <div class="g-chero__collage" aria-hidden="true">
      <span class="g-chero__dia g-chero__dia--1"><img src="<?= e(asset('/assets/img/team-vanuatu-800.jpg')) ?>" alt="" loading="eager" decoding="async"></span>
      <span class="g-chero__dia g-chero__dia--2"><img src="<?= e(asset('/assets/img/photo/dispensary-480.jpg')) ?>" alt="" loading="lazy" decoding="async"></span>
      <span class="g-chero__dia g-chero__dia--3"><img src="<?= e(asset('/assets/img/photo/island-480.jpg')) ?>" alt="" loading="lazy" decoding="async"></span>
    </div>
  </div>
</section>

<section class="g-sec g-sec--tight g-bg-white g-ways" aria-labelledby="ways-h">
  <div class="g-wrap">
    <span class="g-label">Contact Details</span>
    <h2 id="ways-h">Ways to reach us</h2>
    <div class="g-ways__grid g-mt">
      <div class="g-ways__item">
        <h3><span class="g-ways__badge"><?= gi('phone') ?></span> Phone</h3>
        <ul>
          <li><?= gi('phone') ?><a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a></li>
          <li><?= gi('clock') ?><span>Monday to Friday, 8am to 5pm</span></li>
        </ul>
      </div>
      <div class="g-ways__item">
        <h3><span class="g-ways__badge"><?= gi('chat') ?></span> WhatsApp</h3>
        <ul>
          <li><?= gi('chat') ?><a href="<?= e(whatsapp_url()) ?>">Message us on WhatsApp</a></li>
          <li><?= gi('image') ?><span>A photo of the prescription is enough to start</span></li>
        </ul>
      </div>
      <div class="g-ways__item">
        <h3><span class="g-ways__badge"><?= gi('mail') ?></span> Email</h3>
        <ul>
          <li><?= gi('mail') ?><a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a></li>
          <li><?= gi('clock') ?><span>Answered within one working day — not for anything urgent</span></li>
        </ul>
      </div>
      <div class="g-ways__item">
        <h3><span class="g-ways__badge"><?= gi('pin') ?></span> Visit us</h3>
        <ul>
          <li><?= gi('pin') ?><span>Ground Floor, Room 1006, Golden Port, Namba 2 Area, Port Vila</span></li>
          <li><?= gi('building') ?><span>Ground floor, no stairs</span></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php /* The enquiry card: one type selector, five separate forms. JS shows the
         form matching the selector; without JS all five show, each under its
         own heading, and the selector hides. */ ?>
<section class="g-sec g-iform-sec" id="enquiry" aria-labelledby="iform-h">
  <div class="g-wrap g-iform-grid">
    <aside class="g-cloc g-oreasons" aria-labelledby="oreasons-h">
      <span class="g-label">More Help</span>
      <h2 id="oreasons-h">Other reasons to get in touch</h2>
      <div class="g-oreasons__list">
        <a href="<?= e(url('/report-a-side-effect')) ?>">
          <h3>Report a side effect</h3>
          <p>Something happened after taking a medicine we supplied.</p>
        </a>
        <a href="<?= e(url('/complaints')) ?>">
          <h3>Make a complaint</h3>
          <p>If something went wrong, we want to hear it.</p>
        </a>
        <a href="<?= e(url('/policies-and-safety')) ?>#returns">
          <h3>Return or dispose of medicine</h3>
          <p>Unused cancer medicine must not go in household rubbish.</p>
        </a>
      </div>
    </aside>
    <div class="g-iform">
      <span class="g-label">Enquiry Form</span>
      <h2 id="iform-h">Send us your enquiry</h2>
      <div class="g-field g-iform__type">
        <label for="enq-type">Enquiry type</label>
        <select id="enq-type">
          <?php foreach ($forms as $key => $f): ?>
          <option value="<?= e($key) ?>"<?= $key === $active ? ' selected' : '' ?>><?= e($f['option']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php foreach ($forms as $key => $f): ?>
      <div class="g-iform__panel<?= $key === $active ? ' is-active' : '' ?>" data-panel="<?= e($key) ?>">
        <h3 class="g-iform__ptitle"><?= e($f['title']) ?></h3>
        <p class="g-iform__quote"><?= e($f['quote']) ?></p>
        <?php gform_render($f['kind'], $f['schema'], $states[$key], [
            'id'      => 'form-' . $key,
            'submit'  => $f['submit'],
            'success' => 'Thank you. We have received your enquiry. Our team will contact you within one working day. If your medicine is urgent, please call us.',
            'warn'    => true,
        ]); ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php /* Before-you-send band: intro left, four gradient accordion cards right.
         data-single (guide.js) keeps one card open at a time. */ ?>
<section class="g-sec g-know" aria-labelledby="know-h">
  <div class="g-wrap g-know__grid">
    <div class="g-know__intro">
      <span class="g-know__dots" aria-hidden="true"><i></i><i></i></span>
      <span class="g-label">Before You Send</span>
      <h2 id="know-h">What to know before you reach out</h2>
      <p>Four short notes on sending a prescription, requesting a quotation, choosing the right form and what happens next.</p>
      <div class="g-btns g-know__btns">
        <a class="g-btn g-btn--grad" href="#enquiry">Send an Enquiry</a>
        <?= g_call_btn('Talk to Our Team', 'secondary') ?>
      </div>
    </div>
    <div class="g-know__cards" data-single>
      <details class="g-know__card g-know__card--1" open>
        <summary>
          <span class="g-know__kicker">Request a Medicine</span>
          <span class="g-know__title">Send the prescription first</span>
          <span class="g-know__toggle" aria-hidden="true"></span>
        </summary>
        <div class="g-know__body">
          <p>The fastest route: the medicine name and a clear photo or PDF of the prescription. A pharmacist checks availability, the supply route and the price, and we reply with the next steps. Prescription medicines are never supplied without one.</p>
        </div>
      </details>
      <details class="g-know__card g-know__card--2">
        <summary>
          <span class="g-know__kicker">Request a Quotation</span>
          <span class="g-know__title">Line-by-line quotations</span>
          <span class="g-know__toggle" aria-hidden="true"></span>
        </summary>
        <div class="g-know__body">
          <p>Hospitals, clinics and pharmacies: share the product names, strengths, quantities and the delivery location — a list or tender document is perfect. You receive a line-by-line quotation your team can review and compare.</p>
        </div>
      </details>
      <details class="g-know__card g-know__card--3">
        <summary>
          <span class="g-know__kicker">Enquiry Routes</span>
          <span class="g-know__title">The right form for every enquiry</span>
          <span class="g-know__toggle" aria-hidden="true"></span>
        </summary>
        <div class="g-know__body">
          <p>Each enquiry type has its own short form that asks only for what that request needs — a patient medicine enquiry, a cancer medicine, Named Patient Supply, a facility quotation, or anything else. Not sure? Pick any form, or just call us.</p>
        </div>
      </details>
      <details class="g-know__card g-know__card--4">
        <summary>
          <span class="g-know__kicker">Next Steps</span>
          <span class="g-know__title">What happens after you reach out</span>
          <span class="g-know__toggle" aria-hidden="true"></span>
        </summary>
        <div class="g-know__body">
          <p>We confirm we have received your enquiry, a pharmacist reviews any prescription, and we contact you with availability, price and next steps — within one working day. If a medicine is urgent, call us instead.</p>
        </div>
      </details>
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

<?php /* The type selector swaps which form panel shows. The server already
         marks the right panel active (from ?type= or the posted form), so
         this only handles changes after load. */ ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var sel = document.getElementById('enq-type');
  if (!sel) { return; }
  var panels = document.querySelectorAll('.g-iform__panel');
  sel.addEventListener('change', function () {
    Array.prototype.forEach.call(panels, function (p) {
      p.classList.toggle('is-active', p.getAttribute('data-panel') === sel.value);
    });
  });
});
</script>

<?php include INC . '/footer.php'; ?>
