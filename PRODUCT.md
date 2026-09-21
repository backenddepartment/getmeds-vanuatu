# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

Plain HTML5, CSS3, and minimal vanilla JavaScript. PHP only for shared includes
(`header.php`, `footer.php`, `nav.php`) and the enquiry form handler. Runs on XAMPP/Apache
locally, standard LAMP in production.

Hard constraints on the stack, set by the brief:

- No build step, no framework, no package manager, no database.
- No CDN dependencies. Fonts, CSS, and JS are self-hosted. Connections in Vanuatu are slow
  and metered, so every external request is treated as a failure point.
- One stylesheet, one small JS file. That is the whole front end.
- No JavaScript may be required to read content or navigate. JS enhances; it never gates.

## Users

Three audiences arrive, and they are explicitly ranked. Every design decision serves the
first before the others.

1. **Patients and their families.** Often stressed, frequently older, sometimes reading
   English as a second or third language (Bislama and French are widely spoken in Vanuatu).
   Many arrive on a phone, on mobile data, days after a cancer diagnosis. A person
   navigating this site may be doing so on the worst week of their life.
2. **Prescribers.** Oncologists and nurses at Vila Central Hospital and the private
   hospital, who need availability, ordering, and handling details fast.
3. **Institutional buyers.** Hospitals, ministries, and NGOs across Fiji, Solomon Islands,
   and the wider Pacific.

Chemotherapy-induced peripheral neuropathy is common in audience 1, which makes precise
tapping genuinely difficult. This is a product fact, not a hypothetical.

## Product Purpose

Getmeds Vanuatu — Pacific Chemotherapy Pharmacy is a specialty oncology pharmacy in Port
Vila, Vanuatu. The site exists so that a patient, prescriber, or institutional buyer can
find out whether a medicine is available and start a conversation with a pharmacist about
getting it.

Success is a completed enquiry that a pharmacist can act on, or a phone call placed. There
is no other conversion.

## Positioning

The value proposition is availability and integrity of supply, not care: *the medicine is
real, it is stored properly, and it is here.* A neighbouring pharmacy in the region cannot
truthfully copy the combination of specialty oncology stock, cold-chain handling, and a
licensed dispensing presence physically in Port Vila.

Part of the Getmeds group (getmeds.ph, getmedshealthcare.com).

## Operating Context

- Enquiries are reviewed by a pharmacist, who collects remaining detail by phone. Phone is
  the first contact channel for most patients.
- Every medicine supplied requires a valid prescription from a licensed physician.
- Pricing is patient-specific and is never published.
- Cold-chain storage and handling are part of the actual service and are relevant to
  prescribers and institutional buyers.
- Delivery covers Port Vila and reaches beyond it within Vanuatu; institutional supply
  extends across the Pacific.

## Capabilities and Constraints

Deliberately **not** built, each for a stated reason:

- No shopping cart, checkout, add-to-cart, or prices on medicine pages. Cancer medicines
  cannot be sold that way and pricing is patient-specific.
- No online payment integration.
- No patient login or account area.
- No chatbot or live-chat widget.
- No newsletter popup, no exit-intent modal, and no cookie banner unless analytics are
  actually added. Interrupting someone researching chemotherapy access is unacceptable.

Claim limits, which bound every line of copy on the site:

- No claim that Getmeds treats, cures, or improves outcomes in cancer. The pharmacy
  supplies medicines against a valid prescription. That is the only claim the site makes.
- Privacy, terms, and disclaimer copy is not to be drafted by the builder. Those pages ship
  with real headings and structure and a `[[LEGAL COPY REQUIRED — do not draft]]` marker.
- The one legal exception is the Getmeds group standing product disclaimer, which appears at
  the foot of every `/medicines` page: product information is educational only, is not
  medical advice, a prescription, or an endorsement for a specific condition; prescription
  medicines require a valid prescription from a licensed physician; self-medication is
  discouraged.

Access control that is still open:

- `/providers` ships behind a `noindex` tag and an interstitial stating the section is
  intended for healthcare professionals, with a continue button. This is a placeholder for a
  real access-control decision pending legal advice on whether Vanuatu restricts
  advertising cancer treatment to the public. The code is structured so the gate tightens or
  is removed by changing one include.

Terminology, set deliberately for audience 1: say "medicine," not "pharmaceutical product";
"how to order," not "procurement pathway."

## Brand Commitments

Binding constraints the user pinned in the brief. These override any pattern preference.

- **Palette, five values and no more.** `--ink #0F2A3D` (body text, headings), `--action
  #1D9FDA` (Getmeds group blue; interactive elements only, never decoration), `--paper
  #FBFBF9` (page background), `--slate #54687A` (secondary text, borders), `--alert
  #B3261E` (errors and safety notices only).
- **One type family, not two.** A humanist sans with a large x-height and unambiguous
  letterforms — Public Sans or Source Sans 3 — self-hosted. Hierarchy through weight and
  size alone. No display serif.
- **Layout.** Single column, left-aligned, generous vertical rhythm, content max-width
  65ch, mobile-first. The desktop layout is the mobile layout with more margin.
