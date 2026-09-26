<?php
/**
 * FAQ (content guide, page 14).
 *
 * A search box that filters the questions live (guide.js data-filter), six
 * small group headings, 56px accordions with Getmeds Blue plus/minus, one
 * answer open at a time (data-single), FAQPage structured data, and the
 * "Still have a question?" card. Native <details>: every answer opens without
 * JavaScript.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Frequently asked questions',
    'seo_title' => 'Frequently Asked Questions — Getmeds Vanuatu',
    'desc'      => 'Answers about Getmeds Vanuatu: medicines, prescriptions, Named Patient Supply, '
                 . 'chemotherapy, Pacific access, pricing and how to contact us.',
];

$phoneLink = '<a href="' . e(tel_url()) . '">' . e(cfg('phone')) . '</a>';
$mailLink  = '<a href="mailto:' . e(cfg('email')) . '">' . e(cfg('email')) . '</a>';

// Group => [question, answer]. Answers are plain text, verbatim from the guide.
$faq = [
    'About Getmeds' => [
        ['What is Getmeds Vanuatu?',
         'Getmeds Vanuatu is a pharmacy in Port Vila. Our main focus is cancer medicines. We also supply essential medicines, medical supplies and devices.'],
        ['Where is Getmeds based?',
         'Ground Floor, Room 1006, Golden Port, Namba 2 Area, Port Vila, Vanuatu.'],
    ],
    'Medicines and prescriptions' => [
        ['What medicines are available?',
         'Cancer medicines, supportive medicines for cancer treatment, antibiotics, and medicines for diabetes, blood pressure, cholesterol, heart disease and kidney care. We also supply medical consumables and devices. Stock changes, so please contact the Getmeds team for current availability and requirements.'],
        ['What is Named Patient Supply?',
         'It is a way to get a prescribed medicine that is not normally available in Vanuatu, ordered for one named patient. Not every medicine can be supplied this way.'],
        ['Can patients request medicines directly?',
         'Yes. Patients and families can contact us. For prescription medicines, we need your doctor\'s prescription.'],
        ['Do I need a prescription?',
         'Yes, for all prescription medicines. Cancer medicines must follow a doctor\'s prescription or treatment protocol. We only supply what your doctor prescribed.'],
        ['Can doctors and hospitals contact Getmeds?',
         'Yes. We work with doctors, hospitals, clinics, pharmacies and overseas treatment agencies. Send us your requirement and we will send a quotation.'],
        ['How can I check medicine availability?',
         'Send us the medicine name and prescription by phone, email or the online form. Our pharmacist will check and reply.'],
        ['What information is required?',
         'Usually the patient\'s name and contact details, the prescription or treatment protocol, and the doctor\'s details. For imported medicines we may need more papers.'],
    ],
    'Cancer medicines' => [
        ['Does Getmeds provide chemotherapy medicines?',
         'Yes. Chemotherapy and other cancer medicines are our main specialisation. Injectable chemotherapy is given at a hospital, such as Vila Central Hospital or Vanuatu Private Hospital.'],
        ['What oncology medicines are available?',
         'We supply cancer medicines from stock, by import, or by sourcing on request. We do not list individual medicines online because stock changes. Please contact the Getmeds team for current availability and requirements.'],
    ],
    'Pacific access' => [
        ['Can patients from other Pacific Islands access Getmeds?',
         'We are working to expand access across the Pacific and welcome enquiries from other Pacific countries. Contact us with your country and the medicine you need, and we will tell you what is possible.'],
        ['Do you deliver outside Port Vila?',
         'You can collect in Port Vila, or ask us about delivery to your island. Medicines that need cold storage travel in monitored cold boxes.'],
    ],
    'Price and timing' => [
        ['How does pricing work?',
         'Prices depend on the medicine, amount and source. We give you a clear quotation before you commit. You pay nothing until you agree.'],
        ['How long does supply take?',
         'It depends on whether the medicine is in stock, needs to be imported, or must be specially sourced. We give you the earliest possible date when we quote. Please contact the Getmeds team for current availability and requirements.'],
    ],
    'Contact and safety' => [
        ['How can I contact Getmeds?',
         'Call +678 528 2543 (Monday to Friday, 8am to 5pm), email getmeds.vu@gmail.com, use the online form, or visit us at Golden Port, Port Vila.'],
        ['Can Getmeds tell me which medicine to take?',
         'No. Your doctor decides your treatment. Our pharmacist can explain how to take the medicine your doctor prescribed.'],
        ['What should I do in an emergency?',
         'Go to the nearest hospital emergency department or contact your treating hospital. Do not wait for a reply from the pharmacy.'],
    ],
];

/** The answer as HTML: escaped, with the phone number, email and form made into links. */
$answerHtml = static function (string $a) use ($phoneLink, $mailLink): string {
    return strtr(e($a), [
        e(cfg('phone'))       => $phoneLink,
        e(cfg('email'))       => $mailLink,
        'the online form'     => 'the <a href="' . e(request_url('medicine')) . '">online form</a>',
    ]);
};

// FAQPage structured data (schema.org) with every question and answer.
$ld = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => []];
foreach ($faq as $items) {
    foreach ($items as [$q, $a]) {
        $ld['mainEntity'][] = [
            '@type' => 'Question',
            'name'  => $q,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $a],
        ];
    }
}

include INC . '/head.php';
include INC . '/header.php';
?>
<script type="application/ld+json"><?= json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>

<section class="g-hero g-hero--sky g-hero--short">
  <div class="g-wrap g-narrow">
    <?php g_crumbs([['Home', '/'], ['FAQ', null]]); ?>
    <h1>Frequently asked questions</h1>
    <div class="g-bigsearch g-mt" role="search">
      <?= gi('search') ?>
      <label class="g-sr" for="faq-search">Search the questions</label>
      <input type="search" id="faq-search" data-filter="#faq-list" placeholder="Search the questions" autocomplete="off">
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-sec--tight">
  <div class="g-wrap g-narrow">
    <div id="faq-list" class="g-faq" data-single>
      <?php $n = 0; foreach ($faq as $group => $items): ?>
      <div class="g-faq__group" data-filter-group>
        <h2 class="g-faq__head"><?= e($group) ?></h2>
        <div class="g-acc">
          <?php foreach ($items as [$q, $a]): $n++; ?>
          <details class="g-acc__item" id="q<?= $n ?>" data-filter-item>
            <summary><?= e($q) ?></summary>
            <div class="g-acc__body"><p><?= $answerHtml($a) ?></p></div>
          </details>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
      <p class="g-hidden g-muted g-mt" data-filter-empty role="status">No questions match your search. Try another word, or call us on <?= $phoneLink ?>.</p>
    </div>

    <div class="g-card g-card--mint g-center g-mt-lg g-faq__more">
      <h2>Still have a question?</h2>
      <p class="g-mt-sm">Our team is happy to help.</p>
      <div class="g-btns g-btns--center g-mt">
        <?= g_btn('Contact Us', url('/contact'), 'primary') ?>
        <?= g_btn('Call ' . cfg('phone'), tel_url(), 'secondary', 'phone') ?>
      </div>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
