<?php
/**
 * /policies — one page replacing nine.
 *
 * It replaces /privacy, /terms, /disclaimer, /prescription-policy,
 * /shipping-rules, /returns, /patient-safety, /report-side-effect and
 * /complaints. Six of those nine had no content at all: they were a heading
 * list wrapped in "Legal copy required" boxes, and a customer who clicked
 * Privacy Policy got eleven grey markers and no answer.
 *
 * What is written here describes what this pharmacy and this website actually
 * do, which is verifiable and therefore safe to state. It is not a substitute
 * for the formal instruments — a privacy policy and terms of use drafted for
 * Vanuatu law — and those still have to be written by a lawyer, now more than
 * before, because /order accepts uploaded prescriptions. That belongs in the
 * project notes, not shouted at a patient mid-order, so it is in README.md.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';
require_once INC . '/icons.php';

$page = [
    'title' => 'Policies and safety',
    'desc'  => 'Prescriptions, safety, side effects, complaints, privacy, delivery and '
             . 'returns at Getmeds Vanuatu.',
    'ref'   => 'GV-POL',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Policies and safety',
    'The rules we work to, and how to raise something with us.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <nav aria-label="On this page">
    <ul class="chunks">
      <li><a href="#safety"><span class="chunk__name">Patient safety</span><?= icon('arrow', 'chunk__go') ?></a></li>
      <li><a href="#prescriptions"><span class="chunk__name">Prescriptions</span><?= icon('arrow', 'chunk__go') ?></a></li>
      <li><a href="#side-effects"><span class="chunk__name">Report a side effect</span><?= icon('arrow', 'chunk__go') ?></a></li>
      <li><a href="#complaints"><span class="chunk__name">Complaints</span><?= icon('arrow', 'chunk__go') ?></a></li>
      <li><a href="#privacy"><span class="chunk__name">Your information</span><?= icon('arrow', 'chunk__go') ?></a></li>
      <li><a href="#delivery-rules"><span class="chunk__name">Delivery and import</span><?= icon('arrow', 'chunk__go') ?></a></li>
      <li><a href="#returns"><span class="chunk__name">Returns and disposal</span><?= icon('arrow', 'chunk__go') ?></a></li>
      <li><a href="#terms"><span class="chunk__name">Using this website</span><?= icon('arrow', 'chunk__go') ?></a></li>
    </ul>
  </nav>
</div>

<section class="section shell" aria-labelledby="safety">
  <h2 id="safety">Patient safety</h2>
  <div class="prose">
    <p>
      Getmeds Vanuatu does not replace the advice of a doctor, oncologist, pharmacist or other
      qualified healthcare professional. Prescription medicines — cancer medicines especially —
      should only be used under medical supervision.
    </p>
    <p>
      Do not change your medicine, dose, schedule or chemotherapy protocol without talking to
      your healthcare provider first. If anything on this website is unclear, call a pharmacist
      on <?= phone_link() ?> rather than guessing.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="prescriptions">
  <h2 id="prescriptions">Prescriptions</h2>
  <ul class="clauses">
    <li>We supply prescription medicines only against a valid, current prescription from a
        doctor licensed to prescribe them.</li>
    <li>A clear photograph starts the order. We need the original paper prescription before any
        medicine is handed over, and you keep it afterwards.</li>
    <li>A pharmacist checks the prescription against the medicine, and against anything else
        you have told us you take, before dispensing.</li>
    <li>We do not diagnose, we do not prescribe, and we will not supply a prescription medicine
        without one.</li>
  </ul>
</section>

<section class="section shell" aria-labelledby="side-effects">
  <h2 id="side-effects">Report a side effect</h2>

  <div class="notice notice--safety">
    <p class="notice__head"><?= icon('alert') ?>If it is happening now and it is serious, stop reading</p>
    <p>
      Trouble breathing, swelling of the face, lips or throat, a widespread rash, chest pain,
      collapse, a temperature over 38 degrees during chemotherapy, or bleeding that will not
      stop: go to the emergency department or contact your treating hospital immediately. Do
      not fill in a form and do not wait for a pharmacy to open.
    </p>
  </div>

  <div class="prose" style="margin-top:var(--s-5)">
    <p>
      For anything that is not an emergency, call <?= phone_link() ?> — that is the fastest
      route — or email <a href="mailto:<?= e(cfg('email')) ?>?subject=<?= rawurlencode('Side effect') ?>"><?= e(cfg('email')) ?></a>
      with "side effect" in the subject. Tell the doctor treating you as well: they may need to
      change your treatment, and only they can.
    </p>
  </div>

  <h3>What helps us most</h3>
  <ul class="clauses">
    <li>The medicine name, and the batch number if you still have the box.</li>
    <li>What happened, in your own words. You do not need medical language.</li>
    <li>When it started, and how long after taking the medicine.</li>
    <li>Whether it is still happening, and whether you have stopped the medicine.</li>
    <li>Anything else you were taking at the time, including herbal remedies.</li>
  </ul>
</section>

<section class="section shell" aria-labelledby="complaints">
  <h2 id="complaints">Complaints</h2>
  <div class="prose">
    <p>
      If something went wrong we would rather hear it from you than not hear it at all. You do
      not need to be polite about it, and you do not need to know whose fault it was.
    </p>
  </div>

  <dl class="record">
    <div class="record__row">
      <dt>By phone</dt>
      <dd><?= phone_link('num') ?>
          <span class="record__sub">Ask to speak to the responsible pharmacist.</span></dd>
    </div>
    <div class="record__row">
      <dt>By email</dt>
      <dd><a href="mailto:<?= e(cfg('email')) ?>?subject=<?= rawurlencode('Complaint') ?>"><?= e(cfg('email')) ?></a>
          <span class="record__sub">Put "complaint" in the subject so it is not read as an order.</span></dd>
    </div>
    <div class="record__row">
      <dt>Goes to</dt>
      <dd>The responsible pharmacist
          <span class="record__sub">Accountable for the pharmacy, and for answering you.</span></dd>
    </div>
  </dl>

  <p class="small quiet">
    Complaining does not put your medicine at risk and will not change how you are treated
    here. A complaint about your medical treatment belongs to your hospital, not to us.
  </p>
</section>

<section class="section shell" aria-labelledby="privacy">
  <h2 id="privacy">Your information</h2>
  <div class="prose">
    <p>
      What you send through this website — your name, your phone number, the medicine you
      asked about, and any prescription photograph you upload — is read by a pharmacist and
      used to answer you and to dispense correctly. That is all it is for.
    </p>
  </div>
  <ul class="clauses">
    <li><strong>Prescription uploads are not published.</strong> They are stored on the server
        in a location the web server refuses to serve, under a generated filename, and are not
        reachable by anyone browsing this site.</li>
    <li><strong>We do not need your medical history</strong> through the website, and you
        should not send it. A pharmacist will ask for what they need on the phone.</li>
    <li><strong>We do not sell your information,</strong> and there is no advertising or
        tracking on this site.</li>
    <li><strong>To ask what we hold, or to have it deleted,</strong> call <?= phone_link() ?>
        or email <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>.</li>
  </ul>
</section>

<section class="section shell" aria-labelledby="delivery-rules">
  <h2 id="delivery-rules">Delivery and import</h2>
  <ul class="clauses">
    <li>Medicine travels either on ordinary freight or, where it must stay between 2 and 8
        degrees, in a validated cold box on a specific flight. That limits which days it can
        leave.</li>
    <li>A pharmacist gives you the realistic arrival day for your island, not the best case.</li>
    <li>Where a medicine has to be imported for you, the normal Vanuatu import and regulatory
        requirements apply. They apply to every supplier and every patient equally, and they
        affect how long it takes.</li>
    <li>Injectable and hospital-administered medicines are supplied to the ward or clinic
        giving them, not to you directly.</li>
  </ul>
  <p class="small quiet">
    <a href="<?= e(url('/how-it-works')) ?>#delivery">Collection times and island delivery</a>.
  </p>
</section>

<section class="section shell" aria-labelledby="returns">
  <h2 id="returns">Returns and disposal</h2>
  <ul class="clauses">
    <li>Medicine that has left the pharmacy cannot be returned to stock or resold. That is a
        safety rule, not a commercial one: we cannot vouch for how it was stored.</li>
    <li><strong>Bring unused or expired medicine back to us</strong> rather than putting it in
        household rubbish. Cancer medicines in particular must not go into general waste or
        into water.</li>
    <li>If we dispensed the wrong thing, or something arrived damaged or warm, call us and we
        will put it right. Do not take it.</li>
  </ul>
</section>

<section class="section shell" aria-labelledby="terms">
  <h2 id="terms">Using this website</h2>
  <ul class="clauses">
    <li>This website gives general information about a pharmacy service. It is not medical
        advice, and it must not be used to diagnose, to self-treat, or to change a treatment
        your doctor has planned.</li>
    <li>Sending an order through this site does not create a supply agreement. Nothing is
        ordered and nothing is charged until a pharmacist has spoken to you and you have
        agreed to the price.</li>
    <li>No medicine is sold or paid for on this website. There is no payment page, by design.</li>
    <li>We describe the groups of medicine we handle. Nothing on this site is a claim that a
        particular medicine is in stock — ask, and a pharmacist will tell you.</li>
  </ul>
</section>

<div class="section shell">
  <div class="notice">
    <p class="notice__head">If you need an answer now</p>
    <p>
      Call the pharmacy on <?= phone_link() ?> or email
      <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>. A pharmacist will
      answer you directly.
    </p>
  </div>
</div>

<?php include INC . '/footer.php'; ?>
