<?php
/**
 * Home (content guide, page 01).
 *
 * Section order as the guide sets it: hero, trust strip, intro, why, oncology,
 * Named Patient Supply (paired with Across the Pacific), categories,
 * affordability, how it works, who we serve, professionals/patients split,
 * safety, final CTA, footer.
 * The hero keeps the owner's H1, lede and kicker (BUILD-BRIEF, owner decision 2).
 */
require __DIR__ . '/includes/bootstrap.php';
require INC . '/news.php';

$page = [
    'title'     => 'Getmeds Vanuatu',
    'seo_title' => 'Getmeds Vanuatu — Cancer Medicines & Chemotherapy, Port Vila',
    'desc'      => 'Getmeds Vanuatu helps patients and doctors get cancer medicines and other important medicines in Vanuatu and across the Pacific. Send a prescription to start.',
    // The featured FAQs' schema (includes/faq.php): the home page's own six
    // questions only, in <head>; the other two featured ones are in /faq's.
    'head'      => (static function (): string { require_once INC . '/faq.php'; return faq_schema('home'); })(),
];

// Health and medicine headlines only: an off-topic science story from the
// feed never leads the home page (/articles still lists the full feed).
$news = array_slice(array_values(array_filter(news_items(), 'news_is_health')), 0, 3);

include INC . '/head.php';
include INC . '/header.php';
?>

<?php /* Full-screen photo hero. The navbar sits over it, see-through, on this page only
         (includes/header.php, guide.css "Home hero"). */ ?>
<section class="g-homehero g-homehero--split" aria-labelledby="hero-title">
  <div class="g-homehero__media">
    <?php /* WebP copies of homepage.jpg (217 KB): 22, 42 and 106 KB. */ ?>
    <picture>
      <source type="image/webp" srcset="<?= e(asset('/assets/img/homepage-640.webp')) ?> 640w, <?= e(asset('/assets/img/homepage-1024.webp')) ?> 1024w, <?= e(asset('/assets/img/homepage-1920.webp')) ?> 1920w" sizes="100vw">
      <img src="<?= e(asset('/assets/img/homepage.jpg')) ?>"
           width="1920" height="878" alt="" loading="eager" fetchpriority="high" decoding="async">
    </picture>
  </div>
  <?php /* Content sits at the bottom of the photo: headline left, description
           and buttons right, then a rule and the kicker under it. */ ?>
  <div class="g-wrap g-homehero__inner">
    <div class="g-homehero__row">
      <h1 class="g-homehero__title" id="hero-title"><span>Affordable Medicines.</span> <span>Better Access.</span> <span>Stronger Cancer Care.</span></h1>
      <div class="g-homehero__side">
        <p class="g-homehero__lede">Getmeds Vanuatu-Pacific helps patients and healthcare providers access essential and cancer medicines through reliable sourcing, more affordable pricing, and a growing supply network across the Pacific.</p>
        <div class="g-homehero__btns">
          <a class="g-homehero__btn g-homehero__btn--fill" href="<?= e(request_url('medicine')) ?>">Request a Medicine</a>
          <?php /* WhatsApp: a photo of the prescription is the quickest way most
                   people here can ask (audit, 2026-11-04). Same pre-filled
                   message as the phone's bottom bar. */ ?>
          <a class="g-homehero__btn g-homehero__btn--wa" href="<?= e(whatsapp_url('Hello Getmeds, I would like to request a medicine. I will send a photo of my prescription.')) ?>" target="_blank" rel="noopener"><svg class="g-i" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor" stroke="none"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.87 9.87 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.18 8.18 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.25-8.23 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.82c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.24-.64.8-.79.97-.14.16-.29.18-.54.06-.25-.13-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.13-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.47-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/></svg><span>WhatsApp Your Prescription</span></a>
          <a class="g-homehero__btn g-homehero__btn--line" href="<?= e(tel_url()) ?>">Call <?= e(cfg('phone')) ?></a>
        </div>
      </div>
    </div>
    <div class="g-homehero__foot">
      <p class="g-homehero__kicker">Licensed pharmacy · <?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></p>
    </div>
  </div>
