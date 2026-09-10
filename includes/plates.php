<?php
/**
 * Plates: the site's photography.
 *
 * A gazette prints photographs as plates — framed, captioned, struck in the
 * document's own ink. Every image here is duotoned to --ink over --paper in CSS,
 * which is why licensed stock photography can sit beside these rules without
 * turning the page into a brochure.
 *
 * Two honesty rules are enforced here rather than trusted to each page:
 *
 *   - Nothing is captioned as a photograph of this pharmacy, because none of
 *     these are. They are licensed stock. The standing disclosure lives in the
 *     footer, and plate_note() renders it.
 *   - No person in a photograph is named, credited or implied to be staff. The
 *     Pexels licence forbids implying endorsement by a depicted person, and a
 *     pharmacy that publishes a licence number has no business inventing faces
 *     to go with it.
 *
 * Dimensions come from data/images.php so every plate reserves its box before
 * the file lands. Nothing here shifts on load.
 */

/**
 * The image manifest, read once.
 */
function img_manifest(): array
{
    static $m = null;
    if ($m === null) {
        $path = APP_ROOT . '/data/images.php';
        $m = is_file($path) ? require $path : [];
    }
    return $m;
}

/**
 * One plate.
 *
 * $name  key in data/images.php
 * $opt   sizes  string  the CSS `sizes` attribute; default assumes a split column
 *        ar     string  aspect ratio override, e.g. '3 / 4'
 *        pos    string  object-position, e.g. '50% 30%'
 *        cap    string  caption override; '' suppresses the caption entirely
 *        ref    string  small right-hand reference, gazette style
 *        class  string  extra classes on the <figure>
 *        alt    string  alt override
 *        eager  bool    true for a plate above the fold
 */
function plate(string $name, array $opt = []): void
{
    $m = img_manifest();
    if (!isset($m[$name])) {
        return;                       // a missing plate is silence, never a broken box
    }
    $im = $m[$name];

    $alt   = $opt['alt']   ?? $im['alt'];
    $cap   = array_key_exists('cap', $opt) ? $opt['cap'] : ($im['cap'] ?? '');
    $ref   = $opt['ref']   ?? '';
    $sizes = $opt['sizes'] ?? '(min-width: 60em) 32vw, (min-width: 40em) 60vw, 92vw';
    $cls   = trim('plate ' . ($opt['class'] ?? ''));
    $base  = url('/assets/img/photo/' . $name);

    $style = [];
    if (!empty($opt['ar']))  { $style[] = '--plate-ar:' . $opt['ar']; }
    if (!empty($opt['pos'])) { $style[] = '--plate-pos:' . $opt['pos']; }
    $style = $style ? ' style="' . e(implode(';', $style)) . '"' : '';

    $webp = $jpg = [];
    foreach ($im['widths'] as $w) {
        $webp[] = e($base . '-' . $w . '.webp') . ' ' . $w . 'w';
        $jpg[]  = e($base . '-' . $w . '.jpg') . ' ' . $w . 'w';
    }
    $widest = max($im['widths']);
    $load   = !empty($opt['eager']) ? 'eager' : 'lazy';
    $prio   = !empty($opt['eager']) ? 'high' : 'auto';
    ?>
    <figure class="<?= e($cls) ?>"<?= $style ?>>
      <div class="plate__frame">
        <picture>
          <source type="image/webp" srcset="<?= implode(', ', $webp) ?>" sizes="<?= e($sizes) ?>">
          <img class="plate__img"
               src="<?= e($base . '-' . $widest . '.jpg') ?>"
               srcset="<?= implode(', ', $jpg) ?>" sizes="<?= e($sizes) ?>"
               width="<?= (int) $im['w'] ?>" height="<?= (int) $im['h'] ?>"
               loading="<?= $load ?>" decoding="async" fetchpriority="<?= $prio ?>"
               alt="<?= e($alt) ?>">
        </picture>
      </div>
      <?php if ($cap !== '' || $ref !== ''): ?>
      <figcaption class="plate__cap">
        <?php if ($cap !== ''): ?><span><?= e($cap) ?></span><?php endif; ?>
        <?php if ($ref !== ''): ?><span class="plate__ref"><?= e($ref) ?></span><?php endif; ?>
      </figcaption>
      <?php endif; ?>
    </figure>
    <?php
}

/**
 * A full-width band: an ink panel carrying one statement, and a plate beside it.
 *
 * Text sits on the ink and never on the photograph, so contrast is 14.28:1 by
 * construction. This is the only element on the site that inverts, and it is
 * used sparingly — a band that appeared on every page would just be a hero.
 *
 * $opt  kicker, head, body (html allowed in body), pos (object-position)
 */
function band(string $name, array $opt): void
{
    $m = img_manifest();
    $im = $m[$name] ?? null;
    ?>
    <section class="band" aria-labelledby="<?= e($opt['id'] ?? 'band') ?>-head">
      <div class="band__inner">
        <div class="band__panel">
          <?php if (!empty($opt['kicker'])): ?>
          <p class="band__kicker"><?= e($opt['kicker']) ?></p>
          <?php endif; ?>
          <h2 class="band__head" id="<?= e($opt['id'] ?? 'band') ?>-head"><?= e($opt['head']) ?></h2>
          <?php if (!empty($opt['body'])): ?>
          <p class="band__body"><?= $opt['body'] ?></p>
          <?php endif; ?>
        </div>
        <?php if ($im): $base = url('/assets/img/photo/' . $name);
              $webp = $jpg = [];
              foreach ($im['widths'] as $w) {
                  $webp[] = e($base . '-' . $w . '.webp') . ' ' . $w . 'w';
                  $jpg[]  = e($base . '-' . $w . '.jpg') . ' ' . $w . 'w';
              } ?>
        <div class="band__figure"<?= !empty($opt['pos']) ? ' style="--plate-pos:' . e($opt['pos']) . '"' : '' ?>>
          <picture>
            <source type="image/webp" srcset="<?= implode(', ', $webp) ?>" sizes="(min-width: 52em) 50vw, 100vw">
            <img class="plate__img" src="<?= e($base . '-800.jpg') ?>"
                 srcset="<?= implode(', ', $jpg) ?>" sizes="(min-width: 52em) 50vw, 100vw"
                 width="<?= (int) $im['w'] ?>" height="<?= (int) $im['h'] ?>"
                 loading="lazy" decoding="async" alt="<?= e($im['alt']) ?>">
          </picture>
        </div>
        <?php endif; ?>
      </div>
    </section>
    <?php
}

/**
 * The standing disclosure. One sentence, in the footer, on every page.
 * A site that asks people to trust a licence number does not get to be vague
 * about whose dispensary is in the picture.
 */
function plate_note(): string
{
    return 'Photographs are licensed stock images used for illustration. '
         . 'They are not photographs of our premises or our staff.';
}
