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
    'seo_title' => 'Getmeds Vanuatu — Cancer Medicines and Chemotherapy Pharmacy, Port Vila',
    'desc'      => 'Getmeds Vanuatu helps patients and doctors get cancer medicines and other important medicines in Vanuatu and across the Pacific. Send a prescription to start.',
];

$news = array_slice(news_items(), 0, 3);

include INC . '/head.php';
include INC . '/header.php';
?>

<?php /* Full-screen photo hero. The navbar sits over it, see-through, on this page only
         (includes/header.php, guide.css "Home hero"). */ ?>
<section class="g-homehero g-homehero--split" aria-labelledby="hero-title">
  <div class="g-homehero__media">
    <img src="<?= e(asset('/assets/img/homepagebg.jpg')) ?>"
         width="1920" height="884" alt="" loading="eager" fetchpriority="high" decoding="async">
  </div>
  <?php /* Content sits at the bottom of the photo: headline left, description
           and buttons right, then a rule and the kicker under it. */ ?>
  <div class="g-wrap g-homehero__inner">
    <div class="g-homehero__row">
      <h1 class="g-homehero__title" id="hero-title"><span>Affordable Medicines.</span> <span>Better Access.</span> <span>Stronger Cancer Care.</span></h1>
      <div class="g-homehero__side">
        <p class="g-homehero__lede">Getmeds Vanuatu-Pacific helps patients and healthcare providers access essential and cancer medicines through reliable sourcing, more affordable pricing, and a growing supply network across the Pacific.</p>
        <div class="g-homehero__btns">
          <a class="g-homehero__btn g-homehero__btn--fill" href="<?= e(url('/order')) ?>">Order a Medicine</a>
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
      <img src="<?= e(asset('/assets/img/home-about-1600.jpg')) ?>"
           srcset="<?= e(asset('/assets/img/home-about-800.jpg')) ?> 800w, <?= e(asset('/assets/img/home-about-1600.jpg')) ?> 1600w"
           sizes="(max-width: 1248px) 100vw, 1200px" width="1600" height="905" loading="lazy" decoding="async"
           alt="Getmeds Vanuatu staff around a meeting table during a training session, with a presentation on the screen.">
    </figure>
  </div>
</section>

<section class="g-sec g-bg-white g-cat-sec" aria-labelledby="cat-h">
  <div class="g-wrap">
    <div class="g-split g-cat-head">
      <div>
        <span class="g-label">Our Medicines</span>
        <h2 id="cat-h">What we supply</h2>
      </div>
      <div>
        <p class="g-lead">Getmeds stocks and sources medicines across every major category, from cancer treatment to everyday supplies. Every item is checked by a pharmacist before it reaches you.</p>
        <div class="g-btns g-mt"><?= g_btn('See all medicine groups', url('/medicines'), 'secondary') ?></div>
      </div>
    </div>
    <?php
    $tiles = [
        ['oncology', 'ribbon', 'Cancer medicines', 'Chemotherapy, hormone therapy and other cancer medicines'],
        ['haematology', 'drop', 'Blood disorders', 'Medicines for blood cancers and blood conditions'],
        ['supportive-care', 'heart', 'Supportive care', 'Medicines used alongside cancer treatment, such as anti-sickness medicine'],
        ['diabetes', 'gauge', 'Diabetes', 'Medicines and supplies for people living with diabetes'],
        ['cardiology', 'pulse', 'Heart and blood pressure', 'Medicines for high blood pressure, cholesterol and heart disease'],
        ['renal', 'kidney', 'Kidney and dialysis', 'Medicines used in kidney care and dialysis'],
        ['antibiotics', 'bug', 'Antibiotics', 'Medicines used to treat bacterial infections'],
        ['medical-supplies', 'box', 'Medical supplies', 'Single-use consumables, devices and equipment'],
    ];
    ?>
    <div class="g-catrow">
      <div class="g-catrow__track" id="cat-track">
        <?php foreach ($tiles as $i => [$anchor, $ic, $name, $line]): ?>
        <a class="g-catcard" href="<?= e(url('/medicines') . '#' . $anchor) ?>">
          <h3><?= e($name) ?></h3>
          <p><?= e($line) ?></p>
          <span class="g-catcard__go">
            <span>View medicines</span>
            <span class="g-catcard__arrow" aria-hidden="true"><?= gi('arrow') ?></span>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
      <div class="g-catrow__nav">
        <button class="g-catrow__arrow g-catrow__arrow--prev" type="button" data-scroll="-1" data-scroll-target="cat-track" aria-label="Scroll to previous medicine groups"><?= gi('arrow') ?></button>
        <button class="g-catrow__arrow g-catrow__arrow--next" type="button" data-scroll="1" data-scroll-target="cat-track" aria-label="Scroll to more medicine groups"><?= gi('arrow') ?></button>
      </div>
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
      <div class="g-btns g-mt"><?= g_btn('How we keep access affordable', url('/affordable-access'), 'secondary') ?></div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white g-split-sec" aria-labelledby="split-h">
  <div class="g-wrap">
    <h2 id="split-h" class="g-sr">Enquiries for healthcare professionals and for patients</h2>
    <div class="g-grid g-grid--2 g-home-split">
      <img class="g-home-split__img" src="<?= e(asset('/assets/img/families.png')) ?>" width="1200" height="630" loading="lazy" decoding="async" alt="For doctors, hospitals and pharmacies: send us a prescription, a treatment protocol or a product list. We will reply with availability and a quotation.">
      <img class="g-home-split__img" src="<?= e(asset('/assets/img/patients.png')) ?>" width="1200" height="630" loading="lazy" decoding="async" alt="For patients and families: you do not need to know medical words to ask us for help. Send us your prescription and tell us how to reach you.">
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
    <div class="g-home-faq__list">
      <?php
      $homeFaq = [
          ['What is Named Patient Supply?',
           'It is a way to get a prescribed medicine that is not normally available in Vanuatu, ordered for one named patient. Not every medicine can be supplied this way.'],
          ['Do I need a prescription?',
           "Yes, for all prescription medicines. Cancer medicines must follow a doctor's prescription or treatment protocol. We only supply what your doctor prescribed."],
          ['How does pricing work?',
           'Prices depend on the medicine, amount and source. We give you a clear quotation before you commit. You pay nothing until you agree.'],
          ['How long does supply take?',
           'It depends on whether the medicine is in stock, needs to be imported, or must be specially sourced. We give you the earliest possible date when we quote.'],
          ['Can patients from other Pacific Islands access Getmeds?',
           'We are working to expand access across the Pacific and welcome enquiries from other Pacific countries. Contact us with your country and the medicine you need, and we will tell you what is possible.'],
      ];
      ?>
      <?php foreach ($homeFaq as $i => [$q, $a]): ?>
      <details class="g-home-faq__item"<?= $i === 0 ? ' open' : '' ?>>
        <summary>
          <span><?= e($q) ?></span>
          <span class="g-home-faq__toggle" aria-hidden="true"><?= gi('chev-down') ?></span>
        </summary>
        <p><?= e($a) ?></p>
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
    </div>
    <div class="g-home-cta__btns">
      <a class="g-home-cta__btn g-home-cta__btn--white" href="<?= e(request_url('medicine')) ?>">Request a Medicine</a>
      <a class="g-home-cta__btn g-home-cta__btn--dark" href="<?= e(tel_url()) ?>"><?= gi('phone') ?><span>Call <?= e(cfg('phone')) ?></span></a>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
