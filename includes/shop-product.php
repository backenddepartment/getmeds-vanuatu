<?php
/**
 * One product: /<category>/<product>/. Included by
 * product-range/index.php, which sets $product and $cats.
 *
 * A copy of the Getmeds India product page (same layout, sizes, colours,
 * text, steps and popups), built with this site's CSS (guide.css, "Product
 * page") instead of India's Tailwind, which the CSP here would block:
 *   * Left: status | category and name, with Oncology Type and Drug Class
 *     beside them (no image placeholder), the Description card, Related
 *     Products chips.
 *   * Right: Send Inquiry with the profile switcher pill.
 *       Patient / Caregiver: three steps
 *         1 Your details (name, email, phone with country, age)
 *         2 Delivery & documents (address, contact person, the patient's ID
 *           with thumbnails, the prescription; both required)
 *         3 Consent & send (two checkboxes, medical disclaimer)
 *       Doctor / Pharmacy / Hospital: one step (name, phone, age, email,
 *       message)
 *     The Product Range table's Send Inquiry menu opens this page with
 *     ?userType= set, so the right form shows straight away.
 *   * Popups: Inquiry Sent!, Prescription Required, Valid ID Required, the
 *     ID preview, Please Check Your Form (also Submission Failed).
 * assets/js/shop-inquiry.js runs the forms and posts JSON, files as base64,
 * to /product-range/inquiry (shop-inquiry.php), which checks it all again
 * and emails it through Brevo.
 *
 * Two differences from India, on purpose: no "In stock" line (the
 * pharmacist confirms availability, so the status says "Prescription
 * medicine"), and ages run 1 to 100 so a child's inquiry is not blocked.
 */

$p       = $product;
$catName = $cats[$p['cat']][0];
$catPath = shop_category_path($p['cat']);
$static  = is_static_build();
$token   = $static ? '' : csrf_token();   // starts the session, so before any output

$PROFILES = [
    'patient'  => 'Patient / Caregiver',
    'doctor'   => 'Doctor / Healthcare Professional',
    'pharmacy' => 'Pharmacy Owner / Retail Pharmacy',
    'hospital' => 'Hospital / Institution',
];

// The reply-time promise: in the "?" tooltip beside Submit and the success popup.
const PD_SLA = 'We reply within the day, with your price in writing.';

// Up to three more from the same category.
$related = [];
foreach (shop_products() as $r) {
    if ($r['cat'] === $p['cat'] && $r['slug'] !== $p['slug']) {
        $related[] = $r;
    }
    if (count($related) === 3) {
        break;
    }
}

/** The "Age" dropdown, used by both forms. */
$ageDropdown = static function (string $id): string {
    $opts = '';
    for ($age = 1; $age <= 100; $age++) {
        $opts .= '<button type="button" role="option" data-value="' . $age . '">' . $age . '</button>';
    }
    return '<div class="g-pd__age">'
         . '<span class="g-pd__label" id="' . $id . '-l">Age</span>'
         . '<div class="g-pd__agedrop" data-age>'
         . '<input type="hidden" id="' . $id . '" value="">'
         . '<button type="button" class="g-pd__agebtn" aria-haspopup="listbox" aria-expanded="false" aria-labelledby="' . $id . '-l">'
         . '<span class="g-pd__agelabel">Age</span>'
         . '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>'
         . '</button>'
         . '<div class="g-pd__agemenu" role="listbox" hidden>' . $opts . '</div>'
         . '</div></div>';
};

/** A field only bots fill in. A submission that fills it is dropped. */
$honeypot = '<div class="g-pd__hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>';

/** The "?" beside Submit that shows the reply-time promise on hover or focus. */
$slaNote = static function (string $id): string {
    return '<span class="g-pd__sla">'
         . '<button type="button" class="g-pd__slabtn" aria-label="When will we reply?" aria-describedby="' . $id . '">'
         . '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3"/><circle cx="12" cy="17" r="0.6" fill="currentColor" stroke="none"/></svg>'
         . '</button>'
         . '<span id="' . $id . '" role="tooltip" class="g-pd__slatip">' . e(PD_SLA) . '</span>'
         . '</span>';
};

$accept = '.jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf';

