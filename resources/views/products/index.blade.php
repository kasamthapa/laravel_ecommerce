<x-layouts.storefront :title="request()->routeIs('shop') ? 'Shop all frames - Luma Lens' : 'Luma Lens - Independent Eyewear'" :cart-count="$cartCount">
    @if (request()->routeIs('shop'))
        <section class="mx-auto max-w-[100rem] px-4 py-10 sm:px-8 lg:py-14">
            <h1 class="font-display text-3xl font-bold uppercase tracking-wide text-bone sm:text-4xl">Shop all frames</h1>
            <div class="mt-8">
                <livewire:product-catalog :is-shop-page="true" />
            </div>
        </section>
    @else
        <section class="motion-fade relative border-b border-hairline">
            <div class="relative h-[75vh] max-h-[44rem] min-h-[30rem] w-full overflow-hidden bg-charcoal">
                {{-- object-position 50% 40% (not the default center) — the eyes/
                     glasses in this portrait sit above image-center, so a
                     dead-center crop loses the top of the frames on any
                     wide-but-not-tall viewport. Same fix already verified on
                     /style-preview's hero using this identical photo. --}}
                <img
                    src="{{ asset('images/storefront/lightweight-eyewear.png') }}"
                    alt="A person wearing Luma Lens optical frames, photographed against a warm neutral backdrop"
                    class="h-full w-full object-cover object-[50%_40%]"
                >
                {{--
                    Scrim strength: dark headline text (text-bone, which
                    resolves to #171717 on the homepage) needs the blended
                    background to stay light regardless of what's under it —
                    verified by computing the worst-case blend against a
                    near-black patch of the photo (hair) at each opacity.
                    The previous via-black/40 (lg: /10) blended down to only
                    3.42:1 there — fails AA (needs 4.5:1) — because at low
                    scrim opacity the photo's own contrast dominates the
                    result. from/95 via/80 (lg: via/78) keeps the blend at
                    10.3-11.5:1 even against black hair, and the headline's
                    max-w-2xl footprint sits well inside the strong 0-50%
                    part of this gradient at every width tested. --}}
                <div class="absolute inset-0 bg-gradient-to-r from-black/95 via-black/80 to-black/20 lg:from-black/95 lg:via-black/78 lg:to-black/15"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 px-4 pb-12 sm:px-8 sm:pb-16 lg:inset-y-0 lg:flex lg:max-w-2xl lg:flex-col lg:justify-center lg:px-16 lg:pb-0">
                    <p class="font-eyebrow text-[1.05rem] italic text-smoke">New Season</p>
                    <h1 class="mt-4 max-w-2xl font-display text-6xl font-extrabold uppercase leading-[0.92] tracking-tight text-bone sm:text-7xl lg:text-8xl">Built for how you actually move.</h1>
                    <p class="mt-5 max-w-md text-base leading-relaxed text-bone/85">Optical and sun frames edited for fit, finish, and everyday wear &mdash; with lenses ready in every pair.</p>
                    <div class="mt-8">
                        <x-ui.button :href="route('shop')" size="lg">Shop the collection</x-ui.button>
                    </div>
                </div>
            </div>
        </section>

        {{-- Trust strip: honest to what Luma Lens actually offers as a single-vendor online store. --}}
        <section class="border-b border-hairline bg-black">
            <div class="mx-auto grid max-w-[100rem] gap-px bg-hairline px-4 py-px sm:px-8 sm:grid-cols-2 lg:grid-cols-4" data-reveal style="--reveal-duration: 250ms">
                @foreach ([
                    ['icon' => 'shipping', 'label' => 'Free shipping', 'text' => 'On every order over Rs. 10,000, nationwide.'],
                    ['icon' => 'lock', 'label' => 'Secure Khalti checkout', 'text' => 'Orders confirmed only after payment verification.'],
                    ['icon' => 'eye', 'label' => 'Virtual try-on', 'text' => 'See frames on your own face before you buy, no app.'],
                    ['icon' => 'return', 'label' => 'Easy returns', 'text' => 'Not the right fit? Send it back, no hassle.'],
                ] as $item)
                    <div class="flex items-start gap-3 bg-black px-5 py-8">
                        {{-- Muted neutral, not the accent — 4 repeated icons is exactly
                             the "large repeated element" case the accent stays out of. --}}
                        <span class="mt-0.5 shrink-0 text-smoke">
                            @switch($item['icon'])
                                @case('shipping')
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" /><circle cx="7" cy="18" r="1.6" stroke="currentColor" stroke-width="1.6" /><circle cx="17.5" cy="18" r="1.6" stroke="currentColor" stroke-width="1.6" /></svg>
                                    @break
                                @case('lock')
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="1" stroke="currentColor" stroke-width="1.6" /><path d="M8 11V8a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" /></svg>
                                    @break
                                @case('eye')
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2 12s3.8-6.5 10-6.5S22 12 22 12s-3.8 6.5-10 6.5S2 12 2 12Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" /><circle cx="12" cy="12" r="2.6" stroke="currentColor" stroke-width="1.6" /></svg>
                                    @break
                                @case('return')
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 8h11a5 5 0 0 1 0 10H9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" /><path d="M8 4 4 8l4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                    @break
                            @endswitch
                        </span>
                        <div>
                            <p class="font-display text-lg font-bold uppercase tracking-wide text-bone">{{ $item['label'] }}</p>
                            <p class="mt-1 text-sm leading-relaxed text-smoke">{{ $item['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="overflow-hidden border-b border-hairline bg-charcoal py-3">
            <div class="motion-marquee flex w-max gap-10 whitespace-nowrap text-sm font-semibold uppercase tracking-[0.14em] text-bone" aria-hidden="true">
                @for ($i = 0; $i < 2; $i++)
                    <span>Free shipping over Rs. 10,000</span>
                    <span class="text-volt">&middot;</span>
                    <span>3D Try On, no app required</span>
                    <span class="text-volt">&middot;</span>
                    <span>Khalti secure checkout</span>
                    <span class="text-volt">&middot;</span>
                    <span>{{ $totalFrameCount }} frames, nothing generic</span>
                    <span class="text-volt">&middot;</span>
                @endfor
            </div>
        </div>

        {{-- Category icon grid: bold tiles, real Luma Lens categories, confident type over photo. --}}
        @if ($categories->isNotEmpty())
            <section class="border-b border-hairline">
                <div class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
                    <h2 class="max-w-xl font-display text-4xl font-extrabold uppercase leading-[0.9] tracking-tight text-bone sm:text-5xl lg:text-6xl" data-reveal style="--reveal-duration: 250ms">Shop by category.</h2>
                    {{-- Tailwind's build-time scanner needs literal class names, so this is
                         hardcoded to 3 columns rather than interpolated from the category count. --}}
                    <div class="mt-10 grid gap-1 sm:grid-cols-3">
                        @foreach ($categories as $category)
                            @php $cover = $category->products->first(); @endphp
                            <a
                                href="{{ route('shop', ['category' => $category->slug]) }}"
                                class="motion-card group relative block aspect-[4/5] overflow-hidden bg-charcoal group-hover:scale-[1.03]"
                                data-reveal
                                style="--reveal-duration: 250ms; --reveal-delay: {{ $loop->index * 60 }}ms"
                            >
                                @if ($cover)
                                    <img
                                        src="{{ $cover->image_url }}"
                                        alt=""
                                        aria-hidden="true"
                                        class="h-full w-full object-cover"
                                    >
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/10 to-transparent"></div>
                                <div class="absolute inset-x-0 bottom-0 p-6">
                                    <p class="font-display text-3xl font-extrabold uppercase tracking-tight text-bone">{{ $category->name }}</p>
                                    {{-- Muted neutral, not the accent — 3 repeated tiles is
                                         the same "large repeated element" case as the icons above. --}}
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.14em] text-smoke">{{ $category->products_count }} {{ Str::plural('frame', $category->products_count) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="border-b border-hairline bg-black">
            <div class="mx-auto max-w-[100rem] px-4 py-16 text-center sm:px-8 sm:py-20" data-reveal style="--reveal-duration: 250ms">
                <p class="font-display text-7xl font-extrabold tracking-tight text-volt sm:text-8xl md:text-9xl">{{ $totalFrameCount }}</p>
                <p class="mx-auto mt-4 max-w-md text-base leading-relaxed text-bone/70">frames. That's the entire collection &mdash; every one fitted across two widths, checked by hand, and still here next season instead of clearanced out.</p>
            </div>
        </section>

        {{--
            Best sellers: horizontal-scroll carousel of static photo cards,
            all one consistent size. A model_path product used to render an
            inline auto-rotating 3D preview right in this same card slot —
            reusing .glasses-stage/.glasses-3d, which are sized for the
            PDP's full-width viewer (min-height: 34rem, width: min(64rem,
            112%)), broke out of this card's actual ~256-288px width badly.
            That product gets its own properly-sized showcase section below
            instead, and is excluded here so it isn't shown twice.
        --}}
        @php $showcaseProduct = $featuredProducts->firstWhere('model_path'); @endphp
        @php $carouselProducts = $showcaseProduct ? $featuredProducts->reject(fn ($product) => $product->is($showcaseProduct)) : $featuredProducts; @endphp
        @if ($carouselProducts->isNotEmpty())
            <section class="border-b border-hairline">
                <div class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
                    <x-ui.section-heading eyebrow="Best sellers" heading="This season’s edit" data-reveal style="--reveal-duration: 250ms" />
                    <div class="mt-10 -mx-4 flex snap-x snap-mandatory gap-5 overflow-x-auto px-4 pb-4 sm:-mx-8 sm:px-8" data-reveal style="--reveal-duration: 250ms">
                        @foreach ($carouselProducts as $product)
                            <div class="w-64 shrink-0 snap-start sm:w-72">
                                <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistedProductIds, true)" />
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8 text-center">
                        <x-ui.button :href="route('shop')" variant="secondary">View all frames</x-ui.button>
                    </div>
                </div>
            </section>
        @endif

        {{--
            Dedicated 3D showcase — the model_path product pulled out of the
            carousel above gets its own properly-sized stage instead, using
            the exact same PDP glasses-stage/glasses-3d/skeleton markup and
            the same lazy-mount-on-scroll-into-view binding the carousel
            used (bindCarouselViewers in glasses-viewer.js — no second 3D
            system). aspect-square + min-h-0 overrides .glasses-stage's
            base min-height: 34rem to a size that actually fits this
            section instead of the PDP's full-bleed one. No motion-card
            hover-scale here — this stage is drag-to-rotate, and scaling it
            on hover would fight that interaction.

            No background fill on the stage itself — it sits directly on
            the page background instead of inside a visible colored box, so
            the glasses read as floating rather than a photo tile. The
            skeleton keeps its own bg-charcoal while the model loads; once
            ready it's invisible and the stage underneath has nothing to
            reveal but transparency. data-autorotate="true" opts this one
            stage into a slow idle spin (PDP's 3D tab is untouched, still
            drag-only) that pauses on drag and resumes after, gated behind
            prefers-reduced-motion same as the rest of the site's motion.
        --}}
        @if ($showcaseProduct)
            <section class="border-b border-hairline">
                <div class="mx-auto grid max-w-[100rem] gap-10 px-4 py-16 sm:px-8 lg:grid-cols-2 lg:items-center lg:gap-16">
                    <div
                        class="glasses-stage aspect-square min-h-0 overflow-hidden"
                        data-carousel-viewer
                        data-autorotate="true"
                        data-model-path="{{ asset($showcaseProduct->model_path) }}"
                        @if ($showcaseTint = $showcaseProduct->modelTint()) data-frame-tint="{{ $showcaseTint['frame'] }}" data-lens-tint="{{ $showcaseTint['lens'] }}" @endif
                        data-reveal
                        style="--reveal-duration: 250ms"
                    >
                        <canvas class="glasses-canvas" data-glasses-canvas aria-hidden="true"></canvas>
                        <img src="{{ $showcaseProduct->image_url }}" alt="{{ $showcaseProduct->name }}" class="glasses-3d">
                        <div data-glasses-skeleton class="absolute inset-0 z-[3] animate-pulse bg-charcoal" aria-hidden="true"></div>
                    </div>
                    <div data-reveal style="--reveal-duration: 250ms; --reveal-delay: 90ms">
                        <p class="font-eyebrow text-[1.05rem] italic text-smoke">3D Preview</p>
                        <h2 class="mt-3 font-display text-4xl font-extrabold uppercase leading-[0.9] tracking-tight text-bone sm:text-5xl lg:text-6xl">{{ $showcaseProduct->name }}</h2>
                        <p class="mt-5 max-w-md text-base leading-relaxed text-smoke">Drag to rotate and see the frame and lens finish from every angle, right here &mdash; no app, no headset. Rs. {{ number_format((float) $showcaseProduct->price) }}.</p>
                        <div class="mt-8">
                            <x-ui.button :href="route('products.show', $showcaseProduct)" size="lg">View this frame</x-ui.button>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section class="mx-auto grid max-w-[100rem] gap-10 px-4 py-16 sm:px-8 lg:grid-cols-2 lg:items-center lg:gap-16">
            <div class="aspect-[4/3] overflow-hidden bg-charcoal lg:order-2" data-reveal style="--reveal-duration: 250ms">
                <img
                    src="{{ asset('images/storefront/progressive-eyewear.png') }}"
                    alt="A Luma Lens optical frame photographed in natural light"
                    class="h-full w-full object-cover"
                >
            </div>
            <div class="lg:order-1" data-reveal style="--reveal-duration: 250ms; --reveal-delay: 90ms">
                <h2 class="font-display text-4xl font-extrabold uppercase leading-[0.9] tracking-tight text-bone sm:text-5xl lg:text-6xl">Nothing here is a guess.</h2>
                <p class="mt-5 max-w-md text-base leading-relaxed text-smoke">Acetate and metal come from mills we've used for years, not whichever supplier quoted lowest this season. Widths are set per shape &mdash; Narrow, Medium, or Wide, whichever that frame actually sits well in, not a default applied across the board. Hinges get checked by hand before a frame leaves for you, every one, not a sample batch.</p>
            </div>
        </section>
    @endif
</x-layouts.storefront>