</section>

<section class="g-statbar" aria-label="Getmeds Vanuatu at a glance">
  <ul class="g-wrap g-statbar__list">
    <li><span class="g-statbar__num">1st</span><span class="g-statbar__cap">Chemotherapy pharmacy<br>in the Pacific</span></li>
    <li><span class="g-statbar__num">Named</span><span class="g-statbar__cap">Patient access for<br>hard-to-find medicines</span></li>
    <li><span class="g-statbar__num"><?= e(cfg('medicines_count')) ?></span><span class="g-statbar__cap">Affordable medicines<br>available to order</span></li>
    <li><span class="g-statbar__num">83</span><span class="g-statbar__cap">Islands of Vanuatu<br>we work to reach</span></li>
  </ul>
</section>

<section class="g-sec g-bg-white g-intro-sec" aria-labelledby="intro-h">
  <div class="g-wrap g-home-intro">
    <div class="g-home-intro__head">
      <span class="g-label">About Getmeds Vanuatu</span>
      <h2 id="intro-h">A pharmacy built for cancer care in the Pacific</h2>
    </div>
    <div class="g-home-intro__body">
      <div class="g-stack">
        <p>For many people in Vanuatu, getting cancer medicine has meant travelling overseas or waiting for someone to bring it back. Getmeds Vanuatu was set up to change that.</p>
        <p>We are the first chemotherapy pharmacy in the Pacific. We keep cancer medicines in Port Vila, import them when needed, and source harder-to-find medicines for individual patients. We also supply medicines for diabetes, high blood pressure, heart disease and kidney care.</p>
      </div>
      <div class="g-home-intro__actions">
        <p class="g-home-intro__stat">83 islands. One pharmacy working to reach them.</p>
        <a class="g-home-intro__btn" href="<?= e(url('/about')) ?>">More about us</a>
      </div>
    </div>
  </div>
  <div class="g-wrap">
    <figure class="g-home-intro__photo">
      <?php /* WebP copies of home-about (174 KB): 32 and 74 KB. */ ?>
      <picture>
      <source type="image/webp" srcset="<?= e(asset('/assets/img/home-about-800.webp')) ?> 800w, <?= e(asset('/assets/img/home-about-1600.webp')) ?> 1600w" sizes="(max-width: 1248px) 100vw, 1200px">
      <img src="<?= e(asset('/assets/img/home-about-1600.jpg')) ?>"
           srcset="<?= e(asset('/assets/img/home-about-800.jpg')) ?> 800w, <?= e(asset('/assets/img/home-about-1600.jpg')) ?> 1600w"
           sizes="(max-width: 1248px) 100vw, 1200px" width="1600" height="905" loading="lazy" decoding="async"
           alt="Getmeds Vanuatu staff around a meeting table during a training session, with a presentation on the screen.">
      </picture>
    </figure>
  </div>
</section>

