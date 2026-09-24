<?php
/**
 * THE HEALTHCARE-PROFESSIONAL GATE.
 *
 * This is the single place the gate lives. To tighten it, loosen it or remove it,
 * change 'provider_gate' in config.php, or change the logic in this one file.
 * No other file in the site knows the gate exists.
 *
 * Why it is here: legal advice is still outstanding on whether Vanuatu restricts
 * the advertising of cancer treatment to the general public. Until that resolves,
 * /providers carries a noindex tag and this interstitial. This is a placeholder
 * for a real access-control decision, not a security boundary, and it is
 * deliberately not described as one to the user.
 *
 * Modes, set by config.php 'provider_gate':
 *   'interstitial'  notice plus a continue button   (current)
 *   'open'          no gate
 *   'closed'        section unavailable, contact details only
 *
 * How to tighten it later, in this file alone:
 *   - require a signed-in account, or an access code, before calling provider_gate_pass()
 *   - swap the cookie for a server-side session flag
 *   - add an audit log line where noted below
 *
 * Every page under /providers must call provider_gate() immediately after
 * including header.php, and must set $page['noindex'] = true. The one
 * exception is /providers itself, which shows its hero first and then calls
 * provider_gate('h2'), because the hero already carries the page's h1.
 */

const PROVIDER_GATE_COOKIE = 'gv_hcp';

function provider_gate_passed(): bool
{
    return isset($_COOKIE[PROVIDER_GATE_COOKIE]) && $_COOKIE[PROVIDER_GATE_COOKIE] === '1';
}

/**
 * Record that the visitor confirmed. Session cookie only: it expires when the
 * browser closes, so the confirmation is never persistent, and it carries no
 * personal data. If the gate is ever tightened, replace this with a real check.
 */
function provider_gate_accept(): void
{
    // Audit log for a tightened gate would go here.
    setcookie(PROVIDER_GATE_COOKIE, '1', [
        'path'     => base_path() === '' ? '/' : base_path(),
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']),
    ]);
}

/**
 * Renders the interstitial and stops the page when the visitor has not
 * continued yet. Returns normally when the section may be shown.
 *
 * $heading  'h1' normally; 'h2' when a hero above already holds the page's h1.
 */
function provider_gate(string $heading = 'h1'): void
{
    $mode = cfg('provider_gate', 'interstitial');

    if ($mode === 'open') {
        return;
    }

    if ($mode === 'interstitial') {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'
            && isset($_POST['hcp_continue'])
            && csrf_valid($_POST['_token'] ?? null)) {
            provider_gate_accept();
            return;
        }
        if (provider_gate_passed()) {
            return;
        }
    }

    // Not passed. Render the notice in place of the page and stop.
    provider_gate_render($mode, $heading === 'h2' ? 'h2' : 'h1');

    global $page;
    include INC . '/footer.php';
    exit;
}

function provider_gate_render(string $mode, string $heading = 'h1'): void
{
    require_once INC . '/icons.php';
    ?>
    <div class="shell gate">
      <div class="gate__head">
        <<?= $heading ?>>This section is written for healthcare professionals</<?= $heading ?>>
      </div>

      <div class="prose stack" style="margin-top:var(--s-5)">
        <p class="lede">
          The pages under For Doctors &amp; Hospitals describe medicine availability,
          ordering and handling. They are intended for prescribers, nurses, pharmacists
          and institutional buyers.
        </p>

        <?php if ($mode === 'closed'): ?>
          <div class="notice">
            <p class="notice__head">Not available online at the moment</p>
            <p>
              To ask about availability, ordering or handling, please call the pharmacy on
              <?= phone_link() ?> or email
              <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>.
            </p>
          </div>
        <?php else: ?>
          <div class="notice">
            <p class="notice__head">If you are a patient or family member</p>
            <p>
              You will find what you need in
              <a href="<?= e(url('/how-it-works')) ?>">For Patients</a>. It covers how to order,
              what to bring, prices and delivery in plain language. You are also welcome to
              call the pharmacy on <?= phone_link() ?>.
            </p>
          </div>

          <form method="post" action="<?= e(current_path() === '/' ? url('/providers') : url(current_path())) ?>">
            <?= csrf_field() ?>
            <div class="actions actions--tight">
              <button class="btn btn--primary" type="submit" name="hcp_continue" value="1">
                I am a healthcare professional, continue
              </button>
            </div>
          </form>

          <p class="small quiet">
            Continuing records nothing about you beyond a note in this browser that you have
            seen this page. It clears when you close the browser.
          </p>
        <?php endif; ?>
      </div>
    </div>
    <?php
}
