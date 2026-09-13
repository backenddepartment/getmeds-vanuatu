---
name: Getmeds Vanuatu — Pacific Chemotherapy Pharmacy
description: An official Vanuatu ministry notice, where ink rules and clause structure carry every hierarchy.
colors:
  ink: "#0F2A3D"
  action: "#1D9FDA"
  paper: "#FFFFFF"
  slate: "#54687A"
  alert: "#B3261E"
  hair: "rgba(15, 42, 61, .18)"
  hair-strong: "rgba(15, 42, 61, .38)"
  wash: "rgba(15, 42, 61, .035)"
  action-wash: "rgba(29, 159, 218, .10)"
  alert-wash: "rgba(179, 38, 30, .055)"
typography:
  display:
    fontFamily: "'Public Sans', ui-sans-serif, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
    fontSize: "clamp(2.375rem, 1.55rem + 3.3vw, 3.5rem)"
    fontWeight: 600
    lineHeight: 1.08
    letterSpacing: "-.028em"
  headline:
    fontFamily: "'Public Sans', ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(1.875rem, 1.40rem + 1.9vw, 2.5rem)"
    fontWeight: 600
    lineHeight: 1.14
    letterSpacing: "-.02em"
  title:
    fontFamily: "'Public Sans', ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(1.4375rem, 1.20rem + .95vw, 1.75rem)"
    fontWeight: 600
    lineHeight: 1.14
    letterSpacing: "-.015em"
  subtitle:
    fontFamily: "'Public Sans', ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.25rem"
    fontWeight: 600
    lineHeight: 1.14
    letterSpacing: "-.01em"
  body:
    fontFamily: "'Public Sans', ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: "normal"
  label:
    fontFamily: "'Public Sans', ui-sans-serif, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 600
    lineHeight: 1.2
    letterSpacing: "0"
  small:
    fontFamily: "'Public Sans', ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: "0"
  micro:
    fontFamily: "'Public Sans', ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.8125rem"
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: "0"
rounded:
  none: "0"
spacing:
  s-1: "0.25rem"
  s-2: "0.5rem"
  s-3: "0.75rem"
  s-4: "1rem"
  s-5: "1.5rem"
  s-6: "2rem"
  s-7: "3rem"
  s-8: "4rem"
  s-9: "6rem"
components:
  button-primary:
    backgroundColor: "{colors.action}"
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    rounded: "{rounded.none}"
    padding: "0.75rem 1.5rem"
    height: "3rem"
  button-primary-hover:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.paper}"
  button-secondary:
    backgroundColor: "transparent"
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    rounded: "{rounded.none}"
    padding: "0.75rem 1.5rem"
    height: "3rem"
  button-secondary-hover:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.paper}"
  button-ghost:
    backgroundColor: "transparent"
    textColor: "{colors.ink}"
    typography: "{typography.body}"
    rounded: "{rounded.none}"
    padding: "0.75rem 1.5rem"
    height: "3rem"
  input-text:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    typography: "{typography.body}"
    rounded: "{rounded.none}"
    padding: "0.75rem"
    height: "3rem"
    width: "100%"
  nav-link:
    backgroundColor: "transparent"
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    rounded: "{rounded.none}"
    padding: "0.75rem 1rem"
    height: "3rem"
  nav-link-hover:
    backgroundColor: "{colors.action-wash}"
    textColor: "{colors.ink}"
  door:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    typography: "{typography.display}"
    rounded: "{rounded.none}"
    padding: "3rem 2rem 0"
    height: "20.25rem"
  door-hover:
    backgroundColor: "{colors.action-wash}"
    textColor: "{colors.ink}"
  notice:
    backgroundColor: "transparent"
    textColor: "{colors.ink}"
    typography: "{typography.body}"
    rounded: "{rounded.none}"
    padding: "1.5rem"
  notice-safety:
    backgroundColor: "{colors.alert-wash}"
    textColor: "{colors.ink}"
    rounded: "{rounded.none}"
    padding: "1.5rem"
  chunk-row:
    backgroundColor: "transparent"
    textColor: "{colors.ink}"
    typography: "{typography.subtitle}"
    rounded: "{rounded.none}"
    padding: "1.5rem 0"
  plate:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.slate}"
    typography: "{typography.caption}"
    rounded: "{rounded.none}"
    padding: "0"
  plate-frame:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    rounded: "{rounded.none}"
    padding: "0"
  band:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.paper}"
    typography: "{typography.title}"
    rounded: "{rounded.none}"
    padding: "3rem 2rem"
  channel-row:
    backgroundColor: "transparent"
    textColor: "{colors.ink}"
    typography: "{typography.subtitle}"
    rounded: "{rounded.none}"
    padding: "1rem 0"
    height: "3rem"
  channel-row-hover:
    backgroundColor: "{colors.action-wash}"
    textColor: "{colors.ink}"
