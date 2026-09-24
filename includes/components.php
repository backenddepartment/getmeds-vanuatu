<?php
/**
 * The small set of repeated page parts. Kept here so the same pattern cannot
 * drift between thirty-one pages.
 */
require_once INC . '/icons.php';

/**
 * A page's opening block: the h1 and one lede line, straight under the
 * masthead with no rule above it (site.css drops the .section rule for the
 * first block in <main>). Every page except the homepage opens with this.
 */
function page_open(string $h1, string $lede = '', string $id = 'page-title'): void
{
    ?>
    <div class="section shell" style="margin-top:0">
      <h1 id="<?= e($id) ?>"><?= e($h1) ?></h1>
      <?php if ($lede !== ''): ?>
      <p class="lede" style="margin-top:var(--s-4)"><?= $lede ?></p>
      <?php endif; ?>
    </div>
    <?php
}

/**
 * A full-screen hero: a photograph under an ink wash, the page's h1, a lede, up
 * to two buttons, and a facts row pinned to the bottom edge (short points, then
 * the phone and hours). The home page and the section landing pages open with
 * this in place of page_open(). Its size and look live in site.css (.hero).
 *
 * $o  photo    string  key in data/images.php; shown as texture, so alt=""
 *     pos      string  object-position for the photo, e.g. '50% 40%'
 *     kicker   string  small label above the heading
 *     title    string  the page's h1
 *     lede     string  one or two sentences, plain text
 *     sub      string  optional second, quieter line under the lede
 *     actions  array   [['label', 'href', 'icon' => name, 'fill' => bool], ...]
 *                      at most one should be 'fill' => true
 *     points   array   [[icon name, text], ...], plain text
 *     short    bool    a compact band instead of a full screen, with no facts
 *                      row; for pages that want an opening image, not a stage
 *     plain    bool    the photograph in its natural colour, with no ink wash
 *                      or duotone over it (the home page asked for this)
 *     facts    bool    false drops the facts row under a full-screen hero
 *                      (default true)
 *     image    array   a one-off image outside the photo library, instead of
 *                      'photo': ['base' => '/assets/img/name', 'widths' =>
 *                      [800, 1600], 'w' => px, 'h' => px], served as JPEG
 *                      copies named name-WIDTH.jpg
 */
