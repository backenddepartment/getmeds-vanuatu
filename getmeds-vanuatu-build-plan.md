# Build Prompt — Getmeds Vanuatu Website

> Paste everything below the line into your coding agent. Fill in the `[[PLACEHOLDER]]`
> values first, or leave them and the agent will render them as visible TODO markers.

---

## Role

You are building a small, production-ready website for **Getmeds Vanuatu — Pacific
Chemotherapy Pharmacy**, a specialty oncology pharmacy in Port Vila, Vanuatu. It is part
of the Getmeds group (getmeds.ph, getmedshealthcare.com).

This is a **small site — roughly 15 pages**. Do not over-engineer it. No build step, no
framework, no package manager, no database.

## Stack

- Plain HTML5, CSS3, and minimal vanilla JavaScript.
- PHP only for shared includes (`header.php`, `footer.php`, `nav.php`) and the enquiry
  form handler. Runs on XAMPP/Apache locally, standard LAMP in production.
- No CDN dependencies. Self-host fonts, CSS, and JS. Connections in Vanuatu are slow and
  metered; every external request is a failure point.
- One stylesheet. One small JS file. That is the whole front end.

---

## The audience, and why it dictates everything

Three groups arrive at this site:

1. **Patients and their families** — often stressed, frequently older, sometimes reading
   English as a second or third language (Bislama and French are widely spoken). Many are
   on a phone, on mobile data, with a cancer diagnosis they received days ago.
2. **Prescribers** — oncologists and nurses at Vila Central Hospital and the private
   hospital, who need availability, ordering, and handling details fast.
3. **Institutional buyers** — hospitals, ministries and NGOs across Fiji, Solomon Islands
   and the wider Pacific.

Every design decision serves group 1 first. A person navigating this site may be doing so
on the worst week of their life. Cognitive load is the enemy.

---

## Governing principle: Hick's Law

Hick's Law says decision time rises logarithmically with the number of options presented.
Under stress and fatigue, that curve gets steeper. Apply it as hard rules, not vibes:

### Hard rules

1. **Maximum 6 top-level nav items.** No exceptions, no "just one more."
2. **One binary choice at the entry point.** The homepage hero is not a slogan, a stock
   photo, or a carousel — it is a single question and two large routes: *"Are you a
   patient or family member?"* / *"Are you a doctor, nurse or hospital?"* One decision,
   two options, and everything downstream is filtered.
3. **One primary action per page.** Exactly one visually dominant button. Anything else is
   a text link. If a page seems to need two primary actions, it is two pages.
4. **Maximum 5 items in any dropdown.** Chunk beyond that into a landing page.
5. **No carousels, no sliders, no accordion walls, no mega menus.**
6. **Progressive disclosure on forms.** Show a maximum of 5 fields at once. Ask the
   minimum needed to start a conversation; a pharmacist collects the rest by phone.
7. **Medicine lists are chunked, never dumped.** Group into at most 5 categories with a
   search box above. Never render a 200-item grid.
8. **Pre-select safe defaults** wherever a field has an obvious answer (country = Vanuatu,
   contact method = phone).
9. **Cut, don't collapse.** If content doesn't serve one of the three audiences, delete it
   rather than hiding it behind a toggle.

### Where Hick's Law is deliberately overridden

The footer carries around 9 compliance links. That is correct — footers are scanned, not
decided from, and regulatory content must be findable. Keep the footer visually quiet:
small text, single column on mobile, no icons.

---

## Sitemap and navigation

Use these labels **exactly**. They were chosen for plainness; do not "improve" them.

### Main navbar

| Label | URL | Dropdown items |
|---|---|---|
| Home | `/` | — |
| Medicines | `/medicines` | Cancer Medicines · Medicines for Side Effects · Other Specialty Medicines |
| For Patients | `/patients` | How to Order · What You Need · Prices & Payment · Talk to a Pharmacist · Delivery |
| For Doctors & Hospitals | `/providers` | What We Stock · Order for Your Hospital · Storage & Handling · Request a Quote |
| About Us | `/about` | Who We Are · Our Pharmacists · Our Licences · Part of Getmeds |
| Contact | `/contact` | — |

