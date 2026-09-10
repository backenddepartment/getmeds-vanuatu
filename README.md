# Getmeds Vanuatu — Pacific Chemotherapy Pharmacy

A 31-page website for a specialty oncology pharmacy in Port Vila, Vanuatu.

Plain HTML5, CSS3 and a small amount of vanilla JavaScript. PHP is used only for
shared includes and the enquiry form handler. No build step, no framework, no
package manager, no database, and no third-party request of any kind.

---

## Running it

### XAMPP (local)

1. Copy this folder into `xampp/htdocs/`.
2. Start Apache.
3. Open `http://localhost/getmeds-vanuatu/`.

That is the whole setup. The site detects whether it is at the document root or in
a subfolder and builds its URLs accordingly, so it needs no configuration either
way.

### Production (LAMP)

Point a virtual host at this folder. PHP 7.4 or newer. `mod_rewrite`,
`mod_headers`, `mod_deflate` and `mod_expires` are used if present and skipped
cleanly if not.

**One filled button per page.** Hick's Law rule 3 is enforced centrally in
`includes/header.php`, not page by page. The brief mandates the "Ask About a
Medicine" button in the header everywhere, so that is the site's default primary
action and any body button pointing at the same place is the outline variant. A
page with a more specific primary action — the medicine lookup, the quote form,
the `/providers` gate — sets `$page['own_primary'] = true` and the header button
steps down so the two do not compete. On `/enquire` the header button would point
at the page you are already reading, so it is not rendered at all. If you add a
page, decide which single action is filled.

**If you deploy into a subfolder rather than a domain root**, edit the two
`ErrorDocument` lines at the bottom of `.htaccess` to include the subfolder path.
Everything else adapts on its own.

### Quick check without Apache

```
php -S 127.0.0.1:8000 -t .
```

---

## Placeholders that must be filled before launch

Every one of these lives in **`config.php`** and nowhere else. Until a value is
supplied it renders on the page as a conspicuous red marker, so the site cannot
quietly go live with a gap.

| Placeholder | What it needs | Who supplies it |
|---|---|---|
| `legal_entity_name` | Registered business name exactly as it appears on the pharmacy licence | Directors / company registration |
| `pharmacy_licence_no` | Vanuatu pharmacy licence number | Responsible pharmacist |
| `pharmacist_name` | Full name of the responsible pharmacist | Responsible pharmacist |
| `pharmacist_reg_no` | Registration number of the responsible pharmacist | Responsible pharmacist |
| `content_reviewed` | Date the site's content was last reviewed for clinical and legal accuracy, e.g. "March 2026". Shown in the footer margin beside each page's notice reference, the way a gazette carries an issue date. | Responsible pharmacist, at each content review |

Already supplied and live in `config.php`: phone `+678 528 2543`, address
`Ground Floor, Rm 1006, Golden Port, Namba 2 Area, Port Vila`, email
`getmeds.vu@gmail.com`.

### Content marked as outstanding on the pages themselves

These render as visible `Copy required` blocks. They are deliberate: none of it has
been drafted, because drafting it would have meant inventing it.

| Page | What is missing | Who supplies it |
|---|---|---|
| `/privacy`, `/terms`, `/disclaimer`, `/prescription-policy`, `/shipping-rules`, `/returns` | All body copy. Real heading structure is already in place for the copy to fill. | A lawyer qualified in Vanuatu |
| `/report-side-effect` | Regulatory reporting obligations and the authority to report to. The practical "how to tell us" route **is** written. | Lawyer + responsible pharmacist |
| `/complaints` | Formal complaints policy, timeframes, and the external escalation body. The practical route **is** written. | Lawyer + responsible pharmacist |
| `/about/licences` | Issuing authority, licence dates, and the public verification route | Responsible pharmacist |
| `/about/our-pharmacists` | Pharmacist biography and confirmed languages spoken | Responsible pharmacist |
| `/providers/what-we-stock` | Line-item formulary, if one is ever to be published | Responsible pharmacist |
| `/contact` | Local directions, parking, nearest bus route | Someone who knows Port Vila |
| Bislama columns, site-wide | Translations of the plain-language summaries | A Bislama speaker with medical knowledge |
| `/about/our-pharmacists`, `/contact` | Photographs of the premises and staff | Photographer. Licensed stock plates ship elsewhere on the site (see PRODUCT.md, 2026-09-11), but these two routes stay unphotographed on purpose: a stock face beside "our pharmacists", or a stock interior beside the real address, would read as a claim about this business. |

**Nothing above has been guessed at.** No licence number, formulary entry,
testimonial, price, or translation appears anywhere in this codebase.

---

## Before you go live

1. Fill the five placeholders in `config.php`.
2. Get the legal pages drafted and paste the copy in.
3. Set `enquiry_mail_enabled => true` in `config.php` **once a real mail transport
   is configured on the server.** Until then every submission is written to
   `data/enquiries.log` and the sender still sees a confirmation and a reference,
   so nothing is silently lost. Check that log during the transition.
4. Change `enquiry_recipient` to a monitored mailbox rather than the public address.
5. Decide the `/providers` access question (see below).
6. Serve over HTTPS. The session cookie sets its `secure` flag automatically once
   you do.
7. Make sure `data/` is not web-readable. `.htaccess` already blocks it on Apache;
   confirm it if you use nginx.

