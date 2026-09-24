<?php
/**
 * Floating contact buttons, bottom right on every page: Facebook, Messenger,
 * and a phone call. Each is a plain link, so it works without JavaScript.
 *
 * These are the platforms' own marks in their own colours, drawn inline (fill,
 * not the house stroke icons in icons.php), because a visitor looks for the
 * logo they already know.
 *
 * WhatsApp leads, and carries a first message, because it is the channel most
 * patients here already have open and the one that can take a photograph of a
 * prescription when the site is served as static HTML with no PHP behind it.
 */
$fbUrl = (string) cfg('facebook_url');
$msUrl = (string) cfg('messenger_url');
$waUrl = whatsapp_url('Hello, I would like to order a medicine.');
?>
<ul class="floatcta" aria-label="Contact us">
  <li>
    <a class="floatcta__btn floatcta__btn--wa" href="<?= e($waUrl) ?>" target="_blank" rel="noopener">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.87 9.87 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.18 8.18 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.25-8.23 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.82c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.24-.64.8-.79.97-.14.16-.29.18-.54.06-.25-.13-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.13-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.47-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/>
      </svg>
      <span class="floatcta__tip">WhatsApp <?= e(cfg('phone')) ?></span>
    </a>
  </li>
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
