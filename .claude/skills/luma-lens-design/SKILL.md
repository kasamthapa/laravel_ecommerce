---
name: luma-lens-design
description: Use for any visual, UI, or frontend work on Luma Lens — new pages, redesigns, styling changes, or component work. Contains the design tokens (currently two candidate base palettes under evaluation) and the quality bar every visual change must clear before being reported as done.
---

# Luma Lens Design

## Base palette — under evaluation, not locked yet

Two directions are being tested in parallel, on separate isolated routes, before either becomes the site-wide choice. Compare both once they're built to the same quality bar — don't assume the dark one is "the" direction just because it exists first.

### Dark variant — `/style-preview`

- Page background: `#0A0A0A`
- Card/section surface: `#171717`
- Text: `#F2F0EA`

### Light variant — `/style-preview-light`

- Page background: `#F2F0EA` (the dark variant's text color, reused as the base)
- Card/section surface: `#FCFBF8` (barely brighter than the page background — cards lift up, same relationship as the dark variant, just inverted)
- Text: `#171717` (the dark variant's card-surface color, reused as text)

## Locked regardless of which base wins

**Accent** — `#D97A2E` for now, but still being compared against two richer/more muted candidates (Bronze `#B87C3A`, Cognac `#A85D2D`) — decide this only once it can be seen in both the dark and light context, color-in-context can shift which reads best. Whichever wins:

- Solid fill ONLY on the CTA button. Never repeated as a flat fill on icons, labels, or decorative elements — those get an outline/stroke treatment instead.
- Roughly 5–8% of total visual area max. Never a large fill or background.

**Other rules**

- No decorative color gradients anywhere (a black-to-transparent darkening fade behind hero text, for legibility, is fine — that's functional, not decorative)
- No blue anywhere
- Headline: Archivo Black or Anton, bold, all-caps, tight tracking, 56px+ on desktop
- Eyebrow / section labels: Playfair Display italic — short labels only, never the headline or body copy
- Body / secondary text: Inter — keep secondary or muted text warm-toned, not cool neutral gray, so it reads as the same material family as everything else
- Buttons / CTAs: fully rounded pill (`border-radius: 9999px`)
- Cards / containers: small radius only (`border-radius: 6px`), with a subtle hairline edge for definition (low-opacity border, not a flat, undefined color-block cut) — never pill
- Card hover: 150ms transition, scale to 1.03x — subtle, not dramatic
- Every hero needs a real photo, and the product itself (glasses) must be the visible focal point — not just present in the image somewhere

## Process

1. Before writing code, write a short plan: what's the signature element, where does the photo go and how is it cropped so the product stays in frame.
2. Self-critique: would this look the same for any eyewear brand, or is it actually distinctive and considered? If it reads generic, revise before building.
3. Build against the tokens above.
4. Screenshot and verify with computed styles, not eyeballing.
5. Report back what was built, what was verified, and flag anything uncertain.

## Known lessons so far

- **v1**: matched every locked token exactly but shipped with no photography at all — a flat colored card. Fixed in v2.
- **v2**: added real photography, but the crop hid the actual product — only hair and forehead in frame, no glasses. Fixed — the product now has to be the visible focal point, not just present in the file.
- **v3**: even with every value technically correct, the result read as loud/generic rather than premium. Root causes were the same flat accent color repeated everywhere (button, icon, label, all identical solid fills) and cool-toned secondary text clashing with an otherwise warm palette — not the darkness of the background itself. Now testing whether a light-dominated base reads better, while carrying every execution lesson above into both variants either way.
