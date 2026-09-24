<?php
/**
 * Floating contact buttons, bottom right on every page: Facebook, Messenger,
 * and a phone call. Each is a plain link, so it works without JavaScript.
 *
 * These are the platforms' own marks in their own colours, drawn inline (fill,
 * not the house stroke icons in icons.php), because a visitor looks for the
 * logo they already know. Facebook and Messenger read their links from config;
 * while those are still [[PLACEHOLDER]]s the buttons open the platforms' home
 * pages rather than guessing at a page that may belong to someone else.
 */
$fbUrl = cfg('facebook_url');
$fbUrl = is_placeholder($fbUrl) ? 'https://www.facebook.com/' : $fbUrl;
$msUrl = cfg('messenger_url');
$msUrl = is_placeholder($msUrl) ? 'https://www.messenger.com/' : $msUrl;
?>
<ul class="floatcta" aria-label="Contact us">
  <li>
    <a class="floatcta__btn floatcta__btn--fb" href="<?= e($fbUrl) ?>" target="_blank" rel="noopener">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/>
      </svg>
      <span class="floatcta__tip">Facebook<span class="u-hidden"> (opens in a new tab)</span></span>
    </a>
  </li>
  <li>
    <a class="floatcta__btn floatcta__btn--ms" href="<?= e($msUrl) ?>" target="_blank" rel="noopener">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M.001 11.639C.001 4.949 5.241 0 12.001 0S24 4.95 24 11.639c0 6.689-5.24 11.638-12 11.638-1.21 0-2.38-.16-3.47-.46a.96.96 0 0 0-.64.05l-2.39 1.05a.96.96 0 0 1-1.35-.85l-.07-2.14a.97.97 0 0 0-.32-.68A11.39 11.39 0 0 1 .002 11.64zm8.32-2.19-3.52 5.6c-.35.53.32 1.139.82.75l3.79-2.87c.26-.2.6-.2.87 0l2.8 2.1c.84.63 2.04.4 2.6-.48l3.52-5.6c.35-.53-.32-1.13-.82-.75l-3.79 2.87c-.25.2-.6.2-.86 0l-2.8-2.1a1.8 1.8 0 0 0-2.61.48z"/>
      </svg>
      <span class="floatcta__tip">Messenger<span class="u-hidden"> (opens in a new tab)</span></span>
    </a>
  </li>
  <li>
    <a class="floatcta__btn floatcta__btn--call" href="tel:<?= e(cfg('phone_href')) ?>">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
      </svg>
      <span class="floatcta__tip">Call <?= e(cfg('phone')) ?></span>
    </a>
  </li>
</ul>
