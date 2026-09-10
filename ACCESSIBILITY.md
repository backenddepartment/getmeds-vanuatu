# Accessibility

WCAG 2.2 AA is treated as the floor here, not the target.

This audience skews older, often reads English as a second or third language, and
frequently has chemotherapy-induced peripheral neuropathy, which makes precise
tapping genuinely difficult. Several decisions below are deliberately stricter
than the standard for that reason, and they are marked.

This document records **what was actually tested and measured**, and **what is
still open**. Nothing here is a self-assessment where a measurement was possible.

---

## What was tested

### Automated: axe-core 4.10.2

Run against every rendered route at 1440px, with the rule tags
`wcag2a`, `wcag2aa`, `wcag21a`, `wcag21aa`, `wcag22aa` and `best-practice`.

| Scope | Result |
|---|---|
| All 32 routes | **0 violations** |
| `/providers/*` with the gate opened (the content behind the interstitial) | **0 violations** |
| Enquiry form error state, three simultaneous inline errors | **0 violations** |

One `incomplete` result remains, on both textarea fields: axe cannot compute a
contrast ratio for an element with no text in it. The textarea's text colour is
`--ink` on `--paper`, which measures 14.28:1. Not a defect.

One real violation was found and fixed during the audit: `heading-order` on
`/patients/how-to-order/`, where the four step headings were `h3` under an `h1`
with no intervening `h2`. They are now `h2`, which is the correct level for a
top-level division of that page.

### Contrast: computed, not eyeballed

Every pair was computed against the WCAG relative-luminance formula rather than
judged by eye, because the brief asked for exactly that.

| Pair | Ratio | Body 4.5:1 | Large / UI 3:1 |
|---|---|---|---|
| `--ink` on `--paper` | **14.28:1** | Pass | Pass |
| `--slate` on `--paper` | **5.57:1** | Pass | Pass |
| `--alert` on `--paper` | **6.31:1** | Pass | Pass |
| `--paper` on `--ink` fill | **14.28:1** | Pass | Pass |
| `--ink` on `--action` fill | **4.95:1** | Pass | Pass |
| `--action` on `--paper` | **2.89:1** | **FAIL** | **FAIL** |

**The last row is the single most important thing to know before editing this
site.** The brief pins `--action` (`#1D9FDA`, the Getmeds group blue) as the
interactive colour, and it pins `--paper` (`#FBFBF9`) as the page background. Those
two cannot legally carry text together: 2.89:1 fails AA for body text *and* fails
the 3:1 threshold for large text and interface components.

Resolved without adding a sixth colour, and without weakening either pinned rule:

- `--action` is **never** a text colour on paper. Not for links, not for headings,
  not for labels.
- It appears as a **fill** behind `--ink` text (4.95:1, passes), as a **rule**, or
  as an **underline** beneath ink text.
- The primary button is therefore an `--action` fill with `--ink` text. That puts
  the group blue on the site's single most important control, legally.
- Links are `--ink` text with an `--action` underline. Colour is never the only
  cue that something is a link — the underline is always present.
- The focus ring is `--ink`, which is 14.28:1 on paper and 4.95:1 on an action
  fill, so it is clearly visible against every surface in the design.

If you set blue text on the paper background, you break AA. This is the one trap
in the stylesheet and it is commented at the palette definition.

### Target sizes: measured

Measured with `getBoundingClientRect()` on every `a`, `button`, `input`, `select`,
`textarea` and choice row, at 320px and 390px.

- **Every block-level interactive target is at least 48x48px.** The brief asked for
  48px rather than the 44px standard, because of neuropathy in this audience, and
  that is the value in the stylesheet (`--target: 3rem`).
- Gaps between adjacent targets are at least 12px, above the 8px minimum.
- Four defects were found by measurement and fixed: the nine footer policy links
  (43.2px), the wordmark home link (42.8px), and the primary value links inside
  record rows such as the phone number and email address.

