<?php
/**
 * About Getmeds Vanuatu-Pacific, as one page. Who we are, our pharmacists, our
 * licences, the group behind us and the Pacific Network each have a section
 * with its own anchor; the About Us menu links straight to them. The old
 * sub-page addresses redirect here (the index.php left in each old folder,
 * including /pacific-network). The FAQ stays a page of its own at /faq.
 */
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'About us',
    'desc'  => 'Getmeds Vanuatu-Pacific is a licensed specialty pharmacy in Port Vila, focused on '
             . 'improving access to cancer and essential medicines in Vanuatu and the Pacific.',
    'ref'   => 'GV-ABT',
];

include INC . '/head.php';
include INC . '/header.php';

page_hero([
    'photo'   => 'drawer',
    'pos'     => '50% 50%',
    'kicker'  => 'About Getmeds Vanuatu-Pacific',
    'title'   => 'Improving access to medicines in Vanuatu and the Pacific.',
    'lede'    => 'Our primary specialisation is cancer medicines, supplied against a prescription '
               . 'from local stock or sourced individually for the patient who needs them.',
    'actions' => [
        ['label' => 'Who we are', 'href' => url('/about') . '#who-we-are', 'fill' => true],
        ['label' => 'Pacific Network', 'href' => url('/about') . '#pacific-network'],
    ],
    'points'  => [
        ['shield', 'Licensed in Vanuatu'],
        ['person', 'Registered pharmacists'],
        ['pin',    'Golden Port, ' . cfg('address_city')],
    ],
]);
?>

<?php /* Jump links to each section of this page. */ ?>
<nav class="jumpnav" aria-label="On this page">
  <div class="shell">
    <ul class="jumpnav__list">
      <?php foreach (nav_children('/about') as $item): ?>
      <li><a href="<?= e(url($item['url'])) ?>"><?= e($item['label']) ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>