function page_hero(array $o): void
{
    $name   = (string) ($o['photo'] ?? '');
    $im     = $name !== '' ? (img_manifest()[$name] ?? null) : null;
    $base   = $im ? url('/assets/img/photo/' . $name) : '';
    $webpOk = true;
    if (!empty($o['image'])) {
        $im     = $o['image'];
        $base   = url((string) $o['image']['base']);
        $webpOk = false;
    }
    $short = !empty($o['short']);
    $plain = !empty($o['plain']);
    $facts = !$short && ($o['facts'] ?? true) !== false;
    ?>
    <section class="hero<?= $short ? ' hero--short' : '' ?><?= $plain ? ' hero--plain' : '' ?><?= (!$short && !$facts) ? ' hero--nofacts' : '' ?>" aria-labelledby="hero-title">
      <?php if ($im):
          $webp = $jpg = [];
          // A version stamp from each file's own mtime, so a replaced photo
          // shows at once instead of the browser's cached copy.
          $ver = static function (string $url): string {
              $file = APP_ROOT . substr($url, strlen(base_path()));
              return $url . (is_file($file) ? '?v=' . filemtime($file) : '');
          };
          foreach ($im['widths'] as $w) {
              $webp[] = e($ver($base . '-' . $w . '.webp')) . ' ' . $w . 'w';
              $jpg[]  = e($ver($base . '-' . $w . '.jpg')) . ' ' . $w . 'w';
          } ?>
      <?php /* The only image above the fold, so the only one loaded eagerly. It
               sits under a heavy wash as texture, so alt is empty. */ ?>
      <div class="hero__media" style="--hero-pos:<?= e((string) ($o['pos'] ?? '50% 50%')) ?>">
        <picture>
          <?php if ($webpOk): ?>
          <source type="image/webp" srcset="<?= implode(', ', $webp) ?>" sizes="100vw">
          <?php endif; ?>
          <img src="<?= e($ver($base . '-' . max($im['widths']) . '.jpg')) ?>"
               srcset="<?= implode(', ', $jpg) ?>" sizes="100vw"
               width="<?= (int) $im['w'] ?>" height="<?= (int) $im['h'] ?>"
               loading="eager" decoding="async" fetchpriority="high" alt="">
        </picture>
      </div>
      <?php endif; ?>

      <div class="hero__inner shell">
        <?php if (!empty($o['kicker'])): ?>
        <p class="hero__kicker"><?= e($o['kicker']) ?></p>
        <?php endif; ?>
        <h1 class="hero__title" id="hero-title"><?= e($o['title']) ?></h1>
        <?php if (!empty($o['lede'])): ?>
        <p class="hero__lede"><?= e($o['lede']) ?></p>
        <?php endif; ?>
        <?php if (!empty($o['sub'])): ?>
        <p class="hero__sub"><?= e($o['sub']) ?></p>
        <?php endif; ?>

        <?php if (!empty($o['actions'])): ?>
        <div class="hero__actions">
          <?php foreach ($o['actions'] as $a): ?>
          <a class="btn <?= !empty($a['fill']) ? 'hero__btn--fill' : 'hero__btn--line' ?>" href="<?= e($a['href']) ?>"><?= !empty($a['icon']) ? icon($a['icon']) : '' ?><?= e($a['label']) ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($facts): ?>
        <?php /* One row: the points, then the phone and hours. It wraps on narrow
                 screens rather than overflowing. */ ?>
        <div class="hero__facts">
          <?php /* Text only, no icons. Each point's icon name is still accepted in
                   $o['points'] so the pages' settings need not change. */ ?>
          <?php if (!empty($o['points'])): ?>
          <ul class="hero__points" role="list">
            <?php foreach ($o['points'] as $p): ?>
            <li><?= e($p[1]) ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
          <p class="hero__contact">
            <span class="hero__contact-item">Call the pharmacy
              <a href="tel:<?= e(cfg('phone_href')) ?>"><?= e(cfg('phone')) ?></a></span>
            <span class="hero__contact-item"><?= e(cfg('hours_short')) ?></span>
          </p>
        </div>
        <?php endif; ?>
      </div>
    </section>
    <?php
}

/**
 * A chunked list of links. This is what this site uses where the category would
 * reach for a grid of identical cards: hairline rows, one ink rule on top.
 *
 * $items: [['label'=>, 'url'=>, 'blurb'=>], ...]
 */
function chunk_links(array $items): void
{
    if (!$items) { return; }
    ?>
    <ul class="chunks">
      <?php foreach ($items as $it): ?>
      <li>
        <a href="<?= e(url($it['url'])) ?>">
          <span class="chunk__name"><?= e($it['label']) ?></span>
          <?php if (!empty($it['blurb'])): ?>
          <span class="chunk__blurb"><?= e($it['blurb']) ?></span>
          <?php endif; ?>
          <?php /* Leans in on hover, the same gesture the door's rule makes. */ ?>
          <?= icon('arrow', 'chunk__go') ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php
}

/**
 * A chunked list that does not link anywhere, used for medicine categories.
 * Nothing here is a stock claim, so nothing here is a link to a product page.
 */
function chunk_facts(array $groups): void
{
    if (!$groups) { return; }
    ?>
    <ul class="chunks">
      <?php foreach ($groups as $g): ?>
      <li>
        <div>
          <span class="chunk__name"><?= e($g['name']) ?></span>
          <span class="chunk__blurb"><?= e($g['desc']) ?></span>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php
}

/**
 * Numbered step cards, for a process a reader follows in order. An <ol>, so the
 * order is announced by a screen reader as well as shown.
 *
 * $steps: [['icon' => name, 'title' => 'Prescription', 'text' => 'plain text'], ...]
 */