The audit and layout-measuring harnesses used during the build have already been
removed; nothing named `_*.php` should exist in this folder. `ACCESSIBILITY.md`
records how to recreate them if you want to re-run the audit.

---

## The `/providers` gate

`/providers` and everything under it carries a `noindex` tag and an interstitial
saying the section is written for healthcare professionals.

This is a **placeholder for a real access-control decision**, pending legal advice
on whether Vanuatu restricts advertising cancer treatment to the general public. It
is not a security boundary and is deliberately not described as one.

Everything about it lives in **`includes/provider-gate.php`**. Change
`provider_gate` in `config.php` to switch behaviour:

- `interstitial` — notice plus a continue button (current)
- `open` — no gate
- `closed` — section unavailable, contact details only

To tighten it into real access control, that one file is where to do it. Comments
inside mark where an account check, an access code, or an audit log would go. No
other file in the site knows the gate exists.

---

## How it is put together

```
config.php            Every site-wide value. Edit here, nowhere else.
.htaccess             Clean URLs, security headers, compression, caching.
index.php             Home: the two-door split.
404.php               Not found.

includes/
  bootstrap.php         Config loader, URL helpers, nav tree, CSRF. Required first.
  head.php              <head> and the skip link.
  header.php            Masthead. Opens <main>.
  nav.php               The six-item nav.
  footer.php            Closes <main>. Compliance record and the nine policy links.
  components.php        Repeated page parts: page_open, chunk lists, lookup,
                        parallel column, legal page scaffold.
  icons.php             The whole icon set. Four authored SVGs, one stroke weight.
  provider-gate.php     THE healthcare-professional gate. One file.
  medicine-disclaimer.php  Group product disclaimer, foot of every /medicines page.
  enquiry-handler.php   Validation, two-step flow, spam traps, delivery.

data/medicines.php    Medicine CATEGORIES. Contains no drug names, on purpose.
assets/css/site.css   The whole design system. One file.
assets/js/site.js     Enhancement only. Nothing depends on it.
assets/fonts/         Public Sans, self-hosted, variable weight 400-700.
```

Every page follows the same shape:

```php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';
$page = ['title' => '…', 'desc' => '…', 'ref' => 'GV-…'];
include INC . '/head.php';
include INC . '/header.php';
// page content
include INC . '/footer.php';
```

A page with a form calls `ensure_session();` before `head.php`, because the session
must start before any output.

### Navigation

The nav lives in one array, `nav_tree()` in `bootstrap.php`. The header menu and the
landing pages that list their own children both read from it, so the two cannot
drift. Labels are fixed by the brief and are not to be reworded.

---

## Design notes

The visual world is the graphic tradition of an official Vanuatu ministry notice:
ink rules and clause structure carry every hierarchy. There are **no cards, no
shadows, and no rounded corners anywhere**, and that is a decision rather than an
omission. Where this category reaches for a grid of identical cards, this site uses
hairline rows.

The palette is five values and no more:

| Token | Hex | Use |
|---|---|---|
| `--ink` | `#0F2A3D` | Body text, headings, rules |
| `--action` | `#1D9FDA` | Interactive elements only, never decoration |
| `--paper` | `#FBFBF9` | Page background |
| `--slate` | `#54687A` | Secondary text, rules |
| `--alert` | `#B3261E` | Errors and safety notices only |

**One important constraint you need to know before editing.** `--action` measures
**2.89:1** against `--paper`. That fails WCAG AA for body text (4.5:1) *and* for
large text and interface components (3:1). So it is never used as a text colour on
paper. It appears as a fill, a rule, or an underline. Ink text on an `--action`
fill measures 4.95:1 and passes, which is how the primary button carries the group
blue legally. If you set blue text on the paper background, you break AA. See
`ACCESSIBILITY.md`.

Typography is one family, Public Sans, at eight sizes. Hierarchy comes from weight
and size alone.

### The parallel column

Vanuatu's official languages are Bislama, English and French, and the gazette form
this design borrows from is natively parallel-text. Patient pages carry a control
that opens a Bislama column beside the English, correctly marked `lang="bi"`.

The mechanism is complete. **The Bislama is not written**, and must not be machine
translated — these are medical instructions. Each row renders a visible marker until
a Bislama speaker with medical knowledge supplies the words.

---

## What this site deliberately does not have

No cart, checkout or prices. No payment integration. No patient login. No chatbot
or live chat. No newsletter popup, exit-intent modal, or carousel. No cookie banner,
because no analytics ship and the only cookie is the strictly-necessary session
cookie behind CSRF protection, which does not require consent. Turning analytics on
changes that; `analytics_enabled` in `config.php` is a reminder, not a switch that
does the legal work for you.

No claim anywhere that Getmeds treats, cures, or improves outcomes in cancer. The
pharmacy supplies medicines against a valid prescription. Every line of copy on the
site is written to that limit, and any new copy must be too.

---

## Performance

First load, uncompressed: **about 75KB** total — HTML, the whole stylesheet, the
whole script, the font and the favicon. The budget was 500KB.

The font is a variable woff2 subset to Latin, so weights 400 to 700 cost one 27KB
file rather than three. `latin-ext` loads only if an accented character appears.
Nothing is fetched from a CDN, so there is no third-party DNS lookup, no third-party
TLS handshake, and nothing that can fail independently of your own server.

No JavaScript is required to read any content, navigate, or complete the enquiry
form. The form's two steps are ordinary server round trips.
