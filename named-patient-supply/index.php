<?php
/**
 * /named-patient-supply — Content & Design Guide, page 05.
 *
 * Explains a new idea to people who have never heard of it: lots of white
 * space, one idea per section, plain icons, and an "In simple words" line in
 * the white hero, under the lede. The old /patients/named-patient-access redirects here.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Named Patient Supply',
    'seo_title' => 'Named Patient Supply in Vanuatu — Getmeds Vanuatu',
    'desc'      => 'Named Patient Supply helps a patient get a prescribed medicine that is not normally available in Vanuatu. Learn how it works and how Getmeds can help.',
];

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-hero g-bg-white g-pg g-nps-hero">
  <div class="g-wrap g-split g-split--7-5">
    <div>
      <span class="g-label">Hard-to-Find Medicines</span>
      <h1>Named Patient Supply</h1>
      <p class="g-hero__lede">Getting a medicine that is not normally available in Vanuatu, for one named patient.</p>
      <p class="g-nps-simple"><strong>In simple words:</strong> If your doctor prescribes a medicine that Vanuatu does not normally have, Getmeds can try to bring it in just for you. We need your prescription. We tell you the price and time before you pay.</p>
      <div class="g-btns">
        <?= g_btn('Make a Named Patient Supply Enquiry', request_url('nps')) ?>
        <?= g_call_btn('Call Us') ?>
      </div>
    </div>
    <div class="g-hero__media g-nps-illus">
      <img src="<?= e(asset('/assets/img/namedpatient.webp')) ?>" width="900" height="847" alt="Smiling patients of different ages, some in hospital gowns and head scarves, shown in a collage of rounded photos." decoding="async">
    </div>
  </div>
</section>

<?php /* "What it means" and "Why it exists" side by side (eyebrow, black title,
         text), then one full-width image under both: the connection banner
         (doctor, medicine, route to Vanuatu, patient) under a blue tint, with a
         heartbeat lifeline running across it (guide.css "NPS meaning columns"). */ ?>
<section class="g-sec g-bg-white g-nps-first" aria-label="What Named Patient Supply means and why it exists">
  <div class="g-wrap g-nps-cols">
    <div class="g-nps-col" aria-labelledby="mean-h">
      <span class="g-label">The Basics</span>
      <h2 id="mean-h">What does Named Patient Supply mean?</h2>
      <p>Some medicines are not usually sold or stocked in Vanuatu. A doctor may still decide a patient needs one of them.</p>
      <p>Named Patient Supply means the medicine is ordered for one patient, by name, based on their doctor's prescription. It is not ordered for general sale.</p>
    </div>
    <div class="g-nps-col" aria-labelledby="why-h">
      <span class="g-label">Why It Matters</span>
      <h2 id="why-h">Why does it exist?</h2>
      <p>Vanuatu is a small market. Many specialist medicines, including many cancer medicines, are not kept in stock. Named Patient Supply gives patients a way to get the medicine their doctor prescribed, even when it is not normally available here.</p>
    </div>
  </div>
  <figure class="g-nps-band">
    <img src="<?= e(asset('/assets/img/connection.jpg')) ?>" width="1736" height="343" loading="lazy" decoding="async" alt="A doctor writing a prescription, a medicine box and bottle, and a dotted route to a map of Vanuatu where a patient looks out over the islands.">
    <svg class="g-nps-band__line" viewBox="0 0 1200 100" preserveAspectRatio="none" aria-hidden="true" focusable="false">
      <path d="M0 50H300l12-14 12 14h20l10 32 16-74 14 64 10-22h16l10 14 10-14H700l12-14 12 14h20l10 32 16-74 14 64 10-22h16l10 14 10-14H1200"/>
      <path class="g-nps-band__pulse" pathLength="1000" d="M0 50H300l12-14 12 14h20l10 32 16-74 14 64 10-22h16l10 14 10-14H700l12-14 12 14h20l10 32 16-74 14 64 10-22h16l10 14 10-14H1200"/>
    </svg>
  </figure>
</section>

<?php /* Same blue-gradient band as the About page safety section, joined
         straight onto the connection image above: eyebrow and title left;
         the uses, the honest limit and "The prescription comes first" right
         (guide.css "About safety band" + "NPS uses band"). */ ?>
<section class="g-sec g-about-safe g-nps-uses" aria-labelledby="when-h">
  <div class="g-wrap g-split g-split--top">
    <div>
      <span class="g-label">When to Use It</span>
      <h2 id="when-h">When may it be used?</h2>
      <div class="g-nps-uses__rx">
        <h3 id="rx-h">The prescription comes first</h3>
        <p>Every Named Patient Supply request needs a valid prescription or treatment protocol from the patient's doctor. For your safety, we only supply the medicine your doctor prescribed.</p>
      </div>
    </div>
    <div class="g-about-safe__text">
      <ul class="g-nps-uses__list" role="list">
        <li>When a medicine is not available in Vanuatu.</li>
        <li>When a medicine is often out of stock.</li>
        <li>When a patient returns from treatment overseas and needs to continue a medicine at home.</li>
        <li>When a doctor prescribes a specialist medicine for a serious condition.</li>
      </ul>
      <p class="g-nps-uses__note">Not every medicine can be supplied this way. Some medicines cannot be exported by the maker, or need papers that are not available. We will tell you honestly what is possible.</p>
    </div>
  </div>