---

# Design System: Getmeds Vanuatu — Pacific Chemotherapy Pharmacy

## Overview

**Creative North Star: "The Gazette Notice"**

This is an official notice board, not a marketing site. Every hierarchy in it is
carried by ink rules and clause structure, the way a Vanuatu ministry gazette
carries them, and the reader's next action is the largest thing on the page. The
whole visual system is one stylesheet (`assets/css/site.css`), five colour
values, one typeface at eight real sizes, and two named rule weights. There is no
framework, no reset library, no third-party request, and no image asset other
than an inline-authored favicon.

The density is deliberately sparse and the space is deliberately deep. A section
does not open with a container; it opens with a 2px ink rule and 4rem of air
above its heading, so the page reads as stacked plates rather than as a scroll of
tiles. Where the category would reach for a card, this system reaches for a
hairline row: category lists, fact records and clause lists are all rows divided
by 1px slate hairlines. The one boxed element on the site is the notice, and the
one place boldness is spent is the homepage two-door split — a single ink frame
containing two equal panels divided by one hairline.

Confirmed visual rejections, both refused by the shipped code: the corporate
pharma arrangement (hero band, three-up row of same-size rounded cards with
icons, soft grey shadows, blue gradient) and its predictable opposite, the pastel
cancer-charity look. Neither is reachable from these tokens — there is no radius,
no shadow, no gradient and no sixth hue in the stylesheet.

**Key Characteristics:**

- Five colour values and no sixth; every other value in the file is a
  transparency of one of the five.
- Radius is 0 globally, asserted with `* { border-radius: 0 }`.
- No elevation. Depth is spatial and structural, never optical.
- Rule weights are named tokens: a 1px slate hairline divides peers, a 2px ink
  rule opens a section.
- One humanist grotesque (Public Sans, self-hosted variable 400–700) at eight
  sizes; weight and size are the only hierarchy devices.
- Tabular figures on every numeral the reader can compare.
- 48px minimum touch target, above the 44px standard, on purpose.
- Nothing moves on interaction: state changes fill reserved slots.

## Colors

An achromatic gazette sheet with one blue that is only ever a surface you can
touch, and one red that only ever means danger or error.

### Primary

- **Group Blue** (`{colors.action}`): The Getmeds group blue, and the site's only
  saturated value. It appears as a fill behind ink text (the one primary button,
  the skip link), as a 2px underline on links and on the masthead phone number,
  as the 6px bar that grows in a door's reserved slot, as the inset current-page
  mark in the nav, and as `caret-color`, `accent-color` and `::selection`
  background. It measures 2.89:1 against paper and therefore never sets text.

### Neutral

- **Gazette Ink** (`{colors.ink}`): All body text, all headings, every 2px
  section rule, every frame, and the focus ring. 14.28:1 on paper, 4.95:1 on a
  group-blue fill.
- **Notice Paper** (`{colors.paper}`): The page ground, and the text colour when
  ink becomes a fill on hover. A warm off-white, not pure white.
- **Margin Slate** (`{colors.slate}`): Every hairline rule and all secondary
  text — record terms, ledes, clause detail, hints, footer body, the notice
  reference in the footer margin. 5.57:1 on paper.
- **Hairline** (`{colors.hair}`) and **Stroke** (`{colors.hair-strong}`): Ink at
  18% and 38%. The lighter is the pending progress segment and a hover border on
  the nav disclosure; the heavier is the resting stroke on every input, choice
  row and ghost button.
- **Ink Wash** (`{colors.wash}`): Ink at 3.5%. The scrollbar track, the disabled
  and hovered ground on outline controls, and the photo-needed marker's ground.
- **Blue Wash** (`{colors.action-wash}`): Group blue at 10%. The warmed ground
  under a hovered door, link, nav label or category row. This is how blue touches
  a large area without touching text contrast.

### Tertiary

- **Alert Red** (`{colors.alert}`): Safety notices and form errors only. It sets
  error text, the 2px error border on a field, the safety notice frame and the
  outstanding-content markers. 6.31:1 on paper.
- **Alert Wash** (`{colors.alert-wash}`): Alert at 5.5%, the ground of a safety
  notice and of an outstanding-content marker.

### Named Rules