function step_cards(array $steps): void
{
    if (!$steps) { return; }
    ?>
    <ol class="stepcards">
      <?php foreach (array_values($steps) as $i => $s): ?>
      <li class="stepcard">
        <div class="stepcard__top">
          <span class="stepcard__num" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
          <span class="cardicon" aria-hidden="true"><?= icon($s['icon']) ?></span>
        </div>
        <h3 class="stepcard__title"><?= e($s['title']) ?></h3>
        <p class="stepcard__text"><?= e($s['text']) ?></p>
      </li>
      <?php endforeach; ?>
    </ol>
    <?php
}

/**
 * A row of link cards: the few places a visitor most likely wants to go next.
 *
 * $items: [['icon' => name, 'label' => 'Find a Medicine', 'url' => '/enquire', 'blurb' => '...'], ...]
 */
function link_cards(array $items): void
{
    if (!$items) { return; }
    ?>
    <ul class="linkcards">
      <?php foreach ($items as $it): ?>
      <li>
        <a class="linkcard" href="<?= e(url($it['url'])) ?>">
          <span class="cardicon" aria-hidden="true"><?= icon($it['icon']) ?></span>
          <span class="linkcard__title"><?= e($it['label']) ?></span>
          <span class="linkcard__blurb"><?= e($it['blurb']) ?></span>
          <span class="linkcard__go" aria-hidden="true">Open <?= icon('arrow') ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php
}

/**
 * A sliding row of large cards, words on the left and a photograph on the
 * right, with previous / next arrows under it. The home page's quick links.
 *
 * Works without JavaScript: the row simply scrolls sideways (and snaps to each
 * card). site.js adds the arrow buttons' behaviour and greys out an arrow at
 * either end. Each whole card is clickable; the title is the one real link and
 * stretches over the card, so a screen reader hears one link per card, not two.
 *
 * $o  id       string  the section's id; the heading is id-head
 *     title    string  the section heading
 *     action   array   ['label' =>, 'href' =>] optional button beside the heading
 *     items    array   [['label', 'url', 'blurb', 'tag', 'cta', 'photo' =>
 *                      library key, 'icon' => house icon name, 'tone' =>
 *                      'blue'|'sky'], ...]
 *
 * Photographs are licensed stock (see plates.php) and decorative: the card's
 * words carry the meaning, so alt is empty. They are shown in colour through
 * color_picture() in plates.php.
 */
function quick_cards(array $o): void
{
    $items = $o['items'] ?? [];
    if (!$items) { return; }
    $id = (string) ($o['id'] ?? 'quick');
    ?>
    <section class="qcards shell" aria-labelledby="<?= e($id) ?>-head">
      <div class="qcards__head">
        <h2 class="qcards__title" id="<?= e($id) ?>-head"><?= e($o['title']) ?></h2>
        <?php if (!empty($o['action'])): ?>
        <a class="qcards__action" href="<?= e($o['action']['href']) ?>"><?= e($o['action']['label']) ?> <?= icon('arrow') ?></a>
        <?php endif; ?>
      </div>

      <ul class="qcards__track" id="<?= e($id) ?>-track" role="list">
        <?php foreach ($items as $it):
            $tone = ($it['tone'] ?? 'blue') === 'sky' ? 'sky' : 'blue';
            $pic  = !empty($it['photo'])
                  ? color_picture($it['photo'], ['class' => 'qcard__img', 'sizes' => '(min-width: 48em) 24rem, 88vw'])
                  : ''; ?>
        <li class="qcard qcard--<?= $tone ?>">
          <div class="qcard__text">
            <?php if (!empty($it['tag'])): ?>
            <span class="qcard__tag"><?= e($it['tag']) ?></span>
            <?php endif; ?>
            <h3 class="qcard__title">
              <a href="<?= e(url($it['url'])) ?>"><?= e($it['label']) ?></a>
            </h3>
            <p class="qcard__blurb"><?= e($it['blurb']) ?></p>
            <span class="qcard__go" aria-hidden="true"><?= e($it['cta'] ?? 'Find out more') ?> <?= icon('arrow') ?></span>
          </div>
          <?php if ($pic !== ''): ?>
          <div class="qcard__media">
            <?= $pic ?>
            <?php if (!empty($it['icon'])): ?>
            <span class="qcard__badge" aria-hidden="true"><?= icon($it['icon']) ?></span>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>

      <div class="qcards__nav">
        <button class="qcards__arrow" type="button" data-dir="-1" aria-controls="<?= e($id) ?>-track">
          <?= icon('arrow') ?><span class="u-hidden">Previous</span>
        </button>
        <button class="qcards__arrow" type="button" data-dir="1" aria-controls="<?= e($id) ?>-track">
          <?= icon('arrow') ?><span class="u-hidden">Next</span>
        </button>
      </div>
    </section>
    <?php
}

