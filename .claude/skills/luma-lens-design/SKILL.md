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

**Accent** — `#D97A2E`. Solid fill ONLY on the single primary CTA per page — never repeated across multiple elements at once, even decorative ones like a progress stepper. Secondary actions get an outline/hairline treatment instead. Roughly 5–8% of visual area max. On smaller controls (filter chips, tabs, pagination, step indicators), a thin accent border for the selected/active state — not a fill.

**Other rules**

- No decorative color gradients, no blue anywhere
- Headline: Barlow Condensed, weight 800, uppercase, tight tracking, 56px+ on desktop
- Eyebrow / section labels: Playfair Display italic, mixed case, muted warm tone. Applies to non-interactive, purely editorial kickers — not functional or navigational labels, even in a spot an eyebrow visually could sit.
- CTA button text: sentence case, never all-caps. Hand-styled buttons that bypass the established pattern (found once on checkout) should be converted to it, not left as one-offs.
- Body / secondary text: Inter, warm-toned, not cool neutral gray
- Buttons: fully rounded pill (`9999px`) for primary actions. Small functional controls can be simpler. Cards and card-like containers (including order-summary boxes, error boxes, callouts): `6px` radius with a hairline edge — never pill.
- Card hover: 150ms, scale to 1.03x
- Every hero needs a real photo with the product as the visible focal point
- Before assuming a component needs the scoped-edit technique, verify it's actually shared (read both usages) rather than just similar-looking, page-local markup — editing page-local markup directly is fine and simpler.
- Don't fabricate data that doesn't exist to hit a design ideal — flag the gap and use what's real.
- **Check for token-role naming traps and status-color contrast on every page** — but verify each instance individually rather than blanket-flagging every occurrence of a role-named variable. Some are genuinely broken (an orphaned reference with no matching pair, like PDP's invisible text); others are a deliberate, self-consistent pair (like a hover-invert using two role variables together) and are safe. When a status distinction doesn't exist yet, reuse the established `signal-good`/`signal-bad` values.
- When verifying error/validation states, trigger the real thing where possible (an actual server-side validation failure, not just inspecting markup for what an error state would look like) — this has caught real, separate error-rendering paths that a static read would have missed.

## Homepage — accepted additions (do not remove or treat as scope creep)

- The 3D product showcase ("Golden Hour Aviator," drag-to-rotate) — kept deliberately, the project's signature feature.
- The "19 frames" stat block and "Nothing here is a guess" section — kept deliberately.

## Process

1. Plan the signature element before writing code; self-critique for genericness.
2. Audit first. A large share of most pages turns out to already be structurally correct once scoped to the theme. Confirm what's already right with computed styles before building anything new.
3. Verify EVERY token with computed styles, including ones that seem obviously fine — and specifically check for token-role naming traps and status-color contrast, verifying each instance rather than assuming.
4. Shared components: confirm they're actually shared before treating them as such. If shared, scope rather than edit directly, then verify other pages after.
5. Report what was built, what was verified with real values, what judgment calls were made and why, and flag anything uncertain.

## Known lessons so far

- **v1–v4**: photography, product-in-frame cropping, accent overuse, and light-vs-dark base resolved.
- **v5–v6**: homepage rollout issues found and resolved; 3D showcase and new sections kept deliberately.
- **v7**: partial verification gave false confidence; Barlow Condensed corrected as the real headline spec.
- **v8**: shop/catalog — missing-skill recovery, proactive scope-question flagging, functional-label and pagination-shape rules settled.
- **v9**: PDP — token-role naming trap and failing status-color contrast found beyond the brief. Self-caught a false-positive verification and redid it correctly.
- **v10**: cart — several "new territory" elements were already structurally correct once scoped. Coupon success/error messaging had no distinction at all; fixed at the component-property layer, not just CSS.
- **v11**: checkout — verified the order-summary block was page-local, not the shared cart component, before deciding how to edit it. Found a progress stepper using solid accent fill on three elements simultaneously (violates the one-CTA-per-page rule) and a hand-styled CTA bypassing the established button pattern. Verified error states with a real server-side validation failure, which surfaced a second, previously-unnoticed error-rendering path. Correctly distinguished a safe, deliberate `text-black` role-swap pair from an actual token-role bug rather than flagging it on sight.
- **v12**: checkout confirmation — genuinely clean page, needed only the theme-scope extension. Confirmed no track-order link exists on this page at all (the brief's caveat about it was moot). Second self-caught false-positive verification in this arc (wrong SVG matched first, corrected before reporting) — worth trusting this pattern of self-correction, it keeps holding up.