**The Fill, Rule or Underline Rule.** `--action` (#1D9FDA) measures **2.89:1**
against `--paper` (#FBFBF9). That fails the 4.5:1 body-text floor *and* the 3:1
floor for large text and UI components. It therefore never sets text on paper and
never carries meaning as a stroke a user must read. It is only ever a fill behind
ink text, a rule, an underline, or a browser-surface tint. Ink on a group-blue
fill is **4.95:1** and passes, which is how the primary button legally carries the
group colour. This is the single most important constraint in the stylesheet and
the one trap a future editor will walk into.

**The Interactive-Only Rule.** Blue means "you can do something here," so it
never decorates. It does not rule off a heading, does not mark a list item, and
does not fill a progress segment. Audit test: point at any blue pixel and name
the thing a finger, a caret or a keyboard can land on. If you cannot, it is a
defect.

**The Monotone State Ramp Rule.** Non-interactive state is carried by weight, not
hue: `--hair` is pending, `--slate` is done, `--ink` is current. The multi-step
progress track is built this way so the eye lands on where you are without
spending the accent. Under `forced-colors: active` the ramp stays separable —
current becomes `Highlight`, done becomes `GrayText`.

**The Five Values Rule.** Five hues: ink, action, paper, slate, alert. Every
other colour in the file is an alpha of one of those five. Introducing a sixth
hue — including a grey that is not ink-derived — is out of system.

## Typography

**Display Font:** Public Sans (self-hosted variable, weight 400–700)
**Body Font:** Public Sans — the same face. There is no second family.
**Fallback stack:** `ui-sans-serif, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif`

**Character:** A humanist grotesque with a large x-height and unambiguous
letterforms, chosen for a stressed, often older, often ESL reader on a phone. It
is plain rather than expressive, and it does all the work here: there is no
display serif, no second face for labels, and no imagery to lean on. Two subset
woff2 files — latin always, latin-ext only when accented characters appear —
with `font-display: swap`, because a slow Vanuatu connection must never blank the
text.

### Hierarchy

- **Display** (600, `clamp(2.375rem, 1.55rem + 3.3vw, 3.5rem)` = 38–56px, 1.08,
  −0.028em): The door label only. The next action is the largest thing on the
  page, larger than the question that asks it.
- **Headline** (600–700, `clamp(1.875rem, 1.40rem + 1.9vw, 2.5rem)` = 30–40px,
  1.02–1.14): Page `h1`, the homepage entry question (700, −0.035em), the step
  numerals, and the quotable enquiry reference on the receipt.
- **Title** (600, `clamp(1.4375rem, 1.20rem + .95vw, 1.75rem)` = 23–28px, 1.14,
  −0.015em): `h2`, and the form fieldset legend.
- **Subtitle** (600, 20px, −0.01em): `h3`, the lede line, category names, the
  masthead wordmark and the phone number, the entry sub-line.
- **Body** (400, 18px, 1.6, no tracking): All running copy, set to a 65ch
  measure. The lede narrows to 48ch and headings to 34ch.
- **Label** (600, 16px, 0): Buttons, nav labels, field labels, choice rows, door
  clause lines, `h4` at 700.
- **Small** (400, 14px): Record terms, hints, error text (600), the standing
  disclaimer, footer body.
- **Micro** (400, 13px): The wordmark descriptor, the masthead hours, the
  progress label, the footer margin's notice reference, and marker labels.

### Named Rules

**The One Face Rule.** One family, eight sizes, weights 400/600/700. Hierarchy is
size and weight and nothing else. No second family, no italic display, no
letter-spaced small caps.

**The Tabular Numeral Rule.** Every numeral a reader might compare or read back
over the phone is tabular: `font-variant-numeric: tabular-nums` plus
`font-feature-settings: 'tnum' 1` on `.num`, `table`, `dl`, `.record`, `input`
and `output`. Licence numbers, phone numbers, references, dates and step numbers
all align in a column.

**The Single Base Rule.** `html` is `font-size: 100%` so 1rem stays whatever the
reader set. The 18px base is applied **once**, on `body`. Setting a percentage on
both multiplies them and inflates the whole scale — which is exactly what this
build did before it was corrected. Never set a root font percentage.

**The Space-Above Rule.** Every heading has more space above it than below it.
The adjacency rule `h1 + *, h2 + *, h3 + *, h4 + *` must stay **after** the
element margins in source order: `h2 + *` ties `p` on specificity, so source
order decides, and `p`'s `margin` shorthand would otherwise zero the top margin.
Moving it earlier silently collapses body copy against its heading.

**The No Body Tracking Rule.** Tracking is zero at body and label sizes and
tightens only as size grows (−0.01em at 20px through −0.035em at the entry
question). Negative tracking on `body` inherits as a fixed pixel value into every
small label and crushes it.

## Layout

Single column, left-aligned, mobile-first. The desktop layout is the mobile
layout with more margin — there is no separate desktop composition.

The shell (`.shell`) is `max-width: 76rem`, centred, with inline padding of
`clamp(1rem, 4vw, 3rem)`. Prose inside it is capped at a 65ch measure
(`--measure`), headings at 34ch (`--measure-tight`), forms and receipts at 34rem,
the medicine lookup at 38rem, the providers gate content at 40rem. `body` is a
column flex container with `min-height: 100vh` and `.main { flex: 1 0 auto }`, so
the footer sits at the bottom of a short page.

Spacing is a nine-step scale from 0.25rem to 6rem (`--s-1`…`--s-9`), applied
through two stack utilities (`.stack` at 1rem, `.stack--wide` at 2rem) rather
than per-element margins. Section rhythm is fixed: `margin-top: 4rem`,
`border-top: 2px solid ink`, `padding-top: 2rem`. The footer opens with a 2px ink
rule and 6rem above it.

Breakpoints are four em-based minimums, each doing one job:

- **34em** — the medicine lookup row becomes field-plus-button.
- **40em** — record rows become term/value two-column.
- **48em** — the parallel column splits into two columns when open.
- **52em** — the two doors split side by side; the footer becomes 1.6fr/1fr.
- **60em** — the masthead becomes three columns (brand / reach / CTA), the nav
  becomes a horizontal row divided by vertical hairlines, and the menu toggle
  disappears.

Print is a first-class target because this audience prints pages to take to a
doctor: the palette flips to black on white, navigation and interactive chrome
are hidden, sections avoid breaking, and prose links print their href.

### Named Rules

**The Stacked Plates Rule.** A section opens with a 2px ink rule and deep space
(4rem) above its heading. The rule and the air are the container; nothing is
wrapped in a box to group it.

**The Hairline Row Rule.** Where this category would use a grid of identical
cards, this system uses hairline-divided rows: `.chunks` for category lists,
`.record` for fact pairs, `.clauses` and `.steps` for structured text. A card is
never the answer to "these things are peers."

**The Notice Reference Rule.** Every page passes a `$page['ref']` that renders in
the footer margin (`.footer__margin`, 13px slate, right-aligned above 52em,
separated by a hairline) alongside the content-reviewed date. It is the
letterpress stamp made functional: a reference a caller can quote.

## Elevation & Depth

**This system has no elevation.** There is no `box-shadow` used as elevation
anywhere in the stylesheet, no blur, no offset, no `filter: drop-shadow`, no
gradient and no tonal surface layering. Depth is entirely structural: rule
weight (1px hairline versus 2px ink versus the 6px top edge of a receipt), deep
vertical space between plates, and the 65ch measure holding text off the margin.
Where the category would signal "this is a raised object," this system signals
"this is a bounded notice" with an ink frame and no fill.

Two `box-shadow` declarations do exist, and both are zero-blur, zero-offset
**inset** marks used as a rule that occupies no layout space:
`inset 0 -4px 0 var(--action)` under the current nav label, and
`inset 4px 0 0 var(--action)` beside the current dropdown item. They are chosen
over a border precisely so that moving between pages shifts nothing. They are
rules, not shadows.

### Named Rules

**The No-Shadow Rule.** No shadow, ever — not ambient, not hover, not focus, not
a hairline fake. If a surface needs to separate from another surface, it gets a
rule or it gets space. `box-shadow` is permitted only as a zero-blur inset
current-state mark, and only where a border would shift layout.

**The Nothing Moves Rule.** State is never conveyed by motion or displacement.
The door's accent bar lives in a permanently 6px-tall slot and grows into it via
`transform: scaleY()`; the current-page nav mark is inset; the focus ring is an
outline with a 3px offset; the header CTA is never removed, only restyled.
Nothing translates, lifts or reflows on hover, focus or navigation. Under
`prefers-reduced-motion: reduce`, all transitions and animations go to 0s.

## Shapes

Rectilinear throughout, with the corner strategy asserted globally rather than
per-component: `* { border-radius: 0 }`. Radius is 0 on buttons, inputs, frames,
notices, panels and markers — there is exactly one `rounded` token and its value
is zero.

The form language is the ruled sheet. Strokes come in a small fixed vocabulary:

- **Hairline** (`1px solid var(--slate)`) — divides peers.
- **Ink rule** (`2px solid var(--ink)`) — opens a section, frames a notice, forms
  the door frame, bounds a button, bounds a dropdown panel.
- **Heavy edge** (`6px`) — the receipt's top border and the door's accent slot.
  The only weights above 2px in the system, both reserved for "this is the
  outcome" and "this is the target."
- **Field stroke** (`1px solid var(--hair-strong)`, thickening to 2px ink on
  focus with padding compensated by 1px so nothing reflows).
- **Dashed 2px** — used *only* by the outstanding-content markers
  (`.todo-block`, `.photo-needed__frame`). Dashed is the system's signal for
  "this is not finished," never a decorative border.

Icons are four authored 16×16 SVGs (chevron, alert, check, columns) at
`stroke-width: 1.75`, `stroke-linecap: square`, `stroke-linejoin: miter`,
`fill: none`, `currentColor`, sized in `em` — square caps and mitre joins so they
sit with the rules rather than against them. The favicon is the two doors
abstracted: one ink frame, one hairline divide, one blue bar.

### Named Rules

**The Zero Radius Rule.** Nothing on this site is rounded, and the assertion is
global. A radius on any new component is a defect, not a variant.

**The Frame-Not-Card Rule.** A frame bounds content that must not be missed (the
notice, the two doors, the receipt, the lookup, a dropdown panel). It has an ink
border, no fill, no shadow, and no sibling that looks like it. Two frames of
equal weight side by side would be cards; the two doors are therefore **one**
frame divided by a hairline.

**The Square Stroke Rule.** Every authored stroke — icon, rule, marker — uses
square caps and mitre joins at one weight per family. No rounded terminals.

## Components

### Buttons

- **Shape:** Sharp rectangle (0 radius), 2px ink border, minimum height 3rem
  (48px), padding 0.75rem 1.5rem, weight 600, `inline-flex` centred with a
  0.5rem gap for an optional icon.
- **Primary** (`.btn--primary`): Group-blue fill with ink text — the only place
  `--action` fills an area carrying text (4.95:1). Hover and active invert to an
  ink fill with paper text.
- **Secondary / outline** (`.btn--secondary`): Transparent ground, 2px ink
  border, ink text. Same invert on hover and active. This is what a body-level
  button pointing at the header's action becomes.
- **Ghost** (`.btn--ghost`): 38%-ink border at weight 400, for genuinely
  tertiary controls. Hover firms the border to ink and adds the ink wash.
- **Disabled:** 38%-ink border, slate text, ink-wash ground, `not-allowed`.
- **Focus:** The site-wide ring — `2px solid var(--ink)` at `outline-offset: 3px`.
  Never removed; `:focus { outline: none }` exists only to hand off to
  `:focus-visible`.
- **Grouping:** `.actions` lays buttons out with a 0.75rem (12px) gap, above the
  8px minimum separation between targets.

### Cards / Containers

There are no cards. The container vocabulary is:

- **Notice** (`.notice`): The site's one boxed element, reserved for content a
  reader must not miss. 2px ink frame, no fill, no shadow, 1.5rem padding, 65ch
  cap, a 700/16px head. The safety variant swaps the frame to alert and adds the
  alert wash at 5.5%.
- **Record** (`.record`): A definition list of fact pairs as hairline rows. Slate
  14px term, ink value, single column below 40em and `minmax(9rem, 15rem) 1fr`
  above it. A record's primary value is a 48px-tall standalone target; links
  inside `.record__sub` stay inline and keep the WCAG 2.5.8 inline exemption.
- **Chunk rows** (`.chunks`): The card-replacement. An ink rule on top, hairline
  between rows, 1.5rem vertical padding, a 20px/600 name and a slate 16px blurb
  capped at 52ch. Hover warms the whole row with the blue wash. A non-linking
  variant renders the same row as a `div` where nothing may imply a stock claim.
- **Internal padding:** frames use 1.5rem–2rem (`--s-5`/`--s-6`); rows use
  vertical padding only and never inset their text from the page's ink edge.

### Inputs / Fields

- **Style:** Full-width, minimum 48px tall, ink text on paper, `1px solid`
  38%-ink stroke, 0 radius, 0.75rem padding, 18px type. Textareas are 7rem
  minimum and vertically resizable only. The field is achromatic: no blue enters
  the text field.
- **Hover:** stroke firms to slate.
- **Focus:** stroke goes to 2px ink with padding reduced by 1px, so the field
  does not grow. Plus the site focus ring.
- **Label:** Always present, always visible, 600/16px, above the field.
  Placeholders are slate at full opacity and are never labels. An optional field
  says so in a slate 400 span.
- **Error:** 2px alert stroke (again padding-compensated), plus a 14px/600 alert
  message below the field carrying the alert icon and describing the fix. Colour
  is never the only cue. A failed submit also renders `.errors`: a 2px alert box
  on the alert wash listing each problem as a link to its field.
- **Choices:** Radios and checkboxes render as full-width rows with a 1px stroke
  so the whole row is the target; checked firms to 2px ink.
- **Progress** (`.progress`): 6px segments with a 0.25rem gap, coloured by the
  monotone state ramp — pending `--hair`, done `--slate`, current `--ink`.

### Navigation

- **Masthead** (`.masthead`): Border-bottom 2px ink and the only element that
  spans the viewport. Below 60em it is a two-row grid (brand / toggle, then
  reach); at 60em it is `auto 1fr auto` — brand left, phone and hours centred,
  CTA right.
- **Wordmark:** 20px/700 name with a 13px slate descriptor beneath, 48px minimum
  height, underlined in group blue on hover.
- **Call now** (`.callnow`): Label 13px slate over a 20px/700 tabular number,
  with a 2px group-blue bottom rule. Hover warms with the blue wash. Its position
  is load-bearing (see the One Filled Rectangle Rule).
- **Nav row** (`.nav`): Hairline on top; six labels, 16px/600, 48px minimum
  height, 0.75rem/1rem padding. Vertical stack below 60em with a hairline under
  each item; horizontal above 60em with a hairline between items and the first
  item pulled left by its own padding so its text aligns to the wordmark. Hover
  is the blue wash; current is the inset 4px group-blue mark. Menu toggle is 2px
  ink bordered, inverts to an ink fill when expanded, and is hidden entirely with
  JS off (where the nav is simply always open).
- **Dropdown panel** (`.nav__panel`): Paper ground, 2px ink frame, `min-width:
  17rem`, hairline between items, at most five children. Hover-and-focus-within
  operated without JS; click-operated with JS, so a tremor cannot close a menu
  someone is reading. Current child is 700 with a 4px inset group-blue mark.
- **Footer:** 2px ink rule on top, 6rem above, 14px slate. Two columns above
  52em. Section heads are 13px/700 ink over a hairline. Links are 48px-tall rows
  underlined in 38% ink, going ink with a group-blue underline on hover. No icons.

### The Two Doors (signature)

The one place boldness is spent. `.doors` is a **single** 2px ink frame; inside
it, two `.door` panels of equal width divided by one hairline (a top hairline
when stacked below 52em, a left hairline when side by side above it). Each panel
is 17rem tall stacked and 20.25rem tall side by side, padded 3rem/2rem with no
bottom padding, and the whole panel is the anchor.

Inside a door: a display-size label (38–56px, 600, −0.028em, 16ch, balanced), then
three hairline-divided clause lines in 16px slate, then `.door__rule` pushed to
the bottom by `margin-top: auto` and bled to the panel edges with a negative
inline margin — it is the door's own rule, not a bar floating inside it. That
slot is permanently 6px tall: a 2px slate line sits in it at rest, and a 6px
group-blue fill grows into it from the bottom via `scaleY` on hover, active and
focus-visible, while the panel ground warms to the blue wash. Nothing moves.
Focus uses `outline-offset: -6px` so the ring reads inside the frame.

### The Parallel Column (signature)

The memorable moment, on ten patient-facing routes. A 2px ink rule opens the
block; a bar carries a 16px/700 heading (different per page, never templated) and
a 1px-stroked toggle carrying the columns icon. Open, the English and Bislama
columns sit side by side above 48em, divided by a left hairline with 2rem of
padding; below that, stacked and divided by a top hairline. The alternate column
is `lang="bi"` with its own heading marked `lang="en"`. With JS off the column is
simply shown; the English never depends on script. Untranslated lines render the
outstanding-content marker rather than fabricated Bislama.

### Plates (photography)

The world is a printed notice, and a printed notice does not run full-colour
photographs beside its ink. It prints **plates**: framed, captioned, struck in
the document's own ink. That is the whole mechanism by which licensed stock
photography is allowed into this system without turning the page into a
brochure.

`.plate` is a `<figure>`. Inside it, `.plate__frame` carries the 2px ink border,
`isolation: isolate`, `overflow: hidden` and an `aspect-ratio` from
`--plate-ar` (default `4 / 3`; `--plate-pos` sets `object-position`). The image
fills it with `object-fit: cover`.

The duotone is two blend passes, done in CSS rather than baked into the files:

1. `.plate__img` is filtered `grayscale(1) sepia(.42) hue-rotate(168deg)
   saturate(1.45) contrast(1.06)` — desaturate, then push the surviving tone cold
   so midtones sit with the ink instead of going newsprint grey — and composited
   `mix-blend-mode: multiply`, which drops every highlight to `--paper`.
2. `.plate__frame::after` is a `--ink` layer at `mix-blend-mode: lighten`, which
   lifts every shadow to `--ink`.

Pure black therefore prints as `#0F2A3D` and pure white as `#FBFBF9`. No
photograph can introduce a sixth hue, because no photograph keeps its own hues.

`@supports not (mix-blend-mode: lighten)` falls back to a plain toned
photograph inside the same ink frame; `forced-colors: active` and `print` both
drop the overlay and the filter entirely.

The caption is `.plate__cap`: a hairline above, 13px slate, with an optional
right-hand `.plate__ref` in tabular figures — sentence case, zero tracking, the
same object as the footer margin's "Notice GV-HOME". Named ratios are
`.plate--tall` (3/4), `--square`, `--wide` (16/9), `--strip` (21/9).

**Source files are greyscale on disk.** Every plate is duotoned, so the colour
channels are bytes no reader ever sees; storing luminance only is what keeps the
homepage at 157KB with two photographs on it. This is also why there is no
partial-colour plate variant — one cannot exist against these files.

### Split and Band

`.split` is the answer to the empty right column. Body copy is capped at 65
characters, which on a wide screen leaves the right third of every section
blank; a plate fills it. Above 60em it is `minmax(0,1fr) minmax(0,.68fr)` with a
4rem gap (`.split--even` for equal columns, `.split--flip` to lead with the
plate while source order still puts the words first). Below 60em it stacks.

The content keeps normal flow inside `.split__body`. That is deliberate and
load-bearing: the vertical rhythm of this site is built out of **collapsing**
margins between headings and paragraphs, and making the section itself a grid
would silently stop them collapsing and add them together instead.

`.band` is the only element on the site that inverts. It spans the viewport
between two 2px ink rules: an `--ink` panel carrying one short statement, and a
plate beside it. Text sits on the ink and never on the photograph, so contrast
is 14.28:1 by construction rather than by luck — no scrim, no gradient, no
guessing whether a light patch will land under a word. Above 76rem the panel's
left padding is calculated to hold the same left edge as the `.shell` above it.
A band on every page would just be a hero; it is used twice.

### Channels

Contact details are things a reader acts on, not facts they read. `.channels` is
a hairline list where the whole row is the target — `1.5rem` icon column, name,
value, sub-line — and clears the 48px minimum. It replaced a `<dl>` on `/contact`
where the phone number had been a 20px-tall link inside a definition list.

### Outstanding-Content Markers

A real shipped component, deliberately conspicuous so nothing launches unseen.
Inline `.todo` is an alert-wash chip with a 2px alert bottom rule, a 13px/700
alert label and an ink key. `.todo-block` is a 2px **dashed** alert box on the
alert wash for copy a lawyer must write. `.photo-needed__frame` is a 2px dashed
38%-ink box on the ink wash naming its subject at 20px/600. Dashed strokes appear
nowhere else in the system.

### Receipt

The enquiry confirmation. 2px ink frame with the top border raised to 6px, 2rem
padding, 34rem cap, and the quotable reference set at headline size, 700, +0.02em,
tabular, over a hairline. It is the outcome of the site's only conversion and is
weighted accordingly.

### Named Rules

**The One Filled Rectangle Rule.** Exactly one filled rectangle per page, and it
is enforced centrally in `includes/header.php`, never page by page. The header
"Ask About a Medicine" button is the site's default primary action and renders
filled. A page that owns a more specific primary action sets
`$page['own_primary'] = true` and the header button steps down to the outline
variant so it does not compete. On `/enquire` it renders outline with
`aria-current="page"` and is **never removed** — `.masthead__cta` occupies a
content-sized grid column, and dropping the element collapses that column and
shifts the phone number sideways. The phone number is the one thing on this site
that must never move between pages.

**The 48px Target Rule.** `--target: 3rem` is the minimum height and width of
every interactive element, with at least 8px between targets (the actions row
uses 12px). This is 48px rather than the 44px standard because
chemotherapy-induced peripheral neuropathy makes precise tapping genuinely hard
for this audience. It is a product fact, not a preference.

**The Browser Surfaces Rule.** The parts of the page not drawn by hand still
belong to the design: `::selection` (group blue behind ink), `caret-color`,
`accent-color`, both scrollbar syntaxes (`scrollbar-color` and the
`::-webkit-scrollbar` family, slate thumb on ink-wash track),
`text-underline-offset`, tabular figures, print, and `forced-colors: active`. A
new surface inherits these; do not re-theme them locally.

## Do's and Don'ts

### Do:

- **Do** use `--action` (#1D9FDA) only as a fill behind ink text, a rule, an
  underline, or a browser-surface tint. Ink on an action fill is 4.95:1; action
  on paper is 2.89:1 and fails every threshold.
- **Do** reserve `--action` for things a finger, caret or keyboard can land on.
  Carry non-interactive state on the monotone ramp: `--hair` pending,
  `--slate` done, `--ink` current.
- **Do** open a section with `border-top: var(--rule-ink)` (2px ink), 4rem above
  and 2rem below, and let the rule plus the space be the container.
- **Do** divide peers with `var(--rule-hair)` (1px `--slate`) — an actual slate
  hairline, not a tint of ink.
- **Do** keep the header CTA element present on every page, restyling it to the
  outline variant rather than removing it, so the phone number never moves.
- **Do** hold every interactive element to a 48px minimum with 8px clearance.
- **Do** apply the 18px base once, on `body`, and leave `html` at
  `font-size: 100%`.
- **Do** keep the heading-adjacency rule (`h2 + *`) after the element margins in
  source order.
- **Do** set tabular figures on any numeral a reader compares or reads aloud.
- **Do** reserve the slot before you fill it: a state change grows into space
  that already existed.
- **Do** author new icons as 16×16 SVGs at stroke-width 1.75 with square caps and
  mitre joins, keeping geometry inside 1–15 so the stroke never clips.
- **Do** put a new photograph through `.plate`, give it a caption that says what
  is in it, and register it in `data/images.php` with its width, height and
  source. Dimensions on the `<img>` are what stop the plate reflowing on load.
- **Do** store photographic sources greyscale. The duotone discards colour
  anyway, and the bytes are the difference between a 157KB page and a 300KB one.

### Don't:

- **Don't** set text, or any stroke a user must read, in `--action`.
- **Don't** introduce a sixth hue, including a neutral grey that is not derived
  from `--ink`.
- **Don't** add a radius to anything. `* { border-radius: 0 }` is the system.
- **Don't** add a shadow, blur, offset, gradient, glow or `drop-shadow`. The only
  permitted `box-shadow` is a zero-blur inset current-state mark used where a
  border would shift layout.
- **Don't** build a row of same-size bordered panels side by side. Peers get one
  frame divided by a hairline, or they get hairline rows.
- **Don't** reach for a card. `.chunks`, `.record`, `.clauses` and `.steps`
  already cover every "these are peers" case.
- **Don't** put more than one filled rectangle on a page, and don't decide that
  page by page — use `$page['own_primary']`.
- **Don't** add an all-caps eyebrow, kicker or overline above a heading. There is
  no `text-transform: uppercase` anywhere in this stylesheet, and the only
  `text-transform` declaration is an explicit `none`.
- **Don't** use an emoji, a Unicode glyph or an icon font in place of an icon.
  The set is 27 authored SVGs in `includes/icons.php` plus one house mark, and
  it is a closed set: add to that file or go without.
- **Don't** give an icon `--action`. Icons are `--ink` or `--slate`; blue fills
  interactive targets only, and an icon is not a target.
- **Don't** add a second typeface, a display serif, or a weight outside 400–700.
- **Don't** use a dashed border for anything except an outstanding-content
  marker; dashed means "not finished."
- **Don't** move anything on hover, focus or navigation — no translate, no lift,
  no reflow, no entrance animation.
- **Don't** let JavaScript gate content, layout or navigation; every pattern here
  reads and operates with the script blocked.
- **Don't** ship stock photography, or any raster at all, without provenance.
  Every photograph is recorded in `data/images.php` with the source id it came
  from, and the footer carries a standing line saying the photographs are
  licensed stock and are not our premises or our staff.
- **Don't** put a stock photograph where it would read as a claim about this
  business — beside the real street address, or beside a named pharmacist. A
  photograph of a person is never captioned, credited or positioned as staff.
- **Don't** drop a photograph in untoned. A plate that skips the duotone is a
  sixth, seventh and eighth hue arriving at once.
