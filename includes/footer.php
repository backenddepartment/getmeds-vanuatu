<?php
/**
 * Closes <main>, then the footer.
 *
 * The footer carries nine compliance links. Hick's Law is deliberately overridden
 * here: footers are scanned, not decided from, and regulatory content has to be
 * findable. It stays visually quiet — small text, no icons, one column on mobile.
 *
 * Every compliance value comes from config.php so it is edited in one place.
 */
$ref = $page['ref'] ?? null;
?>
</main>

<footer class="footer">
  <div class="shell">

    <div class="footer__record">
      <h2 class="footer__head">The pharmacy</h2>
      <dl class="record">
        <div class="record__row">
          <dt><?= icon('scales') ?>Registered name</dt>
          <dd><?= val('legal_entity_name') ?></dd>
        </div>
        <div class="record__row">
          <dt><?= icon('shield') ?>Pharmacy licence</dt>
          <dd><span class="num"><?= val('pharmacy_licence_no') ?></span></dd>
        </div>
        <div class="record__row">
          <dt><?= icon('person') ?>Responsible pharmacist</dt>
          <dd><?= val('pharmacist_name') ?>
              <span class="record__sub">Registration <span class="num"><?= val('pharmacist_reg_no') ?></span></span></dd>
        </div>
        <div class="record__row">
          <dt><?= icon('pin') ?>Address</dt>
          <dd><?= e(cfg('address_line')) ?><br><?= e(cfg('address_city')) ?>, <?= e(cfg('address_country')) ?></dd>
        </div>
        <div class="record__row">
          <dt><?= icon('phone') ?>Phone</dt>
          <dd><a href="tel:<?= e(cfg('phone_href')) ?>" class="num"><?= e(cfg('phone')) ?></a>
              <span class="record__sub"><?= e(cfg('hours_long')) ?></span></dd>
        </div>
        <div class="record__row">
          <dt><?= icon('mail') ?>Email</dt>
          <dd><a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a></dd>
        </div>
      </dl>
    </div>

    <nav class="footer__legal" aria-label="Policies and compliance">
      <h2 class="footer__head">Policies</h2>
      <ul class="footer__links">
        <?php foreach (footer_links() as $l): ?>
        <li><a href="<?= e(url($l['url'])) ?>"><?= e($l['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="footer__base">
      <p class="footer__group">
        Part of <?= e(cfg('group_name')) ?>.
        <span class="footer__sites"><?= e(implode(', ', cfg('group_sites'))) ?></span>
      </p>
      <p class="footer__claim">
        Getmeds Vanuatu supplies medicines against a valid prescription from a licensed
        doctor. It does not provide medical advice, diagnosis or treatment.
      </p>
      <?php /* Said plainly, because a site that publishes a licence number does not
               get to be vague about whose dispensary is in the photograph. */ ?>
      <p class="footer__claim footer__claim--quiet"><?= e(plate_note()) ?></p>
    </div>

    <?php /* Marginalia. A gazette sets its notice reference and issue line in the
             sheet's margin, apart from the body text, and so does this. */ ?>
    <div class="footer__margin">
      <?php if ($ref): ?>
      <span>Notice <span class="num footer__val"><?= e($ref) ?></span></span>
      <?php endif; ?>
      <span>Content reviewed <span class="footer__val"><?= val('content_reviewed') ?></span></span>
    </div>

  </div>
</footer>

<script src="<?= e(url('/assets/js/site.js')) ?>" defer></script>
</body>
</html>
