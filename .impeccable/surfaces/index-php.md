---
version: 1
slug: "index-php"
primary_target: "index.php"
related_targets: ["medicines","patients","providers","about","contact","enquire"]
---

# Surface brief — Getmeds Vanuatu site

## Scope

The whole site, ~31 pages. Visitor mode: **Operate**. Primary surface: `index.php`
(the two-door split). The mode holds across the site: patients, prescribers and buyers
arrive to complete a task, not to be persuaded.

## Audience, job, action

Three ranked audiences (PRODUCT.md owns the detail). Audience 1 — patients and family,
stressed, often older, often ESL, on a phone on metered mobile data — is served before
the other two in every conflict. The only actions on this site are: lodge an enquiry a
pharmacist can act on, or call `+678 528 2543`.

## Constraints specific to this build

- Sitemap and nav labels are fixed by the brief and are not to be reworded.
- No formulary. Medicine pages carry category structure and search only, no drug names.
- Four values stay as visible `[[PLACEHOLDER]]` markers: legal entity, pharmacy licence
  number, pharmacist name, pharmacist registration number.
- `/providers` ships behind a `noindex` tag and one interstitial include.
- 500KB per page, LCP < 2.5s on 3G, zero third-party requests.

## Direction contract

**THESIS.** This site is an official notice board, not a marketing site: the graphic
tradition of a Vanuatu ministry gazette, where ink rules and clause structure carry every
hierarchy and the reader's next action is always the largest thing on the page. It refuses
the arrangement this category always ships — a hero band over a three-up row of
same-size rounded cards with icons, soft grey shadows and a blue gradient — and it refuses
that arrangement's predictable opposite, the pastel charity look. Cards are not a container
here; a rule is.

**OWN-WORLD.** Ink `#0F2A3D` on paper `#FBFBF9`; `--slate #54687A` for every rule and all
secondary text; `--action #1D9FDA` touching nothing that is not interactive; `--alert
#B3261E` for safety and errors only. Five values, never a sixth. Rule weights are tokens,
not ad-hoc borders: 1px slate hairline divides peers, 2px ink rule opens a section. Radius
is 0 everywhere. No shadow anywhere. One humanist grotesque (Public Sans) at eight real
sizes, self-hosted, weight and size the only hierarchy devices. Tabular figures on every
numeral. Rows divided by hairlines where the category would use cards. Every page carries a
notice reference in its footer margin. Recognizable with all content removed by the rules
alone: a heavy ink masthead rule, deep space above each 2px section rule, and a single
achromatic text field with blue appearing only where a finger or caret can land.

**STORY.** A frightened person understands within one viewport that this is a licensed
pharmacy in Port Vila, that this site will route them rather than sell to them, and that
two things are possible: follow one of two doors, or call a pharmacist now. A prescriber
understands the same page carries a professional section. Both believe the supply is real
because the page shows licence, cold chain and prescription requirement as plain facts
rather than as claims. Both leave having lodged an enquiry with a quotable reference, or
having dialled.

**FIRST VIEWPORT.** Compact masthead: wordmark and the descriptor line left, phone number
and hours centre, `Ask About a Medicine` as the one filled ink rectangle right. Hairline,
then the nav row of six labels. Then a 2px ink rule at full bleed. Then `Who is this for?`
at the top of the type scale (~56px desktop), one slate line beneath it. Then the two-door
split: one 2px ink frame containing two equal panels divided by a single hairline, each
~340px tall, each holding a ~40px/600 label and three lines of slate detail, the whole
panel the target. On hover and focus the panel's bottom bar thickens to 6px `--action` and
the ground warms; nothing moves. Below the frame, one quiet line offering the phone. The
primary action sits twice in the first viewport — the header CTA, and the two doors, which
the brief's Hick's Law rule 2 explicitly licenses as the entry point's one binary choice.

**FORM.** Gazette notice / official Vanuatu ministry notice. Position 5 on my ordered list
of seven grounded candidates. Seed key `b7898867`, scope direction, mode operate, kind
assigned. Raised by five named donations: colour only on edges and rules, never in the text
field (from the iridescent cloud edge, declined); section boundaries as structural mass with
deep space, so the page reads as stacked plates (from the cloud quarry, declined); eight real
sizes from one face rather than three, type doing every job with no imagery (from the
festival lineup poster, competitive); rule weights as named tokens and the letterpress stamp
translated into a functional notice reference (from the design annual's plate section,
competitive); the empty state built as the designed primary view rather than a fallback, which
the absent formulary makes literal on the medicine search (from the drum-machine step row,
declined).

**FINISH.** unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance

## Memorable moment

The parallel column. On patient pages a quiet control opens a second gazette column beside
the English, correctly marked `lang="bi"`, with no page reload and no JS dependency for the
English to be readable. Bislama is never fabricated: the mechanism ships complete with
`[[BISLAMA TRANSLATION REQUIRED]]` slots. This is the one place where the gazette's native
parallel-text tradition becomes a real accessibility feature for an ESL reader, and it is
the reason this direction beat the dispensary metaphor the brief sketched.

## Unresolved decisions

- Whether Vanuatu law restricts advertising cancer treatment to the public. Pending legal
  advice; `/providers` gate is a placeholder in one include until it resolves.
- Bislama and French translations of patient-page copy.
- Whether analytics ship, which decides whether a cookie notice is required.
- Real photography of the Port Vila premises and staff.