**Primary CTA button (in the header, on every page):** `Ask About a Medicine` → `/enquire`

### Footer navigation

Privacy Policy `/privacy` · Terms of Use `/terms` · Medical Disclaimer `/disclaimer` ·
Prescription Policy `/prescription-policy` · Shipping & Import Rules `/shipping-rules` ·
Returns & Medicine Disposal `/returns` · Report a Side Effect `/report-side-effect` ·
Our Licences `/about/licences` · Complaints `/complaints`

---

## What NOT to build

- **No shopping cart, no checkout, no add-to-cart, no prices on medicine pages.** Cancer
  medicines cannot be sold that way, and pricing is patient-specific. The conversion action
  is always an enquiry that a pharmacist reviews.
- **No online payment integration.**
- **No patient login or account area.**
- **No chatbot or live-chat widget.**
- **No newsletter popup, no cookie banner unless analytics are actually added,** no exit
  intent modals. Interrupting someone researching chemotherapy access is unacceptable.
- **No claims that Getmeds treats, cures, or improves outcomes in cancer.** The pharmacy
  supplies medicines against a valid prescription. That is the only claim the site makes.
  Write every line of copy to that limit.

---

## Compliance content — render placeholders, invent nothing

The following must appear in the footer of **every page**, pulled from one PHP config file
so it is edited in a single place:

- Registered business name: `[[LEGAL_ENTITY_NAME]]`
- Pharmacy licence number: `[[PHARMACY_LICENCE_NO]]`
- ~~Responsible pharmacist and registration number: `[[PHARMACIST_NAME]]`, `[[PHARMACIST_REG_NO]]`~~
  **Superseded 2026-09-21.** The owner directed that no individual be named on the site,
  which is for ordering. Both values were removed from `config.php` and every page now
  refers to the role. See PRODUCT.md.
- Physical address in Port Vila: `[[STREET_ADDRESS]]`
- Phone: `[[PHONE]]` — rendered as a `tel:` link, and visible in the header on every page.
  Phone will be the first contact channel for most patients.

Each of the nine footer pages should be built with real headings and structure, with body
copy marked `[[LEGAL COPY REQUIRED — do not draft]]`. Do not write privacy, terms, or
disclaimer text yourself. The one exception: adapt the Getmeds group's standing product
disclaimer, which states that product information is for educational purposes only, does
not constitute medical advice, a prescription, or an endorsement for a specific condition;
that prescription medicines require a valid prescription from a licensed physician; and
that self-medication is discouraged. Place it at the foot of every page under `/medicines`.

**Build `/providers` behind a simple gate.** Add a `noindex` meta tag and an interstitial
stating the section is intended for healthcare professionals, with a continue button. This
is a placeholder for a real access control decision that is still pending legal advice on
whether Vanuatu restricts advertising cancer treatment to the public. Structure the code so
the gate can be tightened or removed by changing one include.

---

## Accessibility — WCAG 2.2 AA as a floor, not a target

This audience skews older, and chemotherapy-induced peripheral neuropathy makes precise
tapping genuinely difficult. Build accordingly:

- **Touch targets minimum 48×48px** with at least 8px between them. Larger than the 44px
  standard, on purpose.
- **Base font size 18px**, body line-height 1.6, line length under 70 characters. Layout
  must survive 200% zoom and 320px width without horizontal scroll.
- **Contrast:** 4.5:1 for body text, 3:1 for large text and interface components. Verify
  every pair; do not eyeball it.
- **Semantic HTML.** Real `<nav>`, `<main>`, `<header>`, `<footer>`, one `<h1>` per page,
  heading levels never skipped. ARIA only where semantics genuinely fall short.
