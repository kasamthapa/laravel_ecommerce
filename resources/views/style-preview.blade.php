<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Style Preview - Luma Lens</title>

    {{--
        Fully self-contained: its own fonts, its own <style> block, no shared
        layout/app.css/vite pipeline. Nothing links to this page and it isn't
        named from anywhere else — reachable only by typing the URL.

        Signature move: the hero photo (Luma Lens's own product shot, already
        live as the homepage hero — not a new asset) is pinned to the right
        with a single-color near-black scrim fading to transparent, so the
        headline sits in real negative space carved out of the photo rather
        than floating on a flat card or a translucent box over it. That scrim
        is opacity-only on one locked color (#0A0A0A) — not a decorative
        color gradient, which stays banned per the locked tokens.
    --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Playfair+Display:ital@1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #0a0a0a;
            --bg-surface: #171717;
            --text-main: #f2f0ea;
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

        .eyebrow {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: 1.05rem;
            color: var(--accent);
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
            color: rgba(242, 240, 234, 0.8);
            max-width: 34rem;
            margin: 0 0 2rem;
        }

        /* Pill CTA — the ONLY place the amber-gold accent appears as a solid fill. */
        .btn-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 9999px;
            background: var(--accent);
            color: #0a0a0a;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.9rem 2rem;
            text-decoration: none;
            cursor: pointer;
        }

        /* Full-bleed hero: breaks out of <main>'s max-width to run edge to
           edge, with the real product photo behind it. */
        /* Height matches the homepage hero's own sizing exactly
           (h-[75vh] max-h-[44rem] min-h-[30rem]): tied to viewport HEIGHT,
           not width. A fixed rem height (the previous version's bug) stays
           put as the window gets wider, so on a wide-but-normal-height
           desktop monitor the box gets extremely short relative to its
           width — object-fit: cover then has to zoom in hard on the tall
           portrait source to fill it, and the visible vertical slice gets
           too thin to hold the glasses no matter what object-position says.
           Scaling height off the viewport keeps the box tall enough at any
           width. */
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

        /* object-position: matching the homepage's plain 50% 50% (previous
           commit) still cropped the glasses out on a real wide-but-normal-
           height monitor — confirmed by a live screenshot, and by reading
           the source photo directly (public/images/storefront/
           lightweight-eyewear.png, 864x1821): the eyebrows/glasses sit at
           roughly 32-44% down the frame, the chin around 54% — the face is
           not vertically centered in this portrait, so a dead-center crop
           starts losing the top of the glasses the moment the visible
           window gets tight (which a wide/short viewport forces regardless
           of container height). The homepage has this same latent bias, it
           just isn't usually visible there. 40% anchors the window on the
           eyes/glasses with margin above and below at every width tested,
           at the cost of the chin/shoulders getting tighter on very wide
           screens instead — the correct trade-off per the skill's
           requirement that the glasses specifically stay in frame. */
        .hero-photo {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: 50% 40%;
        }

        /* Legibility scrim only — one color (page background) at varying
           opacity, not a decorative gradient. Horizontal on desktop so the
           text sits in carved-out negative space with the photo's subject
           still visible; an added vertical pass on mobile, where the photo
           crops shorter and the text stacks lower over more of it. */
        .hero-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(10, 10, 10, 0.97) 0%, rgba(10, 10, 10, 0.86) 38%, rgba(10, 10, 10, 0.35) 68%, rgba(10, 10, 10, 0.1) 100%),
                linear-gradient(0deg, rgba(10, 10, 10, 0.75) 0%, rgba(10, 10, 10, 0) 55%);
        }

        @media (min-width: 768px) {
            .hero-scrim {
                background: linear-gradient(90deg, rgba(10, 10, 10, 0.96) 0%, rgba(10, 10, 10, 0.82) 42%, rgba(10, 10, 10, 0.2) 72%, rgba(10, 10, 10, 0.02) 100%);
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

        /* Cards/tiles: small corner radius only — deliberately NOT pill-shaped,
           so they read distinctly from the CTA buttons. A hairline border
           gives them a firmer edge now that the hero above carries more
           visual weight, so the #171717-vs-#0A0A0A step still reads clearly
           even without a photo of their own competing for attention. */
        .card,
        .trust-tile {
            background: var(--bg-surface);
            border-radius: 6px;
            border: 1px solid rgba(242, 240, 234, 0.08);
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
            color: rgba(242, 240, 234, 0.7);
            margin: 0 0 1.1rem;
        }

        .trust-tile {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            padding: 1.75rem;
            max-width: 26rem;
        }

        .trust-icon {
            color: var(--accent);
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
            color: rgba(242, 240, 234, 0.7);
            margin: 0;
        }

        .section-label {
            font-family: 'Inter', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: rgba(242, 240, 234, 0.45);
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