$page = [
    'title'     => $p['name'],
    'seo_title' => $p['name'] . ' — ' . $catName . ' — Getmeds Vanuatu',
    'desc'      => $p['name'] . ' — ' . $catName . '. Sourced by Getmeds Vanuatu from internationally certified manufacturers, available by prescription. Send an inquiry for a quote.',
];

include INC . '/head.php';
include INC . '/header.php';
?>
<link rel="stylesheet" href="<?= e(asset('/assets/vendor/intl-tel-input/css/intlTelInput.min.css')) ?>">
<script type="application/ld+json"><?= json_encode(array_filter([
    '@context'           => 'https://schema.org',
    '@type'              => 'Drug',
    'name'               => $p['name'],
    'url'                => abs_url(shop_product_path($p)),
    'drugClass'          => $catName,
    'mechanismOfAction'  => $p['drug_class'] ?: null,
    'prescriptionStatus' => 'https://schema.org/PrescriptionOnly',
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>

<div class="g-pd">

  <div class="g-pd__crumbbar">
    <?php g_crumbs([['Home', '/'], ['Catalog', '/medicines/catalog'], ['Product Range', '/product-range/'], [$catName, $catPath], [$p['name'], null]]); ?>
    <a class="g-pd__help" href="<?= e(url('/contact')) ?>">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.5 9a2.5 2.5 0 0 1 5 0c0 1.5-2 2-2 3.5" stroke-linecap="round"/><circle cx="12" cy="17" r="0.6" fill="currentColor" stroke="none"/></svg>
      Do you need help?
    </a>
  </div>

  <div class="g-pd__wrap">
    <div class="g-pd__card">

      <!-- ============ LEFT: PRODUCT ============ -->
      <div class="g-pd__info">
        <div class="g-pd__top">
          <div class="g-pd__head">
            <p class="g-pd__status">
              <span class="g-pd__rx">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
                Prescription medicine
              </span>
              <span class="g-pd__sep" aria-hidden="true">|</span>
              <a class="g-pd__cat" href="<?= e(url($catPath)) ?>"><?= e($catName) ?></a>
            </p>
            <h1><?= e($p['name']) ?></h1>
          </div>
          <?php /* Right of the name: the key details, where a product photo
                   would sit (there are no product photos). */ ?>
          <div class="g-pd__metacol">
            <div class="g-pd__meta">
              <?php if ($p['oncology_type'] !== ''): ?><div><span>Oncology Type</span><strong><?= e($p['oncology_type']) ?></strong></div><?php endif; ?>
              <?php if ($p['drug_class'] !== ''): ?><div><span>Drug Class / Mechanism</span><strong><?= e($p['drug_class']) ?></strong></div><?php endif; ?>
            </div>
          </div>
        </div>

        <div class="g-pd__desc">
          <div class="g-pd__tabs"><span>Description</span></div>
          <p>
            <?= e($p['name']) ?>
            <?php if ($p['drug_class'] !== ''): ?>is classified as <?= preg_match('/^[aeiou]/i', $p['drug_class']) ? 'an' : 'a' ?> <?= e($p['drug_class']) ?>,<?php endif; ?>
            sourced for use within our <?= e($catName) ?> portfolio.
            Reach out to our team for full regulatory documentation, pricing, and current availability.
          </p>
          <div class="g-pd__grid">
            <div><span>Therapeutic Category</span><strong><?= e($catName) ?></strong></div>
            <div><span>Oncology Type</span><strong><?= e($p['oncology_type'] ?: '—') ?></strong></div>
          </div>
        </div>

        <?php if ($related): ?>
        <div class="g-pd__related">
          <p>Related Products</p>
          <div>
            <?php foreach ($related as $r): ?>
            <a href="<?= e(url(shop_product_path($r))) ?>"><?= e($r['name']) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- ============ RIGHT: SEND INQUIRY ============ -->
      <div class="g-pd__inquiry">
        <div class="g-pd__inqhead">
          <h2>Send Inquiry</h2>
          <p>Submit your details to get a formal quote for this product.</p>

          <?php if (!$static): ?>
          <div class="g-pd__switcher" id="pd-switcher">
            <button type="button" id="pd-switcher-btn" aria-haspopup="true" aria-expanded="false" aria-controls="pd-switcher-menu">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1c0-4.418 3.582-7 8-7s8 2.582 8 7v1H4z"/></svg>
              <span class="g-sr">I am a: </span><span id="pd-switcher-label"><?= e($PROFILES['patient']) ?></span>
              <svg class="g-pd__switchchev" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div id="pd-switcher-menu" class="g-pd__menu" hidden>
              <?php foreach ($PROFILES as $key => $label): ?>
              <button type="button" data-value="<?= e($key) ?>"><?= e($label) ?></button>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <?php if ($static): ?>
        <?php /* A static host runs no PHP, so the form would have nowhere to go. */ ?>
        <p class="g-pd__static">Call us on <?= phone_link() ?> or email <a href="mailto:<?= e(cfg('email')) ?>?subject=<?= rawurlencode('Inquiry: ' . $p['name']) ?>"><?= e(cfg('email')) ?></a> with the product name, and attach your prescription.</p>
        <?php else: ?>
        <div id="pd-app"
             data-endpoint="<?= e(url('/product-range/inquiry')) ?>"
             data-product="<?= e($p['slug']) ?>"
             data-token="<?= e($token) ?>"
             data-profiles="<?= e(json_encode($PROFILES)) ?>">

          <div class="g-pd__progress" id="pd-progress">
            <div id="pd-dots" class="g-pd__dots" aria-hidden="true"></div>
            <span id="pd-step-label" aria-live="polite">Step 1 of 3</span>
          </div>

          <!-- Patient / Caregiver: three steps -->
          <form id="pd-form-patient" class="g-pd__form" hidden novalidate>
            <div class="g-pd__step" data-step="1">
              <h3>Your details</h3>
              <label class="g-pd__field"><span class="g-pd__label">Target Product</span><input type="text" readonly value="<?= e($p['name']) ?>" class="g-pd__readonly"></label>
              <label class="g-pd__field"><span class="g-pd__label">Patient Full Name</span><input type="text" name="patientName" autocomplete="name" maxlength="120" placeholder="Full name as shown on the prescription"></label>
              <label class="g-pd__field"><span class="g-pd__label">Email Address</span><input type="email" name="patientEmail" autocomplete="email" maxlength="160" placeholder="john@example.com"></label>
              <div class="g-pd__row">
                <label class="g-pd__field g-pd__grow"><span class="g-pd__label">Phone Number</span><span class="g-pd__phonewrap"><input type="tel" name="patientPhone" class="g-pd__phone" inputmode="numeric" autocomplete="tel" placeholder="591 2345"></span></label>
                <?= $ageDropdown('pd-age-patient') ?>
              </div>
            </div>

            <div class="g-pd__step" data-step="2" hidden>
              <h3>Delivery &amp; documents</h3>
              <label class="g-pd__field"><span class="g-pd__label">Delivery Address</span><input type="text" name="patientAddress" autocomplete="street-address" maxlength="300" placeholder="Complete address for courier delivery"></label>

              <div class="g-pd__box">
                <h4>Contact Person</h4>
                <label class="g-pd__check"><input type="checkbox" id="pd-contact-same">
                  <span><strong>Same as patient details</strong><small>Check this if the patient is the one placing the inquiry.</small></span>
                </label>
                <div id="pd-contact-fields" class="g-pd__contact">
                  <label class="g-pd__field g-pd__field--sm"><span class="g-pd__label">Contact Person's Full Name</span><input type="text" id="pd-contact-name" maxlength="120" placeholder="Person we should contact"></label>
                  <label class="g-pd__field g-pd__field--sm"><span class="g-pd__label">Relationship to Patient</span>
                    <select id="pd-contact-relationship">
                      <option value="">Select relationship (optional)</option>
                      <option>Family member</option><option>Caregiver</option><option>Guardian</option>
                      <option>Healthcare professional</option><option>Other</option>
                    </select>
                  </label>
                </div>
              </div>

              <div class="g-pd__upload">
                <span class="g-pd__label" id="pd-id-h">Upload valid ID of the patient</span>
                <p class="g-pd__hint">A valid government-issued ID of the patient helps us process your order faster and ensures the prescription is dispensed to the right person.</p>
                <div id="pd-id-area" class="g-pd__ids">
                  <label class="g-pd__upbtn"><input id="pd-id-input" type="file" multiple accept="<?= e($accept) ?>" class="g-sr" aria-labelledby="pd-id-h">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m0 0l-4-4m4 4l4-4M4 19h16"/></svg>
                    Upload File
                  </label>
                  <span class="g-pd__hint">JPG, PNG, PDF &mdash; add both sides if needed</span>
                </div>
              </div>

              <div class="g-pd__upload">
                <label class="g-pd__label" for="pd-rx-input">Upload Prescription (Required)</label>
                <input id="pd-rx-input" type="file" multiple accept="<?= e($accept) ?>" class="g-pd__file">
                <ul id="pd-rx-list" class="g-pd__rxlist" role="list"></ul>
              </div>

              <p class="g-pd__note">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                <span>Our pharmacists will contact you on the mobile number provided.</span>
              </p>
            </div>

            <div class="g-pd__step" data-step="3" hidden>
              <h3>Consent &amp; send</h3>
              <div class="g-pd__consent">
                <h4>Declarations and Consent</h4>
                <div class="g-pd__check g-pd__check--sm"><input type="checkbox" id="pd-terms"><label for="pd-terms">I confirm that the information provided is accurate and that the prescription submitted is valid.</label></div>
                <div class="g-pd__check g-pd__check--sm"><input type="checkbox" id="pd-privacy"><label for="pd-privacy">I have read and understood the <a href="<?= e(url('/privacy')) ?>" target="_blank" rel="noopener">Privacy Policy</a> and consent to the collection, use, and processing of my personal and sensitive personal information for the purpose of verifying and processing this inquiry.</label></div>
              </div>
              <?= $honeypot ?>
              <div class="g-pd__disclaimer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
                <p><strong>Medical Disclaimer: </strong><?= e(cfg('site_name')) ?> dispenses prescription medicines only upon receipt of a valid prescription from a licensed physician. This service does not replace professional medical advice, diagnosis, or treatment.</p>
              </div>
            </div>

            <div class="g-pd__actions">
              <button type="button" class="g-pd__back">Back</button>
              <button type="button" class="g-pd__next g-pd__primary">Continue</button>
              <button type="submit" class="g-pd__submit g-pd__primary" hidden>Submit Inquiry Request</button>
              <?= $slaNote('pd-sla-1') ?>
            </div>
          </form>

          <!-- Doctor / Pharmacy / Hospital: one step -->
          <form id="pd-form-corporate" class="g-pd__form" hidden novalidate>
            <div class="g-pd__step" data-step="1">
              <h3>Your details</h3>
              <label class="g-pd__field"><span class="g-pd__label">Target Product</span><input type="text" readonly value="<?= e($p['name']) ?>" class="g-pd__readonly"></label>
              <label class="g-pd__field"><span class="g-pd__label">Full Name</span><input type="text" name="corpName" autocomplete="name" maxlength="120" placeholder="John Doe"></label>
              <div class="g-pd__row">
                <label class="g-pd__field g-pd__grow"><span class="g-pd__label">Phone Number</span><span class="g-pd__phonewrap"><input type="tel" name="corpPhone" class="g-pd__phone" inputmode="numeric" autocomplete="tel" placeholder="591 2345"></span></label>
                <?= $ageDropdown('pd-age-corp') ?>
              </div>
              <label class="g-pd__field"><span class="g-pd__label">Email Address</span><input type="email" name="corpEmail" autocomplete="email" maxlength="160" placeholder="john@example.com"></label>
              <label class="g-pd__field"><span class="g-pd__label">Message</span><textarea name="corpMessage" rows="3" maxlength="3000" placeholder="Tell us more about your requirements..."></textarea></label>
              <?= $honeypot ?>
            </div>
            <div class="g-pd__actions">
              <button type="button" class="g-pd__back" hidden>Back</button>
              <button type="button" class="g-pd__next g-pd__primary" hidden>Continue</button>
              <button type="submit" class="g-pd__submit g-pd__primary">Submit Inquiry Request</button>
              <?= $slaNote('pd-sla-2') ?>
            </div>
          </form>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php if (!$static): ?>
<!-- ============ POPUPS ============ -->
<div class="g-pd__modal g-pd__modal--success" id="pd-modal-success" role="dialog" aria-modal="true" aria-labelledby="pd-success-h" hidden>
  <div class="g-pd__dialog g-pd__dialog--success">
    <?php /* The circle fills in, then the check draws itself, each time the popup opens. */ ?>
    <div class="g-pd__okicon" aria-hidden="true"><svg viewBox="0 0 52 52"><circle class="g-pd__okring" cx="26" cy="26" r="24"/><path class="g-pd__okcheck" d="M15 27l7 7 15-16"/></svg></div>
    <h2 id="pd-success-h">Inquiry Sent!</h2>
    <p>Thank you for your inquiry. <?= e(PD_SLA) ?></p>
    <p class="g-pd__ref" id="pd-success-ref" hidden></p>
    <div class="g-pd__dfoot"><button type="button" class="g-pd__close g-pd__link" data-modal="pd-modal-success">Close</button></div>
  </div>
</div>

<div class="g-pd__modal" id="pd-modal-rx" role="dialog" aria-modal="true" aria-labelledby="pd-rx-h" hidden>
  <div class="g-pd__dialog g-pd__dialog--warn">
    <button type="button" class="g-pd__close g-pd__xsm" data-modal="pd-modal-rx" aria-label="Close"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    <div class="g-pd__dbody">
      <div class="g-pd__warnicon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M9 8h6M9 12h6M9 16h3"/></svg></div>
      <h2 id="pd-rx-h">Prescription Required</h2>
      <p class="g-pd__red">A valid prescription is required before your inquiry can be submitted.</p>
      <p>As a Patient / Caregiver, please attach your doctor-issued prescription (JPG, PNG, or PDF) to ensure your medicine request complies with regulations and can be processed safely by our team.</p>
    </div>
    <div class="g-pd__dfoot"><button type="button" class="g-pd__close g-pd__link" data-modal="pd-modal-rx">I Understand, Upload Now</button></div>
  </div>
</div>

<div class="g-pd__modal" id="pd-modal-id" role="dialog" aria-modal="true" aria-labelledby="pd-idreq-h" hidden>
  <div class="g-pd__dialog g-pd__dialog--warn">
    <button type="button" class="g-pd__close g-pd__xsm" data-modal="pd-modal-id" aria-label="Close"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    <div class="g-pd__dbody">
      <div class="g-pd__warnicon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="12" r="2"/><path d="M14 10h4M14 14h4"/></svg></div>
      <h2 id="pd-idreq-h">Valid ID Required</h2>
      <p class="g-pd__red">A valid ID of the patient is required before your inquiry can be submitted.</p>
      <p>Please upload a valid government-issued ID (JPG, PNG, or PDF) so we can confirm the prescription is being dispensed to the right person.</p>
    </div>
    <div class="g-pd__dfoot"><button type="button" class="g-pd__close g-pd__link" data-modal="pd-modal-id">I Understand, Upload Now</button></div>
  </div>
</div>

<div class="g-pd__modal g-pd__modal--dark" id="pd-modal-preview" role="dialog" aria-modal="true" aria-label="ID preview" hidden>
  <button type="button" class="g-pd__close g-pd__x" data-modal="pd-modal-preview" aria-label="Close"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
  <div class="g-pd__previewbox"><img id="pd-preview-img" src="data:," alt="Uploaded ID"></div>
</div>

<div class="g-pd__modal" id="pd-modal-alert" role="alertdialog" aria-modal="true" aria-labelledby="pd-alert-title" aria-describedby="pd-alert-message" hidden>
  <div class="g-pd__dialog g-pd__dialog--alert">
    <h2 id="pd-alert-title">Please Check Your Form</h2>
    <p id="pd-alert-message"></p>
    <button type="button" class="g-pd__close g-pd__okbtn" data-modal="pd-modal-alert">OK</button>
  </div>
</div>

<script src="<?= e(asset('/assets/vendor/intl-tel-input/js/intlTelInputWithUtils.min.js')) ?>" defer></script>
<script src="<?= e(asset('/assets/js/shop-inquiry.js')) ?>" defer></script>
<?php endif; ?>

<?php include INC . '/footer.php'; ?>
