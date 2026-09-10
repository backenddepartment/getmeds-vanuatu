<?php
/**
 * The small set of repeated page parts. Kept here so the same pattern cannot
 * drift between thirty-one pages.
 */
require_once INC . '/icons.php';

/**
 * A page's opening block: the 2px ink rule, the h1, and one lede line.
 * Every page except the homepage opens with this.
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
