---
name: luma-lens-design
description: Use for any visual, UI, or frontend work on Luma Lens — new pages, redesigns, styling changes, or component work. Contains the locked design tokens and the quality bar every visual change must clear before being reported as done.
---

# Luma Lens Design

## Locked tokens — do not change without explicit sign-off in the current session

**Colors**

- Page background: `#0A0A0A`
- Card/section surface: `#171717`
- Text on dark: `#F2F0EA`
- Accent: `#D97A2E` (warm amber-gold) — ONLY on CTA fills and small active-state details, roughly 5–8% of visual area max, never a large fill or background
- No decorative color gradients anywhere (a black-to-transparent darkening fade behind photo text, for legibility, is fine — that's functional, not decorative)
- No blue anywhere

**Type**

- Headline: Archivo Black or Anton, bold, all-caps, tight tracking, 56px+ on desktop
- Eyebrow / section labels: Playfair Display italic — short labels only, never the headline or body copy
- Body: Inter

**Shape**

- Buttons / CTAs: fully rounded pill (`border-radius: 9999px`)
- Cards / containers: small radius only (`border-radius: 6px`) — never pill. Buttons and cards are deliberately different shapes; don't let one drift toward the other.

**Motion**

- Card hover: 150ms transition, scale to 1.03x — subtle, not dramatic

## The quality bar

This is a near-black-plus-single-accent palette, which is one of the most common defaults in AI-generated design right now — the color choice alone won't make anything look distinctive. Distinctiveness has to come from execution:

- **Every hero needs a real photo**, full-bleed or as a clear dominant panel, with a dark overlay/fade toward the page background so the amber and off-white type stay legible near it.
- **Product photography must actually show the product.** A hero photo that's technically present but cropped so the glasses/eyes aren't in frame serves the brief no better than no photo at all. Check the focal point, not just that an image tag exists.
- **Pick one signature move per screen and commit to it** — usually how the photo, the amber accent, and the type interact — then keep everything else quiet. Restraint everywhere except that one deliberate choice is what makes it read.
- **Use real content, not placeholders** — a real product from the catalog, real copy, photography already live on the site.
- **Quality floor on every ship:** responsive down to mobile width, visible keyboard focus states, motion respects `prefers-reduced-motion`.

## Process

1. Before writing code, write a short plan: what's the one signature element here, and where does the required photo go, and how is it cropped/positioned so the actual product stays in frame?
2. Self-critique the plan: would this look the same for any eyewear brand, or does it use Luma's own product and photography in a way that's actually recognizable as eyewear? If it reads generic, or if the product itself isn't visible, revise before building.
3. Build against the locked tokens above.
4. Screenshot the result and check it against both the locked tokens and the quality bar.
5. Report back what was built, what was verified (real computed values, not "looks right"), and flag anything that couldn't be self-verified.

## Known lessons so far

- **v1** matched every locked token exactly but shipped with no photography at all — a flat colored card, text and a button, nothing else. Fixed in v2.
- **v2** added real photography, but the crop/position hid the actual product — only hair and forehead were in frame, no glasses, no eyes. Technical presence of a photo isn't the same as the photo doing its job. Always confirm the product itself is the visible focal point, not just that an `<img>` exists.