/**
 * A grid of short reasons or principles: an icon, a bold lead and one line.
 *
 * $items: [['icon' => name, 'title' => 'Affordable', 'text' => '...'], ...]
 */
function reason_grid(array $items): void
{
    if (!$items) { return; }
    ?>
    <ul class="reasons">
      <?php foreach ($items as $it): ?>
      <li class="reason">
        <span class="cardicon" aria-hidden="true"><?= icon($it['icon']) ?></span>
        <h3 class="reason__title"><?= e($it['title']) ?></h3>
        <p class="reason__text"><?= e($it['text']) ?></p>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php
}

/**
 * The note every Medicines and Patients page ends with: the Patient Safety
 * link, and on Patients pages the Named Patient Access cross-link. Called from
 * footer.php, so no page has to remember to include it.
 */
function section_notes(): void
{
    $meds = in_section('/medicines');
    $pats = in_section('/patients');
    if (!$meds && !$pats) {
        return;
    }
    $showNpa = current_path() !== '/patients/named-patient-access';
    ?>
    <aside class="shell section-notes" aria-label="Patient safety and access">
      <div class="notice">
        <p class="notice__head"><?= icon('shield') ?>Patient safety comes first</p>
        <p>
          We do not replace the advice of your doctor, oncologist or pharmacist. Please do not
          change a medicine, dose or treatment schedule without talking to them first.
          <a class="body-link" href="<?= e(url('/policies') . '#safety') ?>">Read about patient safety</a>.
        </p>
        <?php if ($showNpa): ?>
        <p>
          Need a cancer medicine that is not available locally?
          <a class="body-link" href="<?= e(url('/how-it-works') . '#not-local') ?>">See Named Patient Access</a>.
        </p>
        <?php endif; ?>
      </div>
    </aside>
    <?php
}

/**
 * The medicine enquiry entry. Deliberately not a catalogue search: we do not
 * publish a stock list, so a results page would either be empty or invented.
 * Instead the field opens a conversation, carrying the medicine name with it.
 *
 * This is the site's designed empty state, not a fallback.
 */
function medicine_lookup(string $context = ''): void
{
    // On a static build the field would carry the medicine name to an /enquire
    // page that has no form to receive it. Asking someone to type the name of
    // their cancer medicine and then losing it is worse than not asking.
    if (is_static_build()) {
        form_unavailable('ask about a medicine', 'Medicine enquiry');
        return;
    }
    ?>
    <form class="lookup" method="get" action="<?= e(url('/order')) ?>">
      <div class="lookup__row">
        <div>
          <label for="lookup-medicine">Medicine name</label>
          <input type="text" id="lookup-medicine" name="medicine" autocomplete="off"
                 inputmode="text" spellcheck="false">
        </div>
        <button class="btn btn--primary" type="submit">Ask About a Medicine</button>
      </div>
      <?php if ($context !== ''): ?>
      <input type="hidden" name="from" value="<?= e($context) ?>">
      <?php endif; ?>
      <p class="lookup__note">
        Type the name on your prescription, even if you are not sure you have it right.
        A pharmacist reads every enquiry and will tell you whether we can supply it, what
        it will cost, and how long it takes. Nothing is ordered by sending this.
      </p>
    </form>
    <?php
}