<section class="g-sec g-bg-white g-cat-sec" aria-labelledby="cat-h">
  <div class="g-supply">
    <div class="g-supply__media">
      <?php /* WebP copies of medicinecta.png (2.2 MB): 34 KB and 90 KB. */ ?>
      <img src="<?= e(asset('/assets/img/medicinecta-1024.webp')) ?>"
           srcset="<?= e(asset('/assets/img/medicinecta-600.webp')) ?> 600w, <?= e(asset('/assets/img/medicinecta-1024.webp')) ?> 1024w"
           sizes="(max-width: 47.99em) 100vw, 50vw" width="1024" height="1536"
           loading="lazy" decoding="async"
           alt="Getmeds Vanuatu-Pacific medicine bottle with blister packs of capsules and tablets on blue and green blocks.">
      <div class="g-supply__caption">
        <span class="g-supply__eyebrow">Getmeds<br>Product Range</span>
        <p class="g-supply__title">Essential to Advanced Therapies</p>
        <p class="g-supply__meta"><?= count(shop_categories()) ?> therapeutic areas &nbsp;&middot;&nbsp; Stocked, imported and sourced</p>
      </div>
      <a class="g-supply__plus" href="<?= e(url('/medicines')) ?>" >See all groups <span aria-hidden="true">&rarr;</span></a>
    </div>
    <div class="g-supply__body">
      <h2 id="cat-h" class="g-supply__h">
        <span>A portfolio built around</span>
        <strong>Pacific disease patterns.</strong>
      </h2>
      <?php
      /* The Product Range categories, folded into their families:
         "Immunotherapy (Cytokine)" => Immunotherapy. Each chip opens the
         family's own category, or its largest one when it has none. */
      $chips = [];
      foreach (shop_categories() as $slug => [$name, , $n]) {
          $family = trim((string) preg_replace('~\s*(\(.*\)|/\s*Miscellaneous)$~', '', $name));
          $own = shop_slug($family) === $slug;
          if (!isset($chips[$family]) || $own || (!$chips[$family][2] && $n > $chips[$family][1])) {
              $chips[$family] = [$slug, $n, $own];
          }
      }
      ?>
      <div class="g-supply__chips">
        <?php foreach ($chips as $family => [$slug]): ?>
        <a class="g-supply__chip" href="<?= e(url(shop_category_path($slug))) ?>"><?= e($family) ?></a>
        <?php endforeach; ?>
      </div>
      <nav class="g-supply__tabs" aria-label="Medicine shortcuts">
        <a class="is-active" href="<?= e(url('/medicines')) ?>">All Medicines</a>
        <a href="<?= e(url('/how-it-works')) ?>">How It Works</a>
        <a href="<?= e(request_url('medicine')) ?>">Request a Medicine</a>
      </nav>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-why-sec" aria-labelledby="why-h">
  <div class="g-wrap g-why2">
    <div class="g-why2__head">
      <span class="g-label">What sets us apart</span>
      <h2 id="why-h">Why Getmeds Vanuatu</h2>
    </div>
    <div class="g-why2__body">
      <ul class="g-why2__list">
        <li><?= gi('arrow') ?><div><h3>Available</h3><p>Many cancer medicines are kept in stock in Port Vila. Others can be imported or sourced for you.</p></div></li>
        <li><?= gi('arrow') ?><div><h3>Checked</h3><p>A pharmacist checks every prescription before we supply anything.</p></div></li>
        <li><?= gi('arrow') ?><div><h3>More affordable</h3><p>We work to make medicines more affordable to access, so fewer patients have to travel overseas for them.</p></div></li>
        <li><?= gi('arrow') ?><div><h3>Ongoing</h3><p>We plan your next treatment cycle with you, so your medicine is ready when you need it.</p></div></li>
      </ul>
      <p class="g-why2__note">We are the first chemotherapy pharmacy in the Pacific, and we work to reach patients across all 83 islands of Vanuatu.</p>
    </div>
  </div>
</section>