<div class="section shell" style="margin-top:var(--s-6)">
  <div class="split">
    <div class="split__body">
      <div class="prose">
        <p class="lede" style="max-width:none">
          Getmeds Vanuatu-Pacific is focused on improving access to medicines in Vanuatu and
          the wider Pacific region.
        </p>
        <p>
          Our primary specialisation is cancer medicines. Our broader portfolio can also include
          essential medicines, medical supplies, medical consumables, medical devices and
          equipment, and other healthcare products, sourced and supplied according to what a
          prescriber or facility actually needs.
        </p>
      </div>

      <h2>What we supply</h2>
      <?php chunk_facts([
          ['name' => 'Cancer medicines',
           'desc' => 'Our core focus, both from local stock and sourced individually per patient.'],
          ['name' => 'Essential medicines',
           'desc' => 'Including over-the-counter items, antibiotics, and cardiometabolic medicines '
                   . 'for conditions such as hypertension, diabetes and cholesterol.'],
          ['name' => 'Medical supplies, devices and equipment',
           'desc' => 'Sourced and supplied according to what a prescriber or facility needs.'],
          ['name' => 'Basic monitoring services',
           'desc' => 'Such as blood pressure and blood sugar checks, where offered.'],
      ]); ?>
    </div>
    <div class="split__aside">
      <?php plate('shelves', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>
</div>

<!-- ============================ Who we are ============================ -->
<section class="section shell" id="who-we-are" aria-labelledby="who-we-are-h">
  <h2 id="who-we-are-h">Who we are</h2>
  <div class="split split--flip">
    <div class="split__body">
      <div class="prose">
        <p>
          Getmeds Vanuatu is a licensed specialty pharmacy on the ground floor of Golden Port in
          Port Vila. It is staffed by registered pharmacists. Its work is supplying cancer
          medicines, the medicines that manage their side effects, and other specialty medicines
          that need careful storage or importing.
        </p>
      </div>

      <h3>What we are</h3>
      <ul class="clauses">
        <li>A pharmacy. Licensed in Vanuatu, with a named responsible pharmacist accountable for
            every medicine that leaves the premises.</li>
        <li>A cold-chain operation. Monitored storage, logged, with backup power, because a
            medicine that has been too warm can look perfectly normal and no longer work.</li>
        <li>An importer. Through <?= e(cfg('group_name')) ?>, which buys at a scale that makes
            small Vanuatu quantities viable.</li>
        <li>A phone number somebody answers. For most patients that matters more than anything
            on this website.</li>
      </ul>

      <h3>What we are not</h3>
      <ul class="clauses">
        <li>Not a clinic. We do not diagnose, we do not treat, and we do not advise on how your
            illness should be managed. That is your doctor's work and we will not step into it.</li>
        <li>Not an online shop. There is no cart and no checkout on this site, and there will not
            be. Cancer medicines cannot responsibly be sold that way.</li>
        <li>Not a second opinion. If you disagree with your treatment plan, the conversation to
            have is with your oncologist, not with a pharmacist.</li>
        <li>Not a substitute for the hospital. Some medicines are supplied through the public
            system, and if that is the better route for you we will tell you so.</li>
      </ul>
    </div>
    <div class="split__aside">
      <?php plate('dispensary', [
        'ar'    => '4 / 5',
      ]); ?>
    </div>
  </div>

  <h3>The premises</h3>
  <?= photo_needed('The dispensary counter and the cold-chain refrigeration, Golden Port, Port Vila') ?>
  <div class="prose">
    <p>
      You are welcome to come and look. A pharmacy that asks people to trust its cold chain
      should be willing to show it, and we are.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="value">
  <h2 id="value">What that means for you</h2>
  <?php reason_grid([
      ['icon' => 'box', 'title' => 'Fewer stockouts',
       'text' => 'We keep a wide sourcing network working in the background, so a course of '
               . 'treatment is less likely to stall halfway through because a delivery didn’t '
               . 'arrive.'],
      ['icon' => 'shield', 'title' => 'Someone accountable',
       'text' => 'A named, registered pharmacist checks every order — from the first phone call '
               . 'or enquiry through to the medicine actually in your hand.'],
      ['icon' => 'card', 'title' => 'A fair price, agreed first',
       'text' => 'We aim for the best price we can offer, and we always tell you the real figure '
               . 'before you decide anything. Nothing is ordered until you say yes.'],
  ]); ?>

  <div class="prose" style="margin-top:var(--s-6)">
    <p>
      We do not diagnose, treat, or advise on the management of illness — that is the work of
      the doctor treating you. What we do is make sure the medicine that’s been prescribed is
      genuine, has been stored correctly the whole way, is priced as fairly as we can manage,
      and is here when it’s needed.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="why-getmeds">
  <h2 id="why-getmeds">Why families and hospitals choose us</h2>
  <?php reason_grid([
      ['icon' => 'vial', 'title' => 'Cancer care is what we know',
       'text' => 'It’s our main focus, not one line among many — so we understand what a delay or '
               . 'a shortage actually costs a patient.'],
      ['icon' => 'truck', 'title' => 'We chase down hard-to-find medicine',
       'text' => 'If something isn’t already here, we’ll tell you honestly whether — and how — we '
               . 'can still get it to you.'],
      ['icon' => 'check', 'title' => 'We do what we say',
       'text' => 'The price and the timeline we give you are the real ones, and we follow up '
               . 'rather than leaving you to chase us.'],
      ['icon' => 'person', 'title' => 'Your doctor stays in charge',
       'text' => 'We support the treatment your own doctor has already planned. We never '
               . 'second-guess it, and we never will.'],
      ['icon' => 'ship', 'title' => 'Built for the Pacific, not just Vanuatu',
       'text' => 'A supply line that only works for one country breaks the moment something on '
               . 'that route goes wrong. We’re building more than one.'],
  ]); ?>
</section>

<!-- ========================== Our pharmacists ========================== -->
<section class="section shell" id="our-pharmacists" aria-labelledby="our-pharmacists-h">
  <h2 id="our-pharmacists-h">Our pharmacists</h2>
  <p class="lede">Who dispenses your medicine, what they are accountable for, and how to check they are registered to do it.</p>

  <h3>Responsible pharmacist</h3>
  <dl class="record">
    <div class="record__row">
      <dt>Name</dt>
      <dd><?= val('pharmacist_name') ?></dd>
    </div>
    <div class="record__row">
      <dt>Registration number</dt>
      <dd><span class="num"><?= val('pharmacist_reg_no') ?></span></dd>
    </div>
    <div class="record__row">
      <dt>Accountable for</dt>
      <dd>Every medicine dispensed from these premises, the cold chain, the controlled
          medicines register, and the conduct of the pharmacy.</dd>
    </div>
    <div class="record__row">
      <dt>Pharmacy licence</dt>
      <dd><span class="num"><?= val('pharmacy_licence_no') ?></span>
          <span class="record__sub"><a href="<?= e(url('/about/licences')) ?>">How to verify this</a></span></dd>
    </div>
    <div class="record__row">
      <dt>Premises</dt>
      <dd><?= e(cfg('address_line')) ?><br><?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></dd>
    </div>
    <div class="record__row">
      <dt>Part of</dt>
      <dd><?= e(cfg('group_name')) ?>
          <span class="record__sub"><a href="<?= e(url('/about/part-of-getmeds')) ?>">What that means for supply</a></span></dd>
    </div>
  </dl>

  <div class="prose">
    <p>
      Every medicine leaving this pharmacy is dispensed by a pharmacist registered in Vanuatu.
      One of them is the responsible pharmacist, named on our pharmacy licence and accountable
      under Vanuatu law for every medicine dispensed from these premises, the cold chain, the
      controlled medicines register, and the conduct of the pharmacy.
    </p>
    <p>
      We do not publish the names or photographs of our staff. The licence is the public
      record, and <a href="<?= e(url('/about/licences')) ?>">you can check it</a>. To speak to
      the responsible pharmacist, call us and ask.
    </p>
  </div>

  <?= photo_needed('The responsible pharmacist at the dispensary counter') ?>

  <div class="todo-block">
    <p class="todo-block__head">Biography required</p>
    <p class="todo-block__body">
      Qualifications, years in practice, oncology experience and languages spoken. Supplied by
      the responsible pharmacist. Nothing has been invented here.
    </p>
  </div>

  <h3>Languages</h3>
  <div class="prose">
    <p>
      Vanuatu's official languages are Bislama, English and French, and a medicine schedule is
      hard enough to follow in your first language. Tell us when you call which you would
      rather use and we will find someone who can help.
    </p>
  </div>
  <div class="todo-block">
    <p class="todo-block__head">Confirmation required</p>
    <p class="todo-block__body">
      Which languages the pharmacy can actually offer, and on which days. This must be
      confirmed rather than assumed, because a promise here that cannot be kept is worse than
      no promise.
    </p>
  </div>

  <h3>What a pharmacist will do when you collect</h3>
  <ol class="clauses">
    <li>Check the prescription against the medicine, and check it against anything else you
        have told us you take.</li>
    <li>Go through the schedule with you: how much, how often, with or without food, and what
        to do if you miss one.</li>
    <li>Tell you what side effects are expected, which ones mean call us, and which ones mean
        go to hospital.</li>
    <li>Explain how to store it at home, and how to bring back what you do not use.</li>
  </ol>
  <div class="prose">
    <p class="small quiet">
      Bring someone with you if you can. Two people remember a schedule better than one.
    </p>
  </div>
</section>

<!-- ============================ Our licences ============================ -->
<section class="section shell" id="licences" aria-labelledby="licences-h">
  <h2 id="licences-h">Our licences</h2>
  <p class="lede">What we hold, and how to check it yourself rather than taking our word for it.</p>

  <div class="split">
    <div class="split__body">
      <h3>The pharmacy, on the record</h3>
      <dl class="record">
        <div class="record__row">
          <dt>Registered name</dt>
          <dd><?= val('legal_entity_name') ?></dd>
        </div>
        <div class="record__row">
          <dt>Trading as</dt>
          <dd><?= e(cfg('site_name')) ?> — <?= e(cfg('site_descriptor')) ?></dd>
        </div>
        <div class="record__row">
          <dt>Pharmacy licence</dt>
          <dd><span class="num"><?= val('pharmacy_licence_no') ?></span></dd>
        </div>
        <div class="record__row">
          <dt>Premises licensed</dt>
          <dd><?= e(cfg('address_line')) ?><br><?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></dd>
        </div>
        <div class="record__row">
          <dt>Responsible pharmacist</dt>
          <dd><?= val('pharmacist_name') ?>,
              registration <span class="num"><?= val('pharmacist_reg_no') ?></span>
              <span class="record__sub">A pharmacist registered in Vanuatu is named on the licence and is accountable
              for every medicine dispensed here.</span></dd>
        </div>
        <div class="record__row">
          <dt>Part of</dt>
          <dd><?= e(cfg('group_name')) ?></dd>
        </div>
      </dl>

      <div class="todo-block">
        <p class="todo-block__head">Licence details required</p>
        <p class="todo-block__body">
          Issuing authority, licence category, issue date and expiry date, plus any import
          authorisation held for controlled or cold-chain medicines. Supplied by the responsible
          pharmacist. None of these have been guessed.
        </p>
      </div>
    </div>
    <div class="split__aside">
      <?php plate('script', [
        'ar'    => '4 / 5',
        'cap'   => 'A licence is a document you can ask to see.',
      ]); ?>
    </div>
  </div>

  <h3>How to verify it</h3>
  <div class="prose">
    <p>
      You should not have to trust a website. A licence number on a page proves nothing on its
      own, and any pharmacy asking you to spend money on cancer medicine should expect to be
      checked.
    </p>
  </div>
  <ol class="clauses">
    <li>Ask us for a copy of the licence. We will send it. If a pharmacy will not show you its
        licence, that tells you something.</li>
    <li>Check it with the issuing authority in Vanuatu directly. The registry, not us, is the
        source of truth.</li>
    <li>Come to the premises. The licence is displayed there, as it is required to be.</li>
  </ol>
  <div class="todo-block">
    <p class="todo-block__head">Verification route required</p>
    <p class="todo-block__body">
      The name of the issuing authority in Vanuatu, and the public route for checking a
      pharmacy licence and a pharmacist registration — a register, a phone number or an office.
      This must be accurate, so it has not been drafted here.
    </p>
  </div>

  <div class="notice notice--safety">
    <p class="notice__head">Counterfeit cancer medicine exists, and it kills people</p>
    <p>
      A website offering cancer medicine without a prescription, at a price well below the
      market, shipped from an unnamed country, is not a bargain. Falsified oncology product is
      a documented global problem, and a medicine that contains nothing is indistinguishable
      from a real one until the treatment fails.
    </p>
    <p>
      Ask any supplier for a licence you can verify, a named pharmacist, and a physical address
      you could walk into. Including us.
    </p>
  </div>
</section>

<!-- ========================== Part of Getmeds ========================== -->
<section class="section shell" id="part-of-getmeds" aria-labelledby="part-of-getmeds-h">
  <h2 id="part-of-getmeds-h">Part of Getmeds</h2>
  <p class="lede">Why belonging to a larger group is the reason a small market can get these medicines at all.</p>

  <div class="prose">
    <p>
      Getmeds Vanuatu is part of <?= e(cfg('group_name')) ?>. The practical effect is
      purchasing scale. A single pharmacy in Port Vila ordering one course of a targeted
      therapy is a rounding error to a manufacturer. A group ordering across several markets
      is a customer.
    </p>
    <p>
      That is not a marketing point. It is the mechanism by which a medicine reaches a patient
      in Vanuatu at all, and it is worth being plain about.
    </p>
  </div>

  <h3>What the group provides</h3>
  <dl class="record">
    <div class="record__row">
      <dt>Purchasing</dt>
      <dd>Access to supply at quantities a standalone Vanuatu pharmacy could not order.</dd>
    </div>
    <div class="record__row">
      <dt>Sourcing</dt>
      <dd>Routes to medicines that have never had a Vanuatu distributor.</dd>
    </div>
    <div class="record__row">
      <dt>Cold chain in transit</dt>
      <dd>Validated shipping into Port Vila, rather than each consignment being improvised.</dd>
    </div>
    <div class="record__row">
      <dt>Group companies</dt>
      <dd><?= e(implode(', ', cfg('group_sites'))) ?></dd>
    </div>
  </dl>

  <h3>What stays local</h3>
  <ul class="clauses">
    <li>The pharmacy licence, held in Vanuatu, for these premises.</li>
    <li>The responsible pharmacist, accountable under Vanuatu law for every medicine dispensed
        here.</li>
    <li>Dispensing, counselling and the conversation you have when you collect. Those happen in
        Port Vila, with a person.</li>
    <li>The cold chain from our refrigerator to you.</li>
  </ul>

  <h3>Who you are dealing with</h3>
  <div class="prose">
    <p>
      When you enquire here, a pharmacist in Port Vila reads it. When you collect, you collect
      here. Group membership changes where the medicine is bought, not who is accountable for
      it once it arrives.
    </p>
  </div>
</section>

<!-- ========================== Pacific Network ========================== -->
<section class="section shell" id="pacific-network" aria-labelledby="pacific-network-h">
  <h2 id="pacific-network-h">Pacific Network</h2>
  <p class="lede">Connecting cancer medicine access across the Pacific.</p>

  <div class="split">
    <div class="split__body">
      <div class="prose">
        <p>
          Getmeds Vanuatu-Pacific started in Vanuatu, but the problem it’s built around — small,
          scattered populations that make individual medicine importation slow and expensive —
          is a Pacific-wide one, not just a Vanuatu one. Our longer-term work is building
          partnerships and supply pathways designed to improve access to cancer medicines more
          broadly across the region.
        </p>
        <p>That includes:</p>
      </div>
      <ul class="clauses">
        <li>Working with healthcare providers, hospitals, pharmacies, and government health agencies
            in more than one Pacific country.</li>
        <li>Supporting more reliable sourcing and distribution so a single delayed shipment doesn’t
            stop treatment.</li>
        <li>Helping reduce treatment interruptions caused by medicine shortages.</li>
        <li>Developing a regional network for cancer medicines and other essential medicines,
            rather than a country-by-country one.</li>
        <li>Supporting patients who return home after overseas cancer treatment and need to continue
            their medicines locally, so treatment doesn’t lapse between the last overseas dose and
            the next local one.</li>
      </ul>
    </div>
    <div class="split__aside">
      <?php plate('island', [
        'ar'    => '4 / 3',
      ]); ?>
    </div>
  </div>

  <h3>Where this is heading</h3>
  <div class="prose">
    <p>
      Vanuatu is our founding market. We’re also assessing opportunity and building
      relationships in Fiji, and the countries identified for possible future expansion include
      Solomon Islands, Samoa, Tonga, Nauru and Tuvalu. This is a staged process — country by
      country, partnership by partnership — not a claim that we already operate in all of them.
      Each new country brings its own regulatory pathway, its own hospital and pharmacy
      partners, and its own supply chain to build properly before we say we’re there.
    </p>
  </div>
  <dl class="record" style="margin-top:var(--s-6)">
    <div class="record__row">
      <dt><?= icon('pin') ?>Founding market</dt>
      <dd>Vanuatu</dd>
    </div>
    <div class="record__row">
      <dt><?= icon('ship') ?>Assessing and building relationships</dt>
      <dd>Fiji</dd>
    </div>
    <div class="record__row">
      <dt><?= icon('cal') ?>Identified for possible future expansion</dt>
      <dd>Solomon Islands, Samoa, Tonga, Nauru and Tuvalu</dd>
    </div>
  </dl>

  <h3>Who we’re working with</h3>
  <div class="prose">
    <p>
      Across the region this looks like partnerships with hospitals and clinics, retail
      pharmacies, government health ministries, overseas referral and medical-mission partners,
      and (where useful) regional distributors — so that a patient’s medicine access doesn’t
      depend on one single supply line.
    </p>
  </div>
</section>

<section class="section shell" aria-labelledby="questions">
  <div class="notice">
    <h2 id="questions" class="notice__head" style="margin-top:0">Still have questions?</h2>
    <p>
      Straight answers to the questions people ask us most are on the
      <a class="body-link" href="<?= e(url('/faq')) ?>">FAQ page</a>. Or call a pharmacist on
      <?= phone_link('body-link') ?>. You will speak to a person, not a menu.
    </p>
  </div>

  <div class="actions">
    <a class="btn btn--secondary" href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a>
    <a class="next-link" href="<?= e(url('/faq')) ?>">Read the FAQ</a>
    <a class="next-link" href="<?= e(url('/contact')) ?>">Contact the pharmacy</a>
  </div>
</section>

<?php include INC . '/footer.php'; ?>