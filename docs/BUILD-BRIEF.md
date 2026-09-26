# Build brief: applying the Content & Design Guide

The whole site is being rebuilt from `docs/content-guide.pdf` (text copy:
`docs/content-guide.txt`). **Every page, every section, every line of website
copy and every design instruction in the guide must be applied. Nothing may be
left out.** Copy is used verbatim (fix nothing, add nothing that contradicts it).

Guide text line ranges in `docs/content-guide.txt`:
00 Global design 31 · 01 Home 183 · 02 About 492 · 03 Oncology 686 ·
04 Medicines 907 · 05 Named Patient Supply 1205 · 06 How It Works 1400 ·
07 Conditions hub 1553 · 08 Condition pages 1646 · 09 Pacific 2377 ·
10 Affordable 2522 · 11 Healthcare Professionals 2619 · 12 Patients 2790 ·
13 Services 2961 · 14 FAQ 3120 · 15 Contact 3237 · 16 Side effect 3407 ·
17 Complaints 3475 · 18 Policies 3530 · 19 Privacy 3609 · 20 Terms 3666 ·
21 404 3715 · 22 Footer 3741 · 23 Navigation 3819.
If the text extraction is unclear anywhere, read the PDF pages directly.

## Owner decisions (override the guide)

1. **No button in the desktop header.** Already done in `includes/header.php`.
   Do not add one. "Request a Medicine" buttons go in page bodies (hero, CTA
   bands, cards) as the guide says for those sections.
2. **Home hero keeps the owner's text**: H1 "Affordable Medicines. Better
   Access. Stronger Cancer Care." and lede "Getmeds Vanuatu-Pacific helps
   patients and healthcare providers access essential and cancer medicines
   through reliable sourcing, more affordable pricing, and a growing supply
   network across the Pacific." Kicker "Licensed pharmacy · Port Vila,
   Vanuatu". Everything else on Home follows the guide.
3. **Old pages not in the guide redirect** (301) to their closest new page
   using `redirect_to('/path')` from bootstrap.

## Already built (do not rewrite; use them)

- `assets/css/guide.css` — the design system (g- prefix). Read it first.
  `assets/css/base.css` — fonts, reset, floating buttons. The old `site.css`
  is no longer loaded; do not use its classes (.section, .shell, .hero, .btn,
  .prose, .lede, .plate, etc.).
- `includes/ui.php` — components: `gi()` icons, `g_btn()`, `g_call_btn()`,
  `request_url($type)`, `tel_url()`, `g_crumbs()`, `g_trust()`, `g_steps()`
  (modes h | rows3 | v), `g_box()` (info | safety | emergency),
  `g_check()`, `g_table()`, `g_cta_band()`, `g_card()`, `g_photo()`,
  `g_pacific_map()`. Read the file for signatures and the icon names.
- `includes/forms.php` — `gform_run($kind, $schema, $prefill)` (call BEFORE
  including head.php) and `gform_render($kind, $schema, $state, $opts)`.
- `includes/header.php`, `nav.php`, `footer.php`, `head.php` (supports
  `$page['seo_title']` = the guide's SEO title, used verbatim as <title>;
  `$page['desc']` = meta description; `$page['noindex']`).
- `assets/js/guide.js` — sticky header, dropdown, `details[data-single]`
  one-open accordions, `[data-mobile-closed]` details start closed on mobile,
  `input[data-filter="#id"]` live filter over `[data-filter-group]` /
  `[data-filter-item]` / `[data-filter-empty]`, `[data-set-type]` enquiry
  cards, upload thumbnails, `[data-spy]` sticky in-page menus.

## Page file pattern

```php
<?php
require __DIR__ . '/../includes/bootstrap.php';   // adjust depth
$page = [
    'title'     => 'About Getmeds Vanuatu',
    'seo_title' => 'About Getmeds Vanuatu — Medicine Access for Vanuatu and the Pacific',
    'desc'      => '<meta description from the guide>',
];
include INC . '/head.php';
include INC . '/header.php';
?>
<section class="g-hero g-hero--grad g-wave"> ... </section>
<section class="g-sec g-bg-mint"><div class="g-wrap"> ... </div></section>
<?php include INC . '/footer.php'; ?>
```

- Section wrapper: `<section class="g-sec g-bg-{white|mint|sky|navy|grad}">`
  + `<div class="g-wrap">` (add `g-narrow` 760px or `g-read` 720px). Follow the
  background each section's design instruction names; otherwise alternate
  white → mint → white → sky.
- Exactly one `<h1>` per page (the guide's H1). Each guide section is an
  `<h2>`; cards are `<h3>`.
- Escape any dynamic text with `e()`. Use `url('/path')` for every internal
  href; never a bare "/path".
- "Request a Medicine" → `request_url('medicine')`. Patient Enquiry →
  `request_url('patient')`. Healthcare Professional Enquiry →
  `request_url('professional')`. Named Patient Supply Enquiry →
  `request_url('nps')`. Request a Cancer Medicine / Enquire About Cancer
  Medicines → `request_url('cancer')`. Request a Quotation →
  `request_url('quotation')` (except on /healthcare-professionals, where it
  jumps to that page's own form `#quote`). Pacific Enquiry →
  `request_url('pacific')`. "Call Us" / "Talk to Our Team" / "Talk to a
  Pharmacist" / "Talk to Our Pharmacist" → `tel_url()` with the phone icon
  (g_call_btn). Contact Us → `url('/contact')`.
- Photos: real photos only. `g_photo($key, $alt)` with keys from
  `data/images.php` (dispensary, shelves, bottles, dispenser, ampoules,
  vialtray, line, drawer, racks, carton, blister, van, parcels, warehouse,
  island, wharf, corridor, ward, script, notes) or 'home'. Team photos of the
  real Getmeds Vanuatu team: `assets/img/vanuatuone.jpg` … `vanuatueight.jpg`
  (large JPEGs; use `<img loading="lazy">` with width/height and alt; view
  them before choosing). Never needles close-up, never sick people. Where the
  guide asks for a photo we don't have (e.g. Golden Port building, smiling
  woman in head scarf), use the closest real photo or a clean illustration
  (inline SVG in brand colours), never a fake.
- Illustrations/diagrams: inline SVG or HTML diagrams (`.g-diagram`), brand
  colours, `role="img"` + aria-label, or aria-hidden if decorative.
- Page-specific CSS: append to `assets/css/guide.css` in a clearly commented
  block at the END titled with your page name, g- prefixed. Keep it small;
  reuse existing classes first. Several agents append in parallel, so use the
  Edit tool to append (never rewrite the file) and re-read before editing.
- Do not stop Apache. Pages are live at
  http://localhost/Vanuatu/getmeds-vanuatu/<path>/
- Verify each page: `curl -s -o /dev/null -w "%{http_code}"` returns 200,
  `C:/xampp/php/php.exe -l file` passes, the HTML has no "Warning"/"Fatal",
  and take a screenshot with headless Chrome
  (`"/c/Program Files/Google/Chrome/Application/chrome.exe" --headless=new
  --disable-gpu --hide-scrollbars --window-size=1440,4000
  --screenshot=<scratch>.png <url>`) and look at it; also at width 500
  (Chrome's minimum) to check the phone layout. Fix what looks wrong.
- Do not commit to git.