<section class="g-sec g-onc-sec" aria-labelledby="onc-h">
  <div class="g-wrap g-onc">
    <div class="g-onc__body">
      <span class="g-label">Oncology and Chemotherapy</span>
      <h2 id="onc-h">Cancer and chemotherapy medicines</h2>
      <div class="g-stack g-mt">
        <p>Cancer treatment often needs medicine in cycles, over many months. If one cycle is missed or late, treatment can be interrupted.</p>
        <p>Getmeds Vanuatu supplies cancer medicines against a doctor's prescription or treatment protocol. We supply medicines in three ways: in stock, for import, or sourced on request.</p>
        <p>Injectable chemotherapy is given at a hospital. We work with your treating team so the medicine is ready for your session.</p>
      </div>
      <div class="g-btns g-mt"><?= g_btn('Learn about cancer medicines', url('/oncology'), 'primary') ?></div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-nps-sec" aria-labelledby="nps-h">
  <div class="g-nps">
    <div class="g-nps__pacific">
      <span class="g-label">Pacific Islands</span>
      <h2>Across the Pacific</h2>
      <div class="g-stack g-mt">
        <p>Access to important medicines should not depend on where you live. From Port Vila, Getmeds is working to make medicines easier to reach across the Pacific Islands.</p>
        <p>If you are a patient, doctor or hospital outside Vanuatu, contact us and we will tell you what we can do.</p>
      </div>
      <div class="g-btns g-mt"><?= g_btn('Pacific access', url('/pacific-access'), 'primary') ?></div>
    </div>
    <div class="g-nps__body">
      <span class="g-label">Hard-to-Find Medicines</span>
      <h2 id="nps-h">Named Patient Supply</h2>
      <div class="g-stack g-mt">
        <p>Sometimes a doctor prescribes a medicine that is not normally available in Vanuatu. Named Patient Supply is a way to get that medicine for one named patient, based on their prescription.</p>
        <p>Getmeds helps the doctor and patient with the request, the paperwork and the import. Not every medicine can be supplied this way, and we will tell you clearly what is possible.</p>
      </div>
      <div class="g-btns g-mt"><?= g_btn('How Named Patient Supply works', url('/named-patient-supply'), 'primary') ?></div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-mint g-aff-sec" aria-labelledby="aff-h">
  <div class="g-wrap g-split">
    <div>
      <h2 id="aff-h">More affordable access</h2>
      <p class="g-quote g-mt">In Vanuatu, cancer and dialysis medicines are usually paid for by the patient. Travelling overseas for treatment can cost millions of vatu.</p>
    </div>
    <div>
      <p class="g-lead">Getmeds works to lower these barriers. We give you a clear price before you commit to anything.</p>
      <div class="g-btns g-mt"><?= g_btn('How we keep access affordable', url('/about'), 'secondary') ?></div>
    </div>
  </div>
</section>

<?php /* Who is asking: two real HTML cards (they were pictures with the words
         drawn in, which a phone shrank past reading and a screen reader could
         not follow). Each sends its reader to the right form. */ ?>
<section class="g-sec g-bg-white g-split-sec" aria-labelledby="split-h">
  <div class="g-wrap">
    <div class="g-home-split__head">
      <div class="g-home-split__title">
        <span class="g-label">Send an Enquiry</span>
        <h2 id="split-h">Enquiries for healthcare professionals and for patients</h2>
      </div>
      <p class="g-home-split__sub">Choose the option that fits you. Send your prescription, protocol or product list, and our pharmacist will confirm availability and price before you order. <?= e(cfg('reply_time')) ?></p>
    </div>
    <div class="g-home-split">
      <article class="g-home-split__card g-home-split__card--pro" aria-labelledby="split-pro">
        <?php /* Photos cropped from the original enquiry images (families.png,
                 patients.png) without their baked-in text, which is written
                 out as real text beside them instead. Decorative: alt="". */ ?>
        <div class="g-home-split__photo">
          <img src="<?= e(asset('/assets/img/enquiry-pro-photo.webp')) ?>" width="400" height="630" alt="" loading="lazy" decoding="async">
        </div>
        <div class="g-home-split__text">
          <span class="g-home-split__eyebrow">Doctors, hospitals and pharmacies</span>
          <h3 id="split-pro">For doctors &amp; hospitals</h3>
          <p>Send us a prescription, a treatment protocol or a product list. Our pharmacist replies with availability, price and delivery time.</p>
          <div class="g-btns"><?= g_btn('Request a Quotation', url('/healthcare-professionals') . '#quote', 'primary') ?></div>
        </div>
      </article>
      <article class="g-home-split__card g-home-split__card--pat" aria-labelledby="split-pat">
        <div class="g-home-split__photo">
          <img src="<?= e(asset('/assets/img/enquiry-patient-photo.webp')) ?>" width="520" height="646" alt="" loading="lazy" decoding="async">
        </div>
        <div class="g-home-split__text">
          <span class="g-home-split__eyebrow">Patients and families</span>
          <h3 id="split-pat">For patients &amp; families</h3>
          <p>You do not need to know medical words to ask us for help. Send us your prescription and tell us how to reach you.</p>
          <div class="g-btns"><?= g_btn('Request a Medicine', request_url('patient'), 'primary') ?></div>
        </div>
      </article>
    </div>
  </div>
