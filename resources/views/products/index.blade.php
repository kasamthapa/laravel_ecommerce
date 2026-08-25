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
                <img
                    src="{{ asset('images/storefront/lightweight-eyewear.png') }}"
                    alt="A person wearing Luma Lens optical frames, photographed against a warm neutral backdrop"
                    class="h-full w-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/40 to-transparent lg:from-black/80 lg:via-black/10 lg:to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 px-4 pb-12 sm:px-8 sm:pb-16 lg:inset-y-0 lg:flex lg:max-w-2xl lg:flex-col lg:justify-center lg:px-16 lg:pb-0">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-volt">New season</p>
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

        {{-- Best sellers: horizontal-scroll carousel. Products with a model_path
             get a subtle auto-rotating 3D preview (same viewer as the PDP's
             3D View tab, mounted lazily only while the card is in view); the
             rest fall back to the static photo. --}}
        @if ($featuredProducts->isNotEmpty())
            <section class="border-b border-hairline">
                <div class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
                    <x-ui.section-heading eyebrow="Best sellers" heading="This season’s edit" data-reveal style="--reveal-duration: 250ms" />
                    <div class="mt-10 -mx-4 flex snap-x snap-mandatory gap-5 overflow-x-auto px-4 pb-4 sm:-mx-8 sm:px-8" data-reveal style="--reveal-duration: 250ms">
                        @foreach ($featuredProducts as $product)
                            <div class="w-64 shrink-0 snap-start sm:w-72">
                                @if ($product->model_path)
                                    <article class="group relative">
                                        <div class="glasses-stage motion-card relative aspect-[4/5] overflow-hidden bg-charcoal group-hover:scale-[1.03]" data-carousel-viewer data-model-path="{{ $product->model_path }}" @if ($tint = $product->modelTint()) data-frame-tint="{{ $tint['frame'] }}" data-lens-tint="{{ $tint['lens'] }}" @endif>
                                            <canvas class="glasses-canvas" data-glasses-canvas aria-hidden="true"></canvas>
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="glasses-3d h-full w-full object-cover" loading="lazy">
                                            <p class="pointer-events-none absolute left-3 top-3 z-[3] border border-gold bg-black/90 px-2 py-1 text-[0.65rem] font-medium uppercase tracking-[0.1em] text-gold">3D</p>
                                        </div>
                                        <a href="{{ route('products.show', $product) }}" class="mt-4 block">
                                            <h3 class="font-display text-lg font-semibold uppercase tracking-wide text-bone">{{ $product->name }}</h3>
                                            <p class="mt-1 text-sm text-smoke">Rs. {{ number_format((float) $product->price) }}</p>
                                        </a>
                                    </article>
                                @else
                                    <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistedProductIds, true)" />
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8 text-center">
                        <x-ui.button :href="route('shop')" variant="secondary">View all frames</x-ui.button>
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
