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
- Headline: Archivo Black or Anton, bold, all-caps, tight tracking, 56px+ on desktop
- Eyebrow / section labels: Playfair Display italic, mixed case ("New Season," not "NEW SEASON"), muted warm tone — never tracked-out sans caps, never the accent color
- CTA button text: sentence case ("Shop the collection"), never all-caps
- Body / secondary text: Inter, warm-toned, not cool neutral gray
- Buttons: fully rounded pill (`9999px`). Cards: `6px` radius with a hairline edge — never pill.
- Card hover: 150ms, scale to 1.03x
- Every hero needs a real photo with the product as the visible focal point

## Homepage — accepted additions (do not remove or treat as scope creep)

These were added without being asked for, but the user reviewed them and chose to keep them. Don't revert or "clean up" the following on future passes — they're intentional:

- The 3D product showcase ("Golden Hour Aviator," drag-to-rotate) — this is the project's signature feature (AR/3D try-on), so surfacing it prominently on the homepage is a deliberate choice, even though the specific product shown doesn't match the palette. Don't remove it or swap the product unless explicitly asked.
- The "19 frames" stat block and the "Nothing here is a guess" materials/QC section — good, considered copy, kept deliberately.

What these DO still need: the same typography rules as the rest of the page (see below) — being "kept" doesn't mean their type treatment is exempt from the shared rules.

## Process

1. Plan the signature element and photo crop before writing code.
2. Self-critique for genericness before building.
3. Build against the tokens above. When porting an already-tested design (e.g. from an isolated preview) to a real page, copy the FULL treatment — font-family and text-transform together with color, not color alone.
4. Screenshot and verify with computed styles, not eyeballing.
5. Report what was built, what was verified, and flag anything uncertain.

## Known lessons so far

- **v1**: shipped with no photography. Fixed in v2.
- **v2**: photo cropped the product out of frame. Fixed.
- **v3**: uniform flat accent + cool secondary text read as loud/generic even with correct values.
- **v4**: light-dominated base chosen over dark once execution matched, confirming v3.
- **v5**: homepage rollout got colors right but silently reverted eyebrow labels and CTA text to old pre-redesign typography (tracked caps, all-caps) instead of the tested treatment, and added unrequested new sections and a 3D showcase.
- **v6**: user reviewed v5's additions directly — keeping the 3D showcase and the two new content sections (see "accepted additions" above), rejecting removal. The typography reversion (eyebrows, button case) is still a real bug, unrelated to that decision, and still needs fixing everywhere, including on the newly-added sections.
