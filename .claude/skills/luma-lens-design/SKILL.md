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

**Accent** — `#D97A2E`. Solid fill ONLY on the single primary CTA per page (e.g. "Add to cart" on the PDP, "Shop the collection" on the homepage). Secondary actions (wishlist, etc.) get an outline/hairline treatment instead, never a second solid-accent fill on the same page. Roughly 5–8% of visual area max. On smaller controls (filter chips, tabs, pagination), a thin accent border/underline for the selected/active state — not a fill.

**Other rules**

- No decorative color gradients, no blue anywhere
- Headline: Barlow Condensed, weight 800, uppercase, tight tracking, 56px+ on desktop
- Eyebrow / section labels: Playfair Display italic, mixed case, muted warm tone. Applies to editorial kickers above a headline only — NOT to functional UI labels (filter-group headings, breadcrumbs, form labels), which stay as plain functional text even when they sit in a spot an eyebrow visually could.
- CTA button text: sentence case, never all-caps
- Body / secondary text: Inter, warm-toned, not cool neutral gray
- Buttons: fully rounded pill (`9999px`), including small controls like pagination and tabs' active-state indicator being an underline rather than a fill. Cards: `6px` radius with a hairline edge — never pill.
- Card hover: 150ms, scale to 1.03x
- Every hero needs a real photo with the product as the visible focal point
- When a change touches a component shared across pages, scope it or give it a separately-named view rather than editing the shared original — then explicitly verify every other page/instance that uses it is unaffected.
- Don't fabricate data that doesn't exist to hit a design ideal — flag the gap and use what's real.
- **Check for token-role naming traps on every page, not just the ones with obvious visual issues.** Some CSS custom properties are named for a literal color (`--color-black`) but actually represent a role (foreground vs. background) that gets redefined per theme — meaning old utility classes like `text-black` or `bg-white` written before the theme existed can silently break (invisible text, wrong contrast) without any console error or visual crash to flag it. Also check status colors (success/error/warning) — these are frequently tuned for only one background and can fail contrast on the other. Audit every color-referencing class on a page against the actual theme, not just the elements that are part of the visual redesign itself.

## Homepage — accepted additions (do not remove or treat as scope creep)

- The 3D product showcase ("Golden Hour Aviator," drag-to-rotate) — kept deliberately, the project's signature feature.
- The "19 frames" stat block and "Nothing here is a guess" section — kept deliberately.

## Process

1. Plan the signature element before writing code; self-critique for genericness.
2. Audit first — a lot of a new page may already be correct if it shares components with an already-fixed page. Confirm what's already right before building.
3. Verify EVERY token with computed styles, including ones that seem obviously fine — and check for token-role naming traps and status-color contrast specifically (see above), even if not asked.
4. Shared components: scope, don't edit directly; verify other pages after.
5. Report what was built, what was verified with real values, what judgment calls were made and why, and flag anything uncertain.

## Known lessons so far

- **v1–v4**: photography, product-in-frame cropping, accent overuse, and light-vs-dark base resolved — see prior history.
- **v5**: homepage rollout reverted typography and added unrequested content/features.
- **v6**: user reviewed and kept the 3D showcase and new sections.
- **v7**: partial verification (colors/eyebrows checked, headline font-family not) gave false confidence. Barlow Condensed is the corrected spec.
- **v8**: shop/catalog page. Correct recovery from a missing skill file; proactive scope-question flagging (shared pagination, non-existent color data) before building; functional-label-vs-eyebrow distinction and pagination-as-pill both settled as standing rules.
- **v9**: PDP. Found and fixed two real bugs beyond the brief — a token-role naming trap that would have rendered a whole section's text invisible, and status-message colors failing AA contrast on the new background — both with real before/after contrast numbers. Caught and corrected its own false-positive verification (queried the wrong element, noticed the mismatch, redid it properly) rather than reporting a false pass. This is the bar going forward: check the underlying token system itself on every page, not just the elements directly touched by the redesign.