/**
 * What stands in for a form on a host that cannot receive one.
 *
 * A static build has no POST handler. Left alone, the enquiry form would render
 * perfectly, accept everything a frightened person typed about their cancer
 * medicine, and throw it away on submit. That is the worst failure this site
 * could have, so on a static build the form is not rendered at all and the two
 * channels that do work are offered in its place.
 *
 * $what  what they were trying to do, e.g. "ask about a medicine"
 */
function form_unavailable(string $what, string $subject = ''): void
{
    $mailto = 'mailto:' . cfg('email');
    if ($subject !== '') {
        $mailto .= '?subject=' . rawurlencode($subject);
    }
    ?>
    <div class="notice" style="max-width:34rem">
      <p class="notice__head"><?= icon('alert') ?>This form is not available on this site</p>
      <p>
        You can still <?= e($what) ?>, and a pharmacist will answer you the same way. Use
        whichever of these suits you.
      </p>
      <ul class="channels" style="margin-top:var(--s-5)">
        <li>
          <a class="channels__row" href="tel:<?= e(cfg('phone_href')) ?>">
            <?= icon('phone') ?>
            <span>
              <span class="channels__name">Call the pharmacy</span>
              <span class="channels__val num"><?= e(cfg('phone')) ?></span>
              <span class="channels__sub"><?= e(cfg('hours_long')) ?> You will speak to a
                person, not a menu.</span>
            </span>
          </a>
        </li>
        <li>
          <a class="channels__row" href="<?= e($mailto) ?>">
            <?= icon('mail') ?>
            <span>
              <span class="channels__name">Email the pharmacy</span>
              <span class="channels__val"><?= e(cfg('email')) ?></span>
              <span class="channels__sub">Say which medicine, and how to reach you. Answered
                within one working day. Do not use email for anything urgent.</span>
            </span>
          </a>
        </li>
      </ul>
    </div>
    <?php
}

/**
 * The parallel-language column.
 *
 * Vanuatu's official languages are Bislama, English and French, and the gazette
 * this design borrows from is natively parallel-text. This opens a second column
 * beside the English rather than replacing the page.
 *
 * The Bislama is NOT written here. Inventing a translation of medical guidance
 * would be worse than leaving it visibly outstanding. The mechanism is complete;
 * the words are a marked deliverable.
 *
 * $heading is the block's real heading and differs per page: an identical
 * heading on all ten pages would be a template tell, not a feature.
 *
 * $rows: [['en' => 'English sentence', 'bi' => null], ...]
 */
function parallel_column(string $heading, array $rows, string $blockId = 'parallel'): void
{
    // Nothing to show a Bislama speaker until somebody writes the Bislama.
    $translated = array_filter($rows, static fn ($r) => !empty($r['bi']));
    if (!$translated) {
        return;
    }
    ?>
    <section class="parallel shell" id="<?= e($blockId) ?>" aria-labelledby="<?= e($blockId) ?>-title">
      <div class="parallel__bar">
        <h2 class="parallel__title" id="<?= e($blockId) ?>-title"><?= e($heading) ?></h2>
        <button class="parallel__toggle" type="button"
                aria-expanded="false" aria-controls="<?= e($blockId) ?>-cols"
                data-show-label="Show Bislama" data-hide-label="Hide Bislama">
          <?= icon('cols') ?>
          <span class="parallel__toggle-text">Show Bislama</span>
        </button>
      </div>

      <div class="parallel__cols" id="<?= e($blockId) ?>-cols">
        <div class="parallel__col" lang="en">
          <h3>English</h3>
          <ul class="clauses">
            <?php foreach ($rows as $r): ?>
            <li><?= e($r['en']) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="parallel__col parallel__col--alt" lang="bi">
          <h3 lang="en">Bislama</h3>
          <ul class="clauses">
            <?php foreach ($rows as $r): ?>
            <li><?= e($r['bi'] ?? '') ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </section>
    <?php
}

/*
 * legal_page() was deleted on 2026-09-24. It rendered a heading list wrapped
 * in "Legal copy required" and "Copy required" boxes, and it was what a
 * customer actually saw when they clicked Privacy Policy or Terms of Use.
 * Those six routes are now sections of /policies with real content.
 */
