<?php
/**
 * FAQ (content guide, page 14).
 *
 * A search box that filters the questions live (guide.js data-filter), six
 * small group headings, accordions that open smoothly with a turning arrow, one
 * answer open at a time (data-smooth), FAQPage structured data, and the
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

// Every question, and this page's FAQPage schema, from includes/faq.php: the
// accordions and the schema are word for word the same. The home page's own
// six featured questions are shown here too but are in its schema, not this
// one, so no question is in two pages' schema.
require_once INC . '/faq.php';
$faq = faq_groups();
$page['head'] = faq_schema('faq');

include INC . '/head.php';
include INC . '/header.php';
?>

<?php /* Laid out like the search results: the title, a line of text, the
         question search and an illustration on the left (staying in view),
         the questions on the right. */ ?>
<section class="g-sec g-bg-white g-sec--tight g-faqp">
  <div class="g-wrap g-faqp__split">
    <aside class="g-faqp__side">
      <?php g_crumbs([['Home', '/'], ['FAQ', null]]); ?>
      <h1>Frequently asked questions</h1>
      <p class="g-faqp__lede">Quick answers about our medicines, prescriptions, cancer care, Pacific access, pricing and safety.</p>
      <div class="g-bigsearch g-mt" role="search">
        <?= gi('search') ?>
        <label class="g-sr" for="faq-search">Search the questions</label>
        <input type="search" id="faq-search" data-filter="#faq-list" placeholder="Search the questions" autocomplete="off">
      </div>
      <?php /* A question bubble pops up, an answer bubble types back (three
               dots), then its lines fill in. Still when motion is reduced. */ ?>
      <svg class="g-fx" viewBox="0 0 280 220" aria-hidden="true" focusable="false">
        <ellipse class="g-fx-shadow" cx="140" cy="206" rx="100" ry="7"/>
        <g class="g-fx-q">
          <path class="g-fx-qbox" d="M44 24h132a18 18 0 0 1 18 18v28a18 18 0 0 1-18 18H82l-20 16v-16h-18a18 18 0 0 1-18-18V42a18 18 0 0 1 18-18z"/>
          <text class="g-fx-qmark" x="52" y="67">?</text>
          <rect class="g-fx-qline" x="82" y="44" width="88" height="8" rx="4"/>
          <rect class="g-fx-qline" x="82" y="60" width="62" height="8" rx="4"/>
        </g>
        <g class="g-fx-a">
          <path class="g-fx-abox" d="M104 112h132a18 18 0 0 1 18 18v38a18 18 0 0 1-18 18h-18v16l-20-16h-94a18 18 0 0 1-18-18v-38a18 18 0 0 1 18-18z"/>
          <g class="g-fx-dots"><circle cx="150" cy="149" r="5"/><circle cx="168" cy="149" r="5"/><circle cx="186" cy="149" r="5"/></g>
          <rect class="g-fx-aline g-fx-aline--1" x="112" y="132" width="116" height="8" rx="4"/>
          <rect class="g-fx-aline g-fx-aline--2" x="112" y="146" width="98" height="8" rx="4"/>
          <rect class="g-fx-aline g-fx-aline--3" x="112" y="160" width="70" height="8" rx="4"/>
          <g class="g-fx-tick"><circle cx="230" cy="168" r="10"/><path d="M225 168l3.5 3.5 6.5-6.5"/></g>
        </g>
      </svg>
    </aside>

    <div class="g-faqp__main">
    <div id="faq-list" class="g-faq" data-smooth>
      <?php $n = 0; foreach ($faq as $group => $items): ?>
      <div class="g-faq__group" data-filter-group>
        <h2 class="g-faq__head"><?= e($group) ?></h2>
        <div class="g-acc">
          <?php foreach ($items as $item): $n++; ?>
          <details class="g-acc__item" id="q<?= $n ?>" data-filter-item>
            <summary><?= e($item['q']) ?></summary>
            <div class="g-acc__body"><?= faq_answer($item) ?></div>
          </details>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
      <p class="g-hidden g-muted g-mt" data-filter-empty role="status">No questions match your search. Try another word, or call us on <a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a>.</p>
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
  </div>
</section>

<?php include INC . '/footer.php'; ?>