</section>

<?php /* "What information do we need?" and "What patients can expect" side by
         side, styled like the meaning columns above (eyebrow, black title),
         each list with plain blue bullets (guide.css "NPS meaning columns"). */ ?>
<section class="g-sec g-bg-white" aria-label="What we need and what to expect">
  <div class="g-wrap g-nps-cols">
    <div class="g-nps-col" aria-labelledby="need-h">
      <span class="g-label">Before You Start</span>
      <h2 id="need-h">What information do we need?</h2>
      <ul class="g-nps-dots" role="list">
        <li>The patient's full name and contact details</li>
        <li>The doctor's prescription or treatment protocol</li>
        <li>The medicine name, strength and amount</li>
        <li>The doctor's or hospital's name and contact details</li>
        <li>The date the medicine is needed (for example, the next treatment cycle)</li>
        <li>Any medical papers needed for the import approval</li>
      </ul>
    </div>
    <div class="g-nps-col" aria-labelledby="expect-h">
      <span class="g-label">Our Promise</span>
      <h2 id="expect-h">What patients can expect</h2>
      <ul class="g-nps-dots" role="list">
        <li>A clear answer about whether we can source the medicine.</li>
        <li>A price before you pay anything.</li>
        <li>An estimated time. This can be longer than for medicines we keep in stock.</li>
        <li>A pharmacist to explain how to take or handle your medicine.</li>
      </ul>
      <p class="g-nps-missing"><strong>If you do not have everything, contact us anyway. We will tell you what is missing.</strong></p>
    </div>
  </div>
</section>

<?php /* White heading row (eyebrow and black title top left, subtext right),
         then the six steps on the #61A644 green band with glassy numbers
         (guide.css "NPS how band"). */ ?>
<section class="g-sec g-bg-white g-nps-how-head" aria-labelledby="how-h">
  <div class="g-wrap g-split g-split--top g-nps-helps">
    <div class="g-nps-col">
      <span class="g-label">Step by Step</span>
      <h2 id="how-h">How it works</h2>
    </div>
    <p class="g-nps-helps__text">From your first enquiry to the day the medicine is ready, these are the six steps we follow. You will know the price and expected timing before you commit to anything.</p>
  </div>
</section>
<section class="g-sec g-nps-how" aria-label="The six steps">
  <div class="g-wrap">
    <?php g_steps([
        ['Enquiry.', 'The patient, doctor or hospital contacts Getmeds.'],
        ['Prescription check.', 'Our pharmacist reviews the prescription or protocol.'],
        ['Sourcing.', 'We find a supplier and check the paperwork needed.'],
        ['Quotation.', 'We tell you the price and expected timing before you commit.'],
        ['Approval and import.', 'We arrange the import approval and order the medicine.'],
        ['Supply.', 'The medicine is dispensed to the patient, or prepared for the hospital for injectable treatment.'],
    ], 'rows3'); ?>
  </div>
</section>

<?php /* Eyebrow and black title left, the four ways we help as one paragraph
         right (guide.css "NPS meaning columns" + "NPS helps"). */ ?>
<section class="g-sec g-bg-white" aria-labelledby="helps-h">
  <div class="g-wrap g-split g-split--top g-nps-helps">
    <div class="g-nps-col">
      <span class="g-label">Our Role</span>
      <h2 id="helps-h">How Getmeds helps</h2>
    </div>
    <p class="g-nps-helps__text">We work with the doctor to confirm exactly what is needed, then handle the sourcing and the import paperwork for you. While the medicine is on its way, we keep both the patient and the doctor updated. And we plan ahead for the next treatment cycle, so the medicine is ready when it is needed.</p>
  </div>
</section>


<?php /* Same CTA banner as the home, About and How It Works pages (guide.css "g-home-cta"), with this page's text. */ ?>
<section class="g-sec g-home-cta" aria-label="Ask about Named Patient Supply">
  <div class="g-wrap g-home-cta__inner">
    <div class="g-home-cta__text">
      <h2>Ask about Named Patient Supply</h2>
      <p>Send us the prescription and we will check what is possible.</p>
    </div>
    <div class="g-home-cta__btns">
      <a class="g-home-cta__btn g-home-cta__btn--white" href="<?= e(request_url('nps')) ?>">Make a Named Patient Supply Enquiry</a>
      <a class="g-home-cta__btn g-home-cta__btn--dark" href="<?= e(url('/contact')) ?>">Contact Us</a>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