- **Rejected defaults.** The sterile corporate-pharma blue gradient, and the soft pastel
  cancer-charity look. The target feeling is clinical precision delivered with warmth —
  closer to a well-run dispensary than to a marketing site.
- **Named anti-patterns.** All-caps eyebrow labels above headings; identical rounded cards
  with soft grey shadows; meta strings joined by middle dots; arrows appended to button
  text; fade-and-slide entrance animations on every section; stock photography of smiling
  models.
- **Boldness is spent in exactly one place:** the homepage two-door split. Everything else
  stays quiet.
- **Voice.** Sentence case everywhere. Active voice. No filler. A button says what happens
  ("Ask About a Medicine," never "Submit" or "Learn More"). Lead with what the person needs
  to do next, not with who Getmeds is. Empty states and errors give direction, not apology.
- **Navigation labels are fixed** and were chosen for plainness; they are not to be
  "improved." Home, Medicines, For Patients, For Doctors & Hospitals, About Us, Contact.
  Header CTA on every page: Ask About a Medicine.

## Evidence on Hand

Confirmed real values, supplied by the user:

- Phone: `+678 528 2543`
- Address: Ground Floor, Rm 1006, Golden Port, Namba 2 Area, Port-Vila, Vanuatu
- Email: `getmeds.vu@gmail.com`

Deliberately absent. Future work must not fabricate these:

- Registered legal entity name and pharmacy licence number. Both render as visible
  placeholders from one config file.
- **No individual is named anywhere on the site.** The responsible pharmacist's name and
  registration number were removed on 2026-09-21 at the owner's direction: this is an
  ordering site, not a staff directory. Pages refer to the role, never the person. Do not
  reintroduce a name, a registration number, or a photograph of staff.
- **No stock list or formulary.** The user confirmed that medicine pages carry category
  structure and search only, with no drug names. Nothing may imply that a specific molecule
  is held in stock until a pharmacist confirms it.
- ~~No photography of the Port Vila premises or staff yet. Imagery, if used, must be real
  photographs of the premises and staff, marked `[[PHOTO NEEDED — describe subject]]` until
  they exist. Stock photography of models is prohibited.~~
  **Superseded 2026-09-11.** The owner directed a move to high fidelity using stock
  photography. Twenty licensed stock photographs now ship, duotoned to the ink palette, with
  provenance for each in `data/images.php`. The anti-pattern the original rule was defending
  against — *smiling models* — still holds and was not shipped: the photographs are
  environmental and procedural, and where a person appears they are working and turned away
  or cropped to hands. Three guardrails stand in place of the old prohibition:
  1. The footer carries a standing line on every page: the photographs are licensed stock,
     and are not our premises or our staff.
  2. `/about/our-pharmacists` takes no photograph. A stock face beside "our pharmacists"
     would be a fabricated credential, and since 2026-09-21 no staff are shown at all.
  3. `/contact` takes no photograph. A stock pharmacy interior beside the real street
     address would read as a photograph of that address.

  Real photographs of the premises and staff remain the goal, and should replace the stock
  plates route by route as they are taken.
- No testimonials, patient stories, outcome data, or pricing exist.

## Product Principles

1. **Cognitive load is the enemy.** Hick's Law is applied as hard rules, not vibes: maximum
   6 top-level nav items; one binary choice at the entry point; one primary action per page;
   maximum 5 items in any dropdown; maximum 5 form fields visible at once; medicine lists
   chunked into at most 5 categories, never dumped.
2. **Cut, don't collapse.** Content that does not serve one of the three audiences is
   deleted rather than hidden behind a toggle. No carousels, sliders, accordion walls, or
   mega menus.
3. **The pharmacy supplies medicine; it does not provide care.** Every line of copy is
   written to that limit.
4. **Reserving the accent for interactivity is a usability decision, not a style one.** If
   blue always means "you can do something here," the eye finds the choice without
   deliberating.
5. **The connection is the constraint.** Under 500KB per page including fonts and images,
   LCP under 2.5s on simulated 3G, nothing loaded from a third party.

## Accessibility & Inclusion

WCAG 2.2 AA is the floor, not the target. Established product requirements:

- Touch targets minimum 48×48px with at least 8px between them — larger than the 44px
  standard, on purpose, because of neuropathy in this audience.
- Base font size 18px, body line-height 1.6, line length under 70 characters. Layout
  survives 200% zoom and 320px width with no horizontal scroll.
- Contrast 4.5:1 for body text, 3:1 for large text and interface components, verified
  rather than eyeballed.
- Semantic HTML: real `<nav>`, `<main>`, `<header>`, `<footer>`, one `<h1>` per page,
  heading levels never skipped. ARIA only where semantics genuinely fall short.
- Full keyboard operability with a visible focus ring, 2px solid at 3:1 against both the
  component and the background. Never `outline: none`.
- Skip-to-content link as the first focusable element.
- Every input has a persistent visible `<label>`; placeholders are not labels. Errors appear
  inline beside the field, in text, describing the fix. Never colour alone.
- `prefers-reduced-motion: reduce` honoured, with all transitions going to zero.
- `lang` set correctly, with per-element `lang` for any Bislama or French.
- Plain language throughout, around an 8th-grade reading level, short sentences.
- Alt text on every image; decorative images get `alt=""`.
