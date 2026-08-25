---
name: luma-lens-design
description: Use for any visual, UI, or frontend work on Luma Lens — new pages, redesigns, styling changes, or component work. Contains the chosen design tokens and the quality bar every visual change must clear before being reported as done.
---

# Luma Lens Design

## Base palette — LIGHT variant chosen

- Page background: `#F2F0EA`
- Card/section surface: `#FCFBF8`
- Text: `#171717`

The dark variant (`/style-preview`, `#0A0A0A` / `#171717` / `#F2F0EA`) is parked, not deleted — it was properly executed once earlier lessons were applied, and stays as a working reference. Don't build new work in dark tokens unless explicitly asked.

## Shared rules

**Accent** — `#D97A2E`. Reads noticeably better against the warm cream base than it did against near-black — stick with it unless flagged otherwise.

- Solid fill ONLY on the CTA button. Not repeated as a flat fill on icons or decorative elements — those get a muted neutral or an outline/stroke treatment instead. (Small text-only touches, like an eyebrow label, are a lighter case — using the accent there is a judgment call, not a hard rule either way.)
- Roughly 5–8% of total visual area max. Never a large fill or background.

**Other rules**

- No decorative color gradients anywhere (a darkening fade behind hero text, for legibility, is fine)
- No blue anywhere
- Headline: Archivo Black or Anton, bold, all-caps, tight tracking, 56px+ on desktop
- Eyebrow / section labels: Playfair Display italic
- Body / secondary text: Inter — keep secondary or muted text warm-toned, not cool neutral gray
- Buttons / CTAs: fully rounded pill (`border-radius: 9999px`)
- Cards / containers: small radius only (`border-radius: 6px`), with a subtle hairline edge for definition — never pill
- Card hover: 150ms transition, scale to 1.03x
- Every hero needs a real photo, and the product itself (glasses) must be the visible focal point

## Process

1. Before writing code, write a short plan: signature element, and how the photo is cropped so the product stays in frame.
2. Self-critique: does this look distinctive and considered, or generic? Revise before building if generic.
3. Build against the tokens above.
4. Screenshot and verify with computed styles, not eyeballing.
5. Report back what was built, what was verified, and flag anything uncertain.

## Known lessons so far

- **v1**: matched every locked token exactly but shipped with no photography — flat colored card. Fixed in v2.
- **v2**: added photography, but the crop hid the product — only hair and forehead in frame. Fixed — the product now has to be the visible focal point.
- **v3**: even with every value technically correct, execution read as loud/generic — uniform flat accent everywhere and cool-toned secondary text were the actual causes, not the background's darkness.
- **v4**: light-dominated base compared directly against the (by-then well-executed) dark one — user chose light. Confirms v3's read: pairing and execution mattered more than which base value was picked.
