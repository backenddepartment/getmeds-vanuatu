<?php
/**
 * Closes <main>, then the Global Footer (content guide, page 22):
 * the brand block, four link columns plus a Contact column (accordions on a
 * phone), and a legal strip with the copyright and policy links. Then the
 * cookie notice and the scripts.
 */
?>
</main>

<footer class="g-footer">
  <div class="g-wrap g-footer__main">
    <div class="g-footer__brand">
      <a href="<?= e(url('/')) ?>">
        <img src="<?= e(asset('/assets/img/logo-getmeds-vanuatu-white.png')) ?>" width="200" height="125" loading="lazy" alt="Getmeds Vanuatu-Pacific, home">
      </a>
      <p class="g-footer__name">Getmeds Vanuatu-Pacific</p>
      <p>A pharmacy in Port Vila focused on cancer medicines. We help patients and healthcare providers access important medicines in Vanuatu and across the Pacific.</p>
      <p>Prescription medicines are supplied only against a valid prescription. Availability may vary.</p>
    </div>

    <?php foreach (footer_columns() as $head => $links): ?>
    <details class="g-footer__col" open>
      <summary><h2><?= e($head) ?></h2></summary>
      <ul>
        <?php foreach ($links as [$label, $href]): ?>
        <li><a href="<?= e(url($href)) ?>"><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </details>
    <?php endforeach; ?>

    <details class="g-footer__col" open>
      <summary><h2>Contact</h2></summary>
      <ul class="g-footer__contact">
        <li><?= gi('phone') ?><a href="<?= e(tel_url()) ?>"><?= e(cfg('phone')) ?></a></li>
        <li><?= gi('mail') ?><a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a></li>
        <li><?= gi('pin') ?><span>Golden Port, Namba 2, Port Vila</span></li>
      </ul>
    </details>
  </div>

  <div class="g-footer__legal">
    <div class="g-wrap">
      <p>&copy; <?= date('Y') ?> Getmeds Vanuatu-Pacific. Port Vila, Vanuatu.</p>
      <ul>
        <?php foreach (footer_links() as $l): ?>
        <li><a href="<?= e(url($l['url'])) ?>"><?= e($l['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</footer>

<?php /* Cookie notice (guide, page 22). Defaults to essential cookies only;
         no analytics script ships until one is chosen and configured. */ ?>
<div class="g-cookie" id="g-cookie" role="region" aria-label="Cookie notice">
  <p>We use essential cookies to make this website work. With your permission we also use analytics cookies to improve it.</p>
  <div class="g-cookie__btns">
    <button type="button" class="g-btn g-btn--primary" data-cookie="analytics">Accept analytics</button>
    <button type="button" class="g-btn g-btn--secondary" data-cookie="essential">Essential only</button>
  </div>
</div>

<?php include INC . '/float-contact.php'; ?>

<script src="<?= e(asset('/assets/js/guide.js')) ?>" defer></script>
<script src="<?= e(asset('/assets/js/i18n.js')) ?>" defer></script>
</body>
</html>