**Two targets remain under 48px, deliberately.** Both are links that sit inline
inside a sentence of body copy. WCAG 2.5.8 explicitly exempts these: *"the target
is in a sentence or its size is otherwise constrained by the line-height of
non-target text."* Forcing 48px on an inline link would break the paragraph it
sits in. Every one of these also has a full-size equivalent elsewhere on the same
page — the inline phone number in a sentence is also the 48px phone target in the
masthead and in the footer record.

### Layout: measured at 320, 390 and 1440

- `document.scrollWidth === document.clientWidth` at all three widths.
- Zero elements extend past the right edge at any width.
- No horizontal scroll at 320px, which is the narrowest width the brief named.

Note on how mobile was measured: headless Chrome on Windows silently refuses a
window narrower than about 504px, so `--window-size=390` renders at 504 and the
screenshot is a lie. Mobile was measured and captured through a same-origin iframe
pinned to exactly 390px, where `clientWidth` really did report 390.

### Typography: measured

- **Body text is exactly 18px** with a 28.8px line-height (1.6), as the brief
  requires.
  This was a real bug caught by measurement: the root was set to `112.5%` *and*
  `body` to `1.125rem`, which compounded to a 20.25px body and made every spacing
  token 12.5% larger than its own documentation claimed. The root is now `100%`, so
  1rem follows the reader's own browser setting, and the 18px base is applied once.
- Body measure is 65ch, under the 70-character limit.
- **Every heading-to-body gap is 16px.** Also a real bug caught by measurement:
  `h2 + *` scores the same CSS specificity as `p`, and `p`'s `margin` shorthand
  appeared later in the file, so it was resetting every heading-to-body gap on the
  site to **0px**. The adjacency rule now comes after the element margins.
- One family, Public Sans, at eight sizes. Hierarchy is size and weight only.

### Keyboard and semantics

- Skip-to-content is the first focusable element on every page, and becomes visible
  on focus.
- `:focus-visible` is a 2px `--ink` outline with a 3px offset. `outline: none`
  appears exactly once in the stylesheet, immediately followed by the
  `:focus-visible` rule that replaces it.
- Navigation dropdowns are operable by keyboard with no JavaScript at all: the
  panel opens on `:hover` and on `:focus-within`. With JavaScript they become
  click-operated instead, with `aria-expanded`, Escape to close, click-outside to
  close, and ArrowDown to enter the panel. **Click rather than hover is a
  deliberate choice for this audience** — a tremor or an imprecise pointer should
  not close a menu somebody is still reading.
- Every parent nav item is itself a link to a landing page that lists the same
  children as body content, so nothing on this site is reachable only by opening a
  dropdown.
- Real `<header>`, `<nav>`, `<main>`, `<footer>`. One `<h1>` per page. Heading
  levels never skipped (verified by axe across all routes).
- ARIA is used only where semantics fall short: `aria-expanded` on disclosures,
  `aria-current="page"`, `role="alert"` on the form error summary,
  `aria-describedby` linking fields to their hints and errors.

### Forms

- Every input has a persistent visible `<label>`. No field uses a placeholder as a
  label.
- Errors appear inline beside the field, in text, and describe the fix rather than
  the failure — "Add a phone number. This is how a pharmacist will reach you."
  rather than "Invalid input."
- Errors are never signalled by colour alone: an error field gets a 2px `--alert`
  border, an authored warning icon, and a text message.
- An error summary appears at the top with `role="alert"`, each item linking to its
  field. It is headed "Nothing has been sent yet", because the first thing a
  worried person needs to know is that they have not lost their work.
- Safe defaults are pre-selected: country is Vanuatu, contact method is phone.
- At most five fields are visible at once, and the form asks about the medicine
  before it asks who you are.
- **No JavaScript is required to complete the form.** Progressive disclosure is two
  ordinary server round trips, so there is no client-side step machine to get out
  of sync on a bad connection.
- Spam is caught with a honeypot field and a minimum completion time. There is no
  CAPTCHA, deliberately: asking somebody on chemotherapy to solve a puzzle to ask
  about their medicine is not acceptable.

### Motion

- `prefers-reduced-motion: reduce` sets every transition and animation duration to
  zero.
- There are no entrance animations anywhere. Nothing fades or slides in on scroll.
- The only motion on the site is the door's hover and focus bar, which animates a
  `transform` (not `height`) so it cannot cause layout thrash, and small colour
  transitions on controls.

