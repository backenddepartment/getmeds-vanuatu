<?php
/**
 * Closes <main>, then the footer.
 *
 * A conventional site footer on a blue-to-green wash: the white logo, a line about
 * the pharmacy and how to reach it, then a column of links for each section of
 * the site, then the compliance record and, on a darker bar, the policy links
 * and the small print.
 *
 * The section columns are built from nav_tree(), the same source as the header
 * nav, so a page added to the site appears here without anyone remembering to.
 * Every compliance value comes from config.php so it is edited in one place.
 */
$cols = array_values(array_filter(nav_tree(), static function (array $item): bool {
    return !empty($item['children']);
}));
require_once INC . '/components.php';
?>
<?php /* The Patient Safety note (and Named Patient Access link) that every
         Medicines and Patients page ends with. It renders nothing elsewhere. */
section_notes(); ?>
</main>

<footer class="footer">
  <div class="footer__main shell">

    <div class="footer__brand">
      <a class="footer__logo" href="<?= e(url('/')) ?>">
        <img src="<?= e(asset('/assets/img/logo-getmeds-vanuatu-white.png')) ?>" width="200" height="125"
             loading="lazy" alt="<?= e(cfg('site_name') . ', ' . cfg('site_descriptor')) ?>">
      </a>
      <p class="footer__about">
        A licensed specialty pharmacy in Port Vila, supplying cancer medicines and the
        medicines that manage their side effects, against a valid prescription.
      </p>
      <ul class="footer__contact">
        <li><?= icon('phone') ?><a href="tel:<?= e(cfg('phone_href')) ?>" class="num"><?= e(cfg('phone')) ?></a></li>
        <li><?= icon('mail') ?><a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a></li>
        <li><?= icon('pin') ?><span><?= e(cfg('address_line')) ?>, <?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></span></li>
        <li><?= icon('clock') ?><span><?= e(cfg('hours_long')) ?></span></li>
      </ul>
    </div>

    <?php foreach ($cols as $i => $col): ?>
    <nav class="footer__col" aria-labelledby="footer-col-<?= $i ?>">
      <h2 class="footer__head" id="footer-col-<?= $i ?>"><a href="<?= e(url($col['url'])) ?>"><?= e($col['label']) ?></a></h2>
      <ul class="footer__links">
        <?php foreach ($col['children'] as $child): ?>
        <li><a href="<?= e(url($child['url'])) ?>"><?= e($child['label']) ?></a></li>
        <?php endforeach; ?>
        <?php if ($col['url'] === '/about'): /* FAQ and Contact have no column of their own. */ ?>
        <li><a href="<?= e(url('/faq')) ?>">FAQ</a></li>
        <li><a href="<?= e(url('/contact')) ?>">Contact</a></li>
        <li><a href="<?= e(url('/enquire')) ?>">Ask About a Medicine</a></li>
        <?php endif; ?>
      </ul>
    </nav>
    <?php endforeach; ?>

  </div>

  <?php /* The compliance record. Each line shows only once its value has been
           supplied in config.php; while every one is still a [[PLACEHOLDER]]
           the whole block is left out rather than printing "Needed" markers. */
  $has = static function (string $key): bool { return !is_placeholder(cfg($key)); };
  $showRecord = $has('legal_entity_name') || $has('pharmacy_licence_no') || $has('pharmacist_name');
  if ($showRecord): ?>
  <div class="footer__record shell">
    <dl>
      <?php if ($has('legal_entity_name')): ?>
      <div>
        <dt>Registered name</dt>
        <dd><?= val('legal_entity_name') ?></dd>
      </div>
      <?php endif; ?>
      <?php if ($has('pharmacy_licence_no')): ?>
      <div>
        <dt>Pharmacy licence</dt>
        <dd><span class="num"><?= val('pharmacy_licence_no') ?></span></dd>
      </div>
      <?php endif; ?>
      <?php if ($has('pharmacist_name')): ?>
      <div>
        <dt>Responsible pharmacist</dt>
        <dd><?= val('pharmacist_name') ?>
            <?php if ($has('pharmacist_reg_no')): ?>
            <span class="footer__sub">Registration <span class="num"><?= val('pharmacist_reg_no') ?></span></span>
            <?php endif; ?></dd>
      </div>
      <?php endif; ?>
    </dl>
  </div>
  <?php endif; ?>

  <div class="footer__bottom">
    <div class="shell">
      <nav class="footer__policies" aria-label="Policies and compliance">
        <ul>
          <?php foreach (footer_links() as $l): ?>
          <li><a href="<?= e(url($l['url'])) ?>"><?= e($l['label']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <div class="footer__small">
        <p>
          &copy; <?= date('Y') ?> <?= e(cfg('site_name')) ?>. Part of <?= e(cfg('group_name')) ?>:
          <?= e(implode(', ', cfg('group_sites'))) ?>.
        </p>
        <p>
          Getmeds Vanuatu supplies medicines against a valid prescription from a licensed
          doctor. It does not provide medical advice, diagnosis or treatment.
        </p>
        <?php /* Said plainly, because a site that publishes a licence number does not
                 get to be vague about whose dispensary is in the photograph. */ ?>
        <p class="footer__claim--quiet"><?= e(plate_note()) ?></p>
      </div>
    </div>
  </div>
</footer>

<?php include INC . '/float-contact.php'; ?>

<script src="<?= e(asset('/assets/js/site.js')) ?>" defer></script>
</body>
</html>