</section>

<?php /* Latest articles: the three newest headlines from /articles, in the same
         cards (includes/news.php). Left out when there are none to show. */ ?>
<?php if ($news): ?>
<section class="g-sec g-bg-white g-home-news" aria-labelledby="news-h">
  <div class="g-wrap">
    <div class="g-home-news__head">
      <div>
        <span class="g-label">Latest Articles</span>
        <h2 id="news-h">Healthcare News</h2>
      </div>
      <div class="g-home-news__side">
        <p>Recent headlines on medicines, cancer care and global health, from independent news outlets.</p>
        <div class="g-btns"><?= g_btn('View all articles', url('/articles'), 'primary') ?></div>
      </div>
    </div>
    <?php /* Headlines are the outlets' own words: left out of the Bislama swap. */ ?>
    <div class="g-articles-grid g-mt" data-no-i18n>
      <?php foreach ($news as $n) { news_card($n); } ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="g-sec g-bg-white g-home-faq" aria-labelledby="faq-h">
  <div class="g-wrap g-home-faq__split">
    <div class="g-home-faq__intro">
      <span class="g-label">FAQ</span>
      <h2 id="faq-h">Medicines and Access Questions</h2>
      <div class="g-btns g-mt">
        <?= g_btn('View all FAQs', url('/faq'), 'primary') ?>
        <?= g_btn('Contact us', url('/contact'), 'secondary') ?>
      </div>
    </div>
    <div class="g-home-faq__list" data-smooth>
      <?php /* The featured questions from includes/faq.php, word for word as in
               the schema; every answer is in the page as it loads. */ ?>
      <?php foreach (faq_home() as $i => $item): ?>
      <details class="g-home-faq__item"<?= $i === 0 ? ' open' : '' ?>>
        <summary>
          <span><?= e($item['q']) ?></span>
          <span class="g-home-faq__toggle" aria-hidden="true"><?= gi('chev-down') ?></span>
        </summary>
        <?= faq_answer($item) ?>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="g-sec g-home-cta" aria-label="Need a medicine? Start with your prescription.">
  <div class="g-wrap g-home-cta__inner">
    <div class="g-home-cta__text">
      <h2>Need a medicine? Start with your prescription.</h2>
      <p>Send it to us and a pharmacist will reply with what we can supply and the price.</p>
      <?php /* The two answers people look for before they ask (FAQ "Price and
               timing"), said here beside the buttons rather than only inside
               the closed accordion above. */ ?>
      <ul class="g-home-cta__facts">
        <li><?= gi('check-circle') ?><span><strong>Price:</strong> a clear quotation before you commit. You pay nothing until you agree.</span></li>
        <li><?= gi('check-circle') ?><span><strong>Timing:</strong> we give you the earliest possible supply date when we quote.</span></li>
      </ul>
      <p class="g-home-cta__hours"><?= gi('clock') ?><?= e(cfg('reply_time')) ?> Open <?= e(cfg('hours_long')) ?></p>
    </div>
    <div class="g-home-cta__btns">
      <a class="g-home-cta__btn g-home-cta__btn--white" href="<?= e(request_url('medicine')) ?>">Request a Medicine</a>
      <a class="g-home-cta__btn g-home-cta__btn--dark" href="<?= e(tel_url()) ?>"><?= gi('phone') ?><span>Call <?= e(cfg('phone')) ?></span></a>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
