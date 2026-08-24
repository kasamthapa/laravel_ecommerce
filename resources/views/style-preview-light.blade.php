<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Style Preview (Light) - Luma Lens</title>

    {{--
        Light-dominated sibling of /style-preview — same structure, same
        photo, same crop, same fonts, same content, only the background/text
        relationship flips per the skill file's Light variant tokens:
        page #F2F0EA (the dark variant's text color, reused as the base),
        card surface #FCFBF8 (barely brighter, same lift relationship as
        the dark variant just inverted), text #171717 (the dark variant's
        card-surface color, reused as text).

        Carrying forward the v3 lesson from the skill file: the same accent
        repeated as a solid-ish color on the button AND the icon AND the
        eyebrow label was a root cause of the dark version reading loud/
        generic. Here the accent appears in exactly one place — the CTA
        fill — full stop. The eyebrow label and trust icon use the warm
        muted text tone instead, not the accent.
    --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Playfair+Display:ital@1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #f2f0ea;
            --bg-surface: #fcfbf8;
            --text-main: #171717;
            --text-muted: rgba(23, 23, 23, 0.65);
            --accent: #d97a2e;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg-page);
            color: var(--text-main);
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 3px;
        }

        main {
            max-width: 72rem;
            margin: 0 auto;
            padding: 3.5rem 1.5rem 6rem;
            display: grid;
            gap: 3.5rem;
        }

        /* No accent here — the eyebrow is a "label," and per the skill's
           carried-over lesson the accent is CTA-fill only now. Warm-muted
           text keeps it quiet instead of adding a second accent moment. */
        .eyebrow {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: 1.05rem;
            color: var(--text-muted);
            margin: 0 0 0.75rem;
        }

        .headline {
            font-family: 'Archivo Black', ui-sans-serif, sans-serif;
            font-size: clamp(2.5rem, 6vw, 4.25rem);
            line-height: 0.98;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            margin: 0 0 1.25rem;
        }

        .hero-body {
            font-family: 'Inter', sans-serif;
            font-size: 1.05rem;
            line-height: 1.6;
            color: var(--text-muted);
            max-width: 34rem;
            margin: 0 0 2rem;
        }

        /* Pill CTA — the ONLY place the amber-gold accent appears at all. */
        .btn-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 9999px;
            background: var(--accent);
            color: #171717;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.9rem 2rem;
            text-decoration: none;
            cursor: pointer;
        }

        /* Same sizing strategy as /style-preview's final fix: height tied to
           viewport HEIGHT (not a fixed rem value), so the box stays tall
           enough to hold the crop at any window width. */
        .hero {
            position: relative;
            width: 100%;
            height: 75vh;
            max-height: 44rem;
            min-height: 30rem;
            display: flex;
            align-items: flex-end;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .hero {
                align-items: center;
            }
        }

        /* Same photo, same crop as /style-preview: object-position 50% 40%
           anchors the visible window on the eyes/glasses (which sit at
           roughly 32-44% down this portrait source, not image-center) with
           margin above and below, verified against the source file itself. */
        .hero-photo {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: 50% 40%;
        }

        /* Legibility scrim — one color (the page background, #F2F0EA) at
           varying opacity, not a decorative gradient, mirroring the dark
           variant's near-black scrim but toward the light base instead. */
        .hero-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(242, 240, 234, 0.97) 0%, rgba(242, 240, 234, 0.88) 38%, rgba(242, 240, 234, 0.4) 68%, rgba(242, 240, 234, 0.12) 100%),
                linear-gradient(0deg, rgba(242, 240, 234, 0.8) 0%, rgba(242, 240, 234, 0) 55%);
        }

        @media (min-width: 768px) {
            .hero-scrim {
                background: linear-gradient(90deg, rgba(242, 240, 234, 0.96) 0%, rgba(242, 240, 234, 0.85) 42%, rgba(242, 240, 234, 0.25) 72%, rgba(242, 240, 234, 0.04) 100%);
            }
        }

        .hero-content {
            position: relative;
            padding: 3rem 1.5rem;
            max-width: 34rem;
        }

        @media (min-width: 768px) {
            .hero-content {
                padding: 4rem 3rem;
            }
        }

        section.card-section,
        section.tile-section {
            padding: 0 1.5rem;
        }

        @media (min-width: 768px) {
            section.card-section,
            section.tile-section {
                padding: 0;
            }
        }

        /* Cards/tiles: small radius only, never pill. The page/card step is
           much smaller here than in the dark variant (#F2F0EA vs #FCFBF8 —
           barely a shade apart) so the hairline border is doing more of the
           definition work than it does on the dark side, not less. */
        .card,
        .trust-tile {
            background: var(--bg-surface);
            border-radius: 6px;
            border: 1px solid rgba(23, 23, 23, 0.1);
        }

        .card {
            width: 18rem;
            max-width: 100%;
            overflow: hidden;
            transition: transform 150ms ease-out;
        }

        .card:hover {
            transform: scale(1.03);
        }

        @media (prefers-reduced-motion: reduce) {
            .card {
                transition-duration: 1ms;
            }
        }

        .card-photo {
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
            display: block;
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-name {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 1.05rem;
            margin: 0 0 0.35rem;
        }

        .card-price {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            color: var(--text-muted);
            margin: 0 0 1.1rem;
        }

        .trust-tile {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            padding: 1.75rem;
            max-width: 26rem;
        }

        /* No accent — stroke icon in the muted warm text tone, same
           reasoning as the eyebrow above. */
        .trust-icon {
            color: var(--text-muted);
            flex-shrink: 0;
            margin-top: 0.15rem;
        }

        .trust-heading {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            margin: 0 0 0.3rem;
        }

        .trust-text {
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            line-height: 1.5;
            color: var(--text-muted);
            margin: 0;
        }

        .section-label {
            font-family: 'Inter', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: rgba(23, 23, 23, 0.45);
            margin: 0 0 1rem;
        }
    </style>
</head>
<body>
    <section class="hero">
        <img class="hero-photo" src="{{ asset('images/storefront/lightweight-eyewear.png') }}" alt="A person wearing Luma Lens optical frames, close crop">
        <div class="hero-scrim"></div>
        <div class="hero-content">
            <p class="eyebrow">New Season</p>
            <h1 class="headline">Built for how you actually move.</h1>
            <p class="hero-body">Optical and sun frames edited for fit, finish, and everyday wear — with lenses ready in every pair.</p>
            <a href="#" class="btn-pill" onclick="return false;">Shop the collection</a>
        </div>
    </section>

    <main>
        <section class="card-section">
            <p class="section-label">Product card</p>
            @if ($product)
                <div class="card">
                    <img class="card-photo" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    <div class="card-body">
                        <p class="card-name">{{ $product->name }}</p>
                        <p class="card-price">Rs. {{ number_format((float) $product->price) }}</p>
                        <a href="#" class="btn-pill" onclick="return false;">Quick Add</a>
                    </div>
                </div>
            @else
                <p class="hero-body">No active product found to preview.</p>
            @endif
        </section>

        <section class="tile-section">
            <p class="section-label">Trust-strip tile</p>
            <div class="trust-tile">
                <svg class="trust-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                    <circle cx="7" cy="18" r="1.6" stroke="currentColor" stroke-width="1.6" />
                    <circle cx="17.5" cy="18" r="1.6" stroke="currentColor" stroke-width="1.6" />
                </svg>
                <div>
                    <p class="trust-heading">Free shipping</p>
                    <p class="trust-text">On every order over Rs. 10,000, nationwide.</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
