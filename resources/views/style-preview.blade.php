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

        main {
            max-width: 72rem;
            margin: 0 auto;
            padding: 3rem 1.5rem 6rem;
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

        section.hero {
            padding: 4rem 2.5rem;
            background: var(--bg-page);
        }

        /* Cards/tiles: small corner radius only — deliberately NOT pill-shaped,
           so they read distinctly from the CTA buttons. */
        .card,
        .trust-tile {
            background: var(--bg-surface);
            border-radius: 6px;
        }

        .card {
            width: 18rem;
            overflow: hidden;
            transition: transform 150ms ease-out;
        }

        .card:hover {
            transform: scale(1.03);
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
    <main>
        <section class="hero">
            <p class="eyebrow">New Season</p>
            <h1 class="headline">Built for how you actually move.</h1>
            <p class="hero-body">Optical and sun frames edited for fit, finish, and everyday wear — with lenses ready in every pair.</p>
            <a href="#" class="btn-pill" onclick="return false;">Shop the collection</a>
        </section>

        <section>
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

        <section>
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