- **Full keyboard operability** with a visible focus ring: 2px solid, 3:1 contrast against
  both the component and the background. Never `outline: none`.
- **Skip-to-content link** as the first focusable element.
- **Forms:** every input has a persistent visible `<label>` — placeholders are not labels.
  Errors appear inline next to the field, in text, describing the fix. Never colour alone.
- **`prefers-reduced-motion: reduce`** honoured; under it, all transitions go to zero.
- **`lang` attribute set correctly**, and per-element `lang` if any Bislama or French
  appears.
- **Plain language throughout**, aiming around an 8th-grade reading level. Short sentences.
  Say "medicine," not "pharmaceutical product." Say "how to order," not "procurement pathway."
- **Alt text on every image.** Decorative images get `alt=""`.

Run an axe or Lighthouse accessibility audit before you call it done, and fix everything
it flags rather than reporting the score.

---

## Performance budget

- Total page weight under 500KB including fonts and images.
- Largest Contentful Paint under 2.5s on a simulated 3G connection.
- Images in WebP with fallbacks, lazy-loaded below the fold, correctly sized.
- Fonts self-hosted, subset to Latin, `font-display: swap`.
- No JavaScript required to read any content or navigate. JS enhances; it never gates.

---

## Design direction

Reject the two obvious defaults: the sterile corporate-pharma blue gradient, and the soft
pastel cancer-charity look. This is a pharmacy whose entire value proposition is *the
medicine is real, it is stored properly, and it is here.* The design should feel like
clinical precision delivered with warmth — closer to a well-run dispensary than to a
marketing site.

**Palette** (5 values, and no more):

| Token | Hex | Use |
|---|---|---|
| `--ink` | `#0F2A3D` | Body text, headings |
| `--action` | `#1D9FDA` | Getmeds group blue. Interactive elements **only** — never decoration |
| `--paper` | `#FBFBF9` | Page background |
| `--slate` | `#54687A` | Secondary text, borders |
| `--alert` | `#B3261E` | Errors and safety notices only |

Reserving `--action` exclusively for interactive elements is itself a Hick's Law move: if
blue always means "you can do something here," the eye finds the choice without deliberating.

**Type:** one family, not two. Use a humanist sans with a large x-height and unambiguous
letterforms — Public Sans or Source Sans 3, self-hosted. Establish hierarchy through weight
and size alone. A display serif would add a font file, a render step, and nothing a patient
needs. Restraint here is a functional choice, not an aesthetic shortfall.

**Layout:** single column, left-aligned, generous vertical rhythm. Content max-width 65ch.
Mobile-first; the desktop layout is the mobile layout with more margin.

**Avoid:** all-caps eyebrow labels above headings, identical rounded cards with soft grey
shadows, meta strings joined by middle dots, arrows appended to button text, fade-and-slide
entrance animations on every section, and stock photography of smiling models. If you need
imagery, use real photographs of the Port Vila premises and staff, with
`[[PHOTO NEEDED — describe subject]]` placeholders until they exist.

**Spend the boldness in one place:** the homepage two-door split. Make that choice large,
confident, and unmissable. Everything else stays quiet.

---

## Copy guidance

Write the non-legal copy yourself, following these rules:

- Sentence case everywhere. Active voice. No filler.
- A button says what happens: "Ask About a Medicine," not "Submit" or "Learn More."
- Never imply the pharmacy provides treatment or medical advice.
- Lead with what the person needs to do next, not with who Getmeds is.
- Empty states and errors give direction, not apology.

---

## Deliverables

1. The full site as described, running on XAMPP with no configuration beyond dropping it in
   `htdocs`.
2. One `config.php` holding every `[[PLACEHOLDER]]` value in one place.
3. A `README.md` listing every placeholder, what it needs, and who has to supply it.
4. A short `ACCESSIBILITY.md` recording what was tested and what remains open.

Before you write any code, output your file structure and the homepage wireframe as ASCII,
and wait for approval.