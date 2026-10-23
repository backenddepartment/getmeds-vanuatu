<?php
/**
 * Contact (content guide, page 15) — enquiry-hub layout.
 *
 * Top to bottom: a light gradient hero (text left, diamond photo collage
 * right), the four ways to reach us, one big enquiry card where an "I am a"
 * selector (the medicines Send Inquiry people types) swaps between five
 * separate forms (each asks only for what it needs), the Golden Port location with the map, a "before you
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
             . e(url('/privacy')) . '" data-modal>Privacy Policy</a>)',
];

/* The enquiry types: the same four people as the Send Inquiry menu on the
   medicines pages (shop-listing.php $AUDIENCES), plus Something Else. Each is
   its own form with its own gform kind, so each asks only the questions that
   person needs. 'kind' must stay unique per form; 'enquiry' and 'quotation'
   keep their historic names so the log and email subjects read the same as
   before. */
$forms = [
    'patient' => [
        'option' => 'Patient / Caregiver',
        'title'  => 'Medicine enquiry for a patient',
        'kind'   => 'enquiry',
        'submit' => 'Send Medicine Enquiry',
        'schema' => [
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
            'hospital' => ['label' => 'Treating doctor or hospital', 'type' => 'text',
                           'help' => 'Where the treatment is planned or happening'],
            'prescription' => ['label' => 'Upload prescription', 'type' => 'file',
                               'help' => 'A clear photo or PDF. We need this before we can supply a prescription medicine'],
            'date_needed'  => ['label' => 'Date needed', 'type' => 'date',
                               'help' => 'For cancer treatment, the date of the next cycle'],
            'message'      => ['label' => 'Message', 'type' => 'textarea', 'help' => 'Anything else we should know'],
            'consent'      => [],
        ],
    ],
    'doctor' => [
        'option' => 'Doctor / Healthcare Professional',
        'title'  => 'Enquiry from a doctor or healthcare professional',
        'kind'   => 'doctor-enquiry',
        'submit' => 'Send Enquiry',
        'schema' => [
            'name'     => ['label' => 'Full name', 'type' => 'text', 'required' => true],
            'role'     => ['label' => 'Role or specialty', 'type' => 'text',
                           'help' => 'For example oncologist, GP or nurse'],
            'facility' => ['label' => 'Hospital or clinic', 'type' => 'text'],
            'phone'    => ['label' => 'Phone or WhatsApp number', 'type' => 'tel', 'required' => true,
                           'help' => 'Include your country code, for example +678'],
            'email'    => ['label' => 'Email', 'type' => 'email', 'required' => true],
            'country'  => ['label' => 'Country', 'type' => 'select', 'required' => true, 'options' => []],
            'medicine' => ['label' => 'Medicine name and strength', 'type' => 'text', 'required' => true,
                           'help' => 'For example the brand or generic name, and the dose'],
            'prescription' => ['label' => 'Upload prescription or treatment protocol', 'type' => 'file',
                               'help' => 'A clear photo or PDF. A pharmacist reviews it before anything is supplied'],
            'message'  => ['label' => 'Message', 'type' => 'textarea',
                           'help' => 'Quantities, timing, or anything else about the patient\'s supply'],
            'consent'  => [],
        ],
    ],
    'pharmacy' => [
        'option' => 'Pharmacy Owner / Retail Pharmacy',
        'title'  => 'Enquiry from a pharmacy',
        'kind'   => 'pharmacy-enquiry',
        'submit' => 'Send Pharmacy Enquiry',
        'schema' => [
            'pharmacy' => ['label' => 'Pharmacy name', 'type' => 'text', 'required' => true],
            'contact'  => ['label' => 'Contact person', 'type' => 'text', 'required' => true],
            'email'    => ['label' => 'Email', 'type' => 'email', 'required' => true],
            'phone'    => ['label' => 'Phone', 'type' => 'tel', 'required' => true],
            'country'  => ['label' => 'Country', 'type' => 'select', 'required' => true, 'options' => []],
            'products' => ['label' => 'Product list (upload)', 'type' => 'file',
                           'help' => 'Photo, scan or PDF of the medicines you want to stock'],
            'notes'    => ['label' => 'Notes', 'type' => 'textarea',
                           'help' => 'Medicines, strengths, quantities and how often you reorder'],
            'consent'  => [],
        ],
    ],
    'hospital' => [
        'option' => 'Hospital / Institution',
        'title'  => 'Request a quotation',
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
    'medicine'     => 'patient',
    'patient'      => 'patient',
    'pacific'      => 'patient',
    'cancer'       => 'patient',
    'nps'          => 'patient',
    'doctor'       => 'doctor',
    'professional' => 'doctor',
    'pharmacy'     => 'pharmacy',
    'hospital'     => 'hospital',
    'quotation'    => 'hospital',
    'other'        => 'other',
];

// Pre-fill from the link that brought the visitor here (whitelisted values only).
$reqType = isset($_GET['type']) && is_string($_GET['type']) ? strtolower(trim($_GET['type'])) : '';
$active  = $typeMap[$reqType] ?? 'patient';
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

<?php /* Full-screen photo hero, same photo, size and layout as the About page's
         hero (the navbar sits over it, see-through: includes/header.php,
         guide.css "Home hero"). */ ?>
<section class="g-homehero g-homehero--split" aria-labelledby="contact-hero-title">
  <div class="g-homehero__media">
    <img src="<?= e(asset('/assets/img/aboutusbg.jpg')) ?>"
         width="1920" height="881" alt="" loading="eager" fetchpriority="high" decoding="async">
    <div class="g-homehero__scrim" aria-hidden="true"></div>
  </div>
  <div class="g-wrap g-homehero__inner">
    <div class="g-homehero__row">
      <h1 class="g-homehero__title" id="contact-hero-title">One team for medicines, quotations and partnerships</h1>
      <div class="g-homehero__side">
        <p class="g-homehero__lede">Send us a prescription, a medicine question or a product list. The more detail you share, the faster we can come back with availability, price and next steps. A pharmacist checks every medicine request.</p>
        <div class="g-homehero__btns">
          <a class="g-homehero__btn g-homehero__btn--fill" href="#enquiry">Send Us Your Enquiry</a>
          <a class="g-homehero__btn g-homehero__btn--line" href="<?= e(tel_url()) ?>">Talk to Our Team</a>
        </div>
      </div>
    </div>
    <div class="g-homehero__foot">
      <p class="g-homehero__kicker">Contact Us</p>
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
        <label for="enq-type">I am a</label>
        <select id="enq-type">
          <?php foreach ($forms as $key => $f): ?>
          <option value="<?= e($key) ?>"<?= $key === $active ? ' selected' : '' ?>><?= e($f['option']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php foreach ($forms as $key => $f): ?>
      <div class="g-iform__panel<?= $key === $active ? ' is-active' : '' ?>" data-panel="<?= e($key) ?>">
        <h3 class="g-iform__ptitle"><?= e($f['title']) ?></h3>
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
         data-smooth (guide.js) animates open/close and keeps one card open. */ ?>
<section class="g-sec g-pt-0 g-know" aria-labelledby="know-h">
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
    <div class="g-know__cards" data-smooth>
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
          <p>Choose who you are — a patient or caregiver, a doctor, a pharmacy, or a hospital — and the form changes to ask only for what that request needs. Not sure? Pick Something Else, or just call us.</p>
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
