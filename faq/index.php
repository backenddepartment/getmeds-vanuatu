<?php
/**
 * FAQ. From the 2026 content brief. Native <details>, so every answer opens
 * without JavaScript and each question is its own keyboard stop.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'FAQ',
    'desc'  => 'Answers to common questions about Getmeds Vanuatu-Pacific: medicines, Named Patient '
             . 'Access, prices, prescriptions and where we serve.',
    'ref'   => 'GV-FAQ',
];

include INC . '/head.php';
include INC . '/header.php';

page_open(
    'Frequently asked questions',
    'Straight answers to the questions people ask us most. If yours isn’t here, call a pharmacist.'
);
?>

<div class="section shell" style="margin-top:var(--s-6);border-top:0;padding-top:0">
  <div class="faq">
    <details>
      <summary>What medicines does Getmeds provide?</summary>
      <div class="faq__a prose">
        <p>
          Cancer medicines are our primary specialisation. We also support essential medicines,
          medical supplies, medical consumables, devices, equipment, and other healthcare
          products.
        </p>
      </div>
    </details>

    <details>
      <summary>Can I request a cancer medicine that isn’t available locally?</summary>
      <div class="faq__a prose">
        <p>
          Yes — this is called Named Patient Access. Patients and healthcare providers can
          contact us to check sourcing and availability, subject to a valid prescription and the
          applicable regulatory and supply requirements. See the
          <a href="<?= e(url('/patients/named-patient-access')) ?>">Named Patient Access</a>
          page for how the process works.
        </p>
      </div>
    </details>

    <details>
      <summary>Are Getmeds medicines affordable?</summary>
      <div class="faq__a prose">
        <p>
          We focus on reduced-cost and competitive pricing wherever we can offer it. The actual
          price depends on the specific medicine, how it’s sourced, freight, regulatory
          requirements, and other applicable costs — we’ll always confirm the real figure with
          you before you commit to anything.
        </p>
      </div>
    </details>

    <details>
      <summary>Do I need a prescription?</summary>
      <div class="faq__a prose">
        <p>
          Yes, for any prescription medicine, including every cancer medicine we supply. We
          don’t sell medicine directly from this website.
        </p>
      </div>
    </details>

    <details>
      <summary>Can Getmeds help patients returning from overseas cancer treatment?</summary>
      <div class="faq__a prose">
        <p>
          Yes. Supporting patients after overseas treatment — helping with continued access to
          cancer medicines locally, and general awareness and education — is part of what we’re
          building toward in Vanuatu.
        </p>
      </div>
    </details>

    <details>
      <summary>Does Getmeds serve only Vanuatu?</summary>
      <div class="faq__a prose">
        <p>
          Vanuatu is where we started and where our pharmacy operates. Our longer-term direction
          is to extend medicine access and distribution across the Pacific region — see
          <a href="<?= e(url('/pacific-network')) ?>">Pacific Network</a>.
        </p>
      </div>
    </details>
  </div>

  <div class="notice">
    <p class="notice__head"><?= icon('phone') ?>Still have a question?</p>
    <p>
      Call a pharmacist on <?= phone_link('body-link') ?>. <?= e(cfg('hours_long')) ?> You will
      speak to a person, not a menu.
    </p>
  </div>
</div>

<?php include INC . '/footer.php'; ?>