### Other

- `lang="en-VU"` on every page. The Bislama parallel columns carry `lang="bi"`, with
  their English scaffolding marked `lang="en"` inside them.
- Plain language throughout, short sentences, aiming around an 8th-grade reading
  level. "Medicine", never "pharmaceutical product". "How to order", never
  "procurement pathway".
- There are no photographs yet, so there is no `alt` text debt. Every future image
  must carry it; decorative images get `alt=""`. The placeholder blocks that stand
  in for photographs are text, not images.
- Browser surfaces are themed from the palette rather than left at browser
  defaults: text selection, the caret, `accent-color`, and the scrollbar.
- A print stylesheet is included, because this audience prints pages to take to a
  doctor. `/patients/what-you-need` in particular is meant to be printable.
- `forced-colors: active` is handled, mapping the accent and focus ring to system
  `Highlight`.

---

## What remains open

These are real gaps, not hedges. None of them is blocked on code.

1. **Bislama translations are missing.** The parallel-column mechanism is complete
   and correct, but every Bislama row renders a visible
   `[[BISLAMA TRANSLATION REQUIRED]]` marker. These are medical instructions and
   **must not be machine translated.** A Bislama speaker with medical knowledge has
   to write them. Until then, an ESL reader gets the English only, which is the
   status quo rather than a regression.

2. **French is not offered at all.** French is an official language of Vanuatu and
   is widely spoken. The parallel-column component takes an arbitrary second
   language, so adding French is a content job rather than a build job, but it has
   not been scoped or decided.

3. **No testing with real users.** Nothing here has been in front of a patient, a
   family member, a prescriber, or anyone with neuropathy. Automated tools verify
   that the rules are satisfied; they cannot tell you whether a frightened person
   can find the phone number. This is the single largest open item and no amount of
   axe passes substitutes for it.

4. **No screen-reader testing on real assistive technology.** Semantics and ARIA are
   correct by inspection and by axe, but the site has not been driven with NVDA,
   JAWS or VoiceOver. The enquiry form's two-step flow and the error summary are
   the two places most likely to need adjustment.

5. **200% zoom not yet verified by hand.** The layout is built in relative units and
   has no horizontal overflow at 320px, which is a strong proxy, but the brief asked
   specifically for 200% zoom and that has not been walked through manually.

6. **LCP on a real 3G connection is unmeasured.** Total first-load weight is 75KB
   uncompressed against a 500KB budget, and nothing is fetched from a third party,
   so the budget is comfortably met. The 2.5s LCP target has not been measured on a
   throttled connection or on a real device in Vanuatu.

7. **Reading level is not formally scored.** Copy was written to be plain and short,
   but no readability metric has been run over it, and "around 8th grade" is an
   intention rather than a measurement.

8. **Contrast of future content is not guaranteed.** The palette is safe as used
   today. The `--action`-on-paper trap described above will break AA the moment
   somebody sets blue text, and only a human reading this file will prevent it.

9. **The legal pages have no body copy yet**, so their final reading level, link
   density and structure cannot be assessed. They ship with real headings and
   visible markers.

10. **Focus order has not been verified on the provider gate transition**, where a
    POST replaces the page content. It is a normal page load so it should be
    correct, but it has not been walked.

---

## How to re-run the automated audit

The audit harness was intentionally not left in the shipped site. To repeat it:

```
php -S 127.0.0.1:8850 -t .
```

Then run axe-core against each route, either through the browser extension or by
injecting `axe.min.js` into the rendered page and calling:

```js
axe.run(document, { runOnly: { type: 'tag',
  values: ['wcag2a','wcag2aa','wcag21a','wcag21aa','wcag22aa','best-practice'] } })
```

Test these states explicitly, because a crawl of the default pages will miss them:

- the enquiry form at step 1 **and** step 2
- the enquiry form's error state, with several fields failing at once
- the enquiry confirmation, with its reference number
- `/providers` **before** continuing past the interstitial, and after
- any page with JavaScript disabled, which changes the navigation to hover-operated
  and reveals the Bislama columns
