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
 *     actions  array   [['label', 'href', 'icon' => name, 'fill' => bool], ...]
 *                      at most one should be 'fill' => true
 *     points   array   [[icon name, text], ...], plain text
 */
function page_hero(array $o): void
{
    $name = (string) ($o['photo'] ?? '');
    $im   = $name !== '' ? (img_manifest()[$name] ?? null) : null;
    ?>
    <section class="hero" aria-labelledby="hero-title">
      <?php if ($im):
          $base = url('/assets/img/photo/' . $name);
          $webp = $jpg = [];
          foreach ($im['widths'] as $w) {
              $webp[] = e($base . '-' . $w . '.webp') . ' ' . $w . 'w';
              $jpg[]  = e($base . '-' . $w . '.jpg') . ' ' . $w . 'w';
          } ?>
      <?php /* The only image above the fold, so the only one loaded eagerly. It
               sits under a heavy wash as texture, so alt is empty. */ ?>
      <div class="hero__media" style="--hero-pos:<?= e((string) ($o['pos'] ?? '50% 50%')) ?>">
        <picture>
          <source type="image/webp" srcset="<?= implode(', ', $webp) ?>" sizes="100vw">
          <img src="<?= e($base . '-' . max($im['widths']) . '.jpg') ?>"
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

        <?php if (!empty($o['actions'])): ?>
        <div class="hero__actions">
          <?php foreach ($o['actions'] as $a): ?>
          <a class="btn <?= !empty($a['fill']) ? 'hero__btn--fill' : 'hero__btn--line' ?>" href="<?= e($a['href']) ?>"><?= !empty($a['icon']) ? icon($a['icon']) : '' ?><?= e($a['label']) ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php /* One row: the points, then the phone and hours. It wraps on narrow
                 screens rather than overflowing. */ ?>
        <div class="hero__facts">
          <?php if (!empty($o['points'])): ?>
          <ul class="hero__points" role="list">
            <?php foreach ($o['points'] as $p): ?>
            <li><?= icon($p[0]) ?><?= e($p[1]) ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
          <p class="hero__contact">
            <span class="hero__contact-item"><?= icon('phone') ?>Call the pharmacy
              <a href="tel:<?= e(cfg('phone_href')) ?>"><?= e(cfg('phone')) ?></a></span>
            <span class="hero__contact-item"><?= icon('clock') ?><?= e(cfg('hours_short')) ?></span>
          </p>
        </div>
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
    <form class="lookup" method="get" action="<?= e(url('/enquire')) ?>">
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
            <li>
              <?php if (!empty($r['bi'])): ?>
                <?= e($r['bi']) ?>
              <?php else: ?>
                <span class="todo" role="mark" lang="en">
                  <span class="todo__label">Needed</span>
                  <span class="todo__key">BISLAMA TRANSLATION</span>
                </span>
              <?php endif; ?>
            </li>
            <?php endforeach; ?>
          </ul>
          <p class="small quiet" lang="en" style="margin-top:var(--s-4)">
            These points must be translated by a Bislama speaker with medical knowledge.
            They have deliberately not been machine translated.
          </p>
        </div>
      </div>
    </section>
    <?php
}

/**
 * A page whose body copy must be written by a lawyer. Renders the real heading
 * structure the copy should fill, plus the marker.
 *
 * $sections: ['Heading', ...]
 */
function legal_page(string $h1, string $lede, array $sections, string $whoDrafts): void
{
    page_open($h1, e($lede));
    ?>
    <div class="section shell">
      <?= legal_copy_required() ?>
      <div class="prose">
        <p class="small quiet">To be drafted by: <?= e($whoDrafts) ?>.</p>
      </div>

      <?php foreach ($sections as $s): ?>
      <h2><?= e($s) ?></h2>
      <div class="todo-block">
        <p class="todo-block__head">Copy required</p>
        <p class="todo-block__body"><?= e($s) ?> — content for this heading has not been written.</p>
      </div>
      <?php endforeach; ?>

      <div class="notice" style="margin-top:var(--s-8)">
        <p class="notice__head">If you need an answer now</p>
        <p>
          Do not wait for this page. Call the pharmacy on <?= phone_link() ?> or email
          <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a> and a pharmacist
          will answer you directly.
        </p>
      </div>
    </div>
    <?php
}
