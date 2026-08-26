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

**Accent** — `#D97A2E`. Solid fill ONLY on the CTA button, not repeated as a flat fill elsewhere. Roughly 5–8% of visual area max. On smaller controls (filter chips, pagination), a thin accent border/underline for the selected/active state — not a fill.

**Other rules**

- No decorative color gradients, no blue anywhere
- Headline: Barlow Condensed, weight 800, uppercase, tight tracking, 56px+ on desktop (see v7 — this is the real, pre-existing sitewide display face, not Archivo Black)
- Eyebrow / section labels: Playfair Display italic, mixed case, muted warm tone. This applies to editorial kickers sitting above a headline ("New Season," "Best Sellers") — NOT to functional UI labels like filter-group headings ("Category," "Price," "Color") or other form-section labels, which stay as plain functional text (uppercase-tracked Inter is fine for these). Applying the eyebrow treatment to a functional label reads as a bug, not a design choice.
- CTA button text: sentence case, never all-caps
- Body / secondary text: Inter, warm-toned, not cool neutral gray
- Buttons: fully rounded pill (`9999px`). This includes small clickable controls like pagination page-numbers, not just primary CTAs. Cards: `6px` radius with a hairline edge — never pill.
- Card hover: 150ms, scale to 1.03x
- Every hero needs a real photo with the product as the visible focal point
- When a change touches a component shared across pages (section headings, pagination, buttons), use a scoped rule or a separately-named view rather than editing the shared original — then explicitly verify every other page that uses it is unaffected. This is now the standard approach, not a one-off.
- Don't fabricate data that doesn't exist (hex values for free-text color names, etc.) to hit a design ideal — flag the gap instead and use what's real.

## Homepage — accepted additions (do not remove or treat as scope creep)

- The 3D product showcase ("Golden Hour Aviator," drag-to-rotate) — kept deliberately, it's the project's signature feature.
- The "19 frames" stat block and "Nothing here is a guess" section — kept deliberately.

## Process

1. Plan the signature element before writing code; self-critique for genericness.
2. Audit first — a lot of a new page may already be correct if it shares components with an already-fixed page. Confirm what's already right before building anything new.
3. Verify EVERY token with computed styles, including ones that seem obviously fine.
4. Shared components: scope, don't edit directly; verify other pages after.
5. Report what was built, what was verified with real values, what judgment calls were made and why, and flag anything uncertain.

## Known lessons so far

- **v1–v4**: photography, product-in-frame cropping, accent overuse, and light-vs-dark base all resolved — see prior history.
- **v5**: homepage rollout reverted typography and added unrequested content/features.
- **v6**: user reviewed and kept the 3D showcase and new sections; typography reversion still needed fixing.
- **v7**: cleanup pass verified eyebrows/buttons correctly but accepted the homepage's headline font as already-right without checking it against the actual tested reference. Barlow Condensed is the corrected, real spec now. Lesson: partial verification gives false confidence — check things that look right too.
- **v8**: shop/catalog page rollout. Recovered correctly from a missing skill file (paused and reloaded rather than proceeding without it). Proactively flagged two out-of-brief items (shared pagination view, non-existent color-hex data) before building rather than silently expanding scope or silently leaving them broken. Correctly distinguished functional UI labels from editorial eyebrows (see rule above) and settled pagination as pill-shaped (see rule above). Full cross-page regression check across PDP, cart, homepage, and admin/wishlist/orders, all confirmed unaffected. This is the process working as intended.
