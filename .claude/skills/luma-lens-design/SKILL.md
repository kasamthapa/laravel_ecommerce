---
name: luma-lens-design
description: Use for any visual, UI, or frontend work on Luma Lens — new pages, redesigns, styling changes, or component work. Contains the chosen design tokens and the quality bar every visual change must clear before being reported as done.
---

# Luma Lens Design

## Base palette — LIGHT variant chosen

- Page background: `#F2F0EA`
- Card/section surface: `#FCFBF8`
- Text: `#171717`

The dark variant (`/style-preview`) is parked, not deleted — kept as a working reference.

## Shared rules

**Accent** — `#D97A2E`. Solid fill ONLY on the CTA button, not repeated as a flat fill elsewhere. Roughly 5–8% of visual area max.

**Other rules**

- No decorative color gradients, no blue anywhere
- Headline: **Barlow Condensed**, weight 800, uppercase, tight tracking, 56px+ on desktop. (Corrected from "Archivo Black or Anton" — that was the spec used to build the isolated `/style-preview` pages, but the real site's actual heading font, used everywhere including product names, was always Barlow Condensed. Never verified against computed styles until now. Barlow Condensed is the real answer going forward; the isolated preview pages are out of date on this one point and can be left as historical reference, not corrected.)
- Eyebrow / section labels: Playfair Display italic, mixed case ("New Season," not "NEW SEASON"), muted warm tone — never tracked-out sans caps, never the accent color
- CTA button text: sentence case ("Shop the collection"), never all-caps
- Body / secondary text: Inter, warm-toned, not cool neutral gray
- Buttons: fully rounded pill (`9999px`). Cards: `6px` radius with a hairline edge — never pill.
- Card hover: 150ms, scale to 1.03x
- Every hero needs a real photo with the product as the visible focal point

## Homepage — accepted additions (do not remove or treat as scope creep)

- The 3D product showcase ("Golden Hour Aviator," drag-to-rotate) — kept deliberately, it's the project's signature feature (AR/3D try-on). Don't remove it or swap the product unless explicitly asked.
- The "19 frames" stat block and the "Nothing here is a guess" materials/QC section — kept deliberately.

These still follow the same typography rules as everything else — being "kept" only means their content and presence are settled, not their type treatment.

## Process

1. Plan the signature element and photo crop before writing code.
2. Self-critique for genericness before building.
3. Build against the tokens above. When porting an already-tested design to a real page, verify EVERY token with computed styles — including ones that seem obviously fine, like the headline font-family. Visual similarity between two different type families at heavy/condensed weights is easy to miss by eye.
4. When a shared component is used on other pages (e.g. section headings, buttons also used on the PDP), don't edit it directly — use a scoped rule, then explicitly verify the other pages weren't affected, don't just assume.
5. Report what was built, what was verified with real values, and flag anything uncertain.

## Known lessons so far

- **v1**: shipped with no photography. Fixed in v2.
- **v2**: photo cropped the product out of frame. Fixed.
- **v3**: uniform flat accent + cool secondary text read as loud/generic even with correct values.
- **v4**: light-dominated base chosen over dark once execution matched, confirming v3.
- **v5**: homepage rollout got colors right but silently reverted eyebrow labels and CTA text to old typography, and added unrequested new sections and a 3D showcase.
- **v6**: user reviewed v5's additions directly and chose to keep the 3D showcase and new sections (see "accepted additions" above); the typography reversion was unrelated and still needed fixing.
- **v7**: the eyebrow/button cleanup pass verified those elements correctly against a fresh `/style-preview-light` screenshot and correctly avoided a cross-page regression on the PDP — genuinely careful work. But it accepted the real homepage's headline font (Barlow Condensed) as already-correct without checking it against the actual tested reference, which uses Archivo Black. Checking computed values against _some_ things but not _all_ things gives false confidence. Barlow Condensed is now the corrected spec, since it's the pre-existing, sitewide display face — but the lesson is the gap in verification, not just this one value.
