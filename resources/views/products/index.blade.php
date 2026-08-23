<x-layouts.storefront :title="request()->routeIs('shop') ? 'Shop all frames - Luma Lens' : 'Luma Lens - Independent Eyewear'" :cart-count="$cartCount">
    @if (request()->routeIs('shop'))
        <section class="mx-auto max-w-[100rem] px-4 py-10 sm:px-8 lg:py-14">
            <h1 class="font-serif text-3xl text-ink sm:text-4xl">Shop all frames</h1>
            <div class="mt-8">
                <livewire:product-catalog :is-shop-page="true" />
            </div>
        </section>
    @else
        <section class="motion-fade relative border-b border-line">
            <div class="relative h-[75vh] max-h-[44rem] min-h-[30rem] w-full overflow-hidden bg-cream-dim">
                <img
                    src="{{ asset('images/storefront/lightweight-eyewear.png') }}"
                    alt="A person wearing Luma Lens optical frames, photographed against a warm neutral backdrop"
                    class="h-full w-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-ink/70 via-ink/25 to-transparent lg:from-ink/65 lg:via-transparent lg:to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-ink/60 via-transparent to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 px-4 pb-12 sm:px-8 sm:pb-16 lg:inset-y-0 lg:flex lg:max-w-2xl lg:flex-col lg:justify-center lg:px-16 lg:pb-0">
                    <p class="text-xs font-medium uppercase tracking-[0.14em] text-cream/80">New season</p>
                    <h1 class="mt-4 max-w-2xl font-serif text-4xl leading-[1.05] text-cream sm:text-5xl lg:text-6xl">Frames considered, not chosen in a hurry.</h1>
                    <p class="mt-5 max-w-md text-base leading-relaxed text-cream/85">Optical and sun frames edited for fit, finish, and everyday wear &mdash; with lenses ready in every pair.</p>
                    <div class="mt-8">
                        <x-ui.button :href="route('shop')" size="lg">Shop the collection</x-ui.button>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-b border-line">
            <div class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
                <h2 class="max-w-xl font-serif text-3xl leading-tight text-ink sm:text-4xl" data-reveal>Three ways to see clearly.</h2>
                <div class="mt-10 grid gap-4 lg:grid-cols-3">
                    <a href="{{ route('shop', ['category' => 'sunglasses']) }}" class="group relative block aspect-[4/5] overflow-hidden bg-cream-dim lg:col-span-2 lg:row-span-2 lg:aspect-auto" data-reveal>
                        <img
                            src="https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&w=1600&q=85"
                            alt="Sunglasses"
                            class="h-full w-full object-cover transition-opacity duration-200 ease-out group-hover:opacity-85"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div>
                        <p class="motion-press absolute bottom-6 left-6 font-serif text-2xl text-cream">Sunglasses</p>
                    </a>
                    <a href="{{ route('shop', ['category' => 'optical-frames']) }}" class="group relative block aspect-[16/9] overflow-hidden bg-cream-dim" data-reveal style="--reveal-delay: 80ms">
                        <img
                            src="https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&w=1200&q=85"
                            alt="Eyeglasses"
                            class="h-full w-full object-cover transition-opacity duration-200 ease-out group-hover:opacity-85"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div>
                        <p class="motion-press absolute bottom-5 left-5 font-serif text-xl text-cream">Eyeglasses</p>
                    </a>
                    <a href="{{ route('shop', ['category' => 'blue-light']) }}" class="group relative block aspect-[16/9] overflow-hidden bg-cream-dim" data-reveal style="--reveal-delay: 160ms">
                        <img
                            src="https://images.unsplash.com/photo-1591076482161-42ce6da69f67?auto=format&fit=crop&w=1200&q=85"
                            alt="Blue light frames"
                            class="h-full w-full object-cover transition-opacity duration-200 ease-out group-hover:opacity-85"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div>
                        <p class="motion-press absolute bottom-5 left-5 font-serif text-xl text-cream">Blue light</p>
                    </a>
                </div>
            </div>
        </section>

        <section class="border-b border-line bg-ink">
            <div class="mx-auto max-w-[100rem] px-4 py-16 text-center sm:px-8 sm:py-20" data-reveal>
                <p class="font-serif text-6xl text-gold sm:text-7xl">{{ $totalFrameCount }}</p>
                <p class="mx-auto mt-4 max-w-md text-base leading-relaxed text-cream/70">frames. That's the entire collection &mdash; every one fitted across two widths, checked by hand, and still here next season instead of clearanced out.</p>
            </div>
        </section>

        @if ($featuredProducts->isNotEmpty())
            <section class="border-b border-line">
                <div class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
                    <x-ui.section-heading eyebrow="Featured" heading="This season’s edit" data-reveal />
                    <div class="mt-10 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($featuredProducts as $product)
                            <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistedProductIds, true)" data-reveal style="--reveal-delay: {{ $loop->index * 70 }}ms" />
                        @endforeach
                    </div>
                    <div class="mt-12 text-center">
                        <x-ui.button :href="route('shop')" variant="secondary">View all frames</x-ui.button>
                    </div>
                </div>
            </section>
        @endif

        <section class="mx-auto grid max-w-[100rem] gap-10 px-4 py-16 sm:px-8 lg:grid-cols-2 lg:items-center lg:gap-16">
            <div class="aspect-[4/3] overflow-hidden bg-cream-dim lg:order-2" data-reveal>
                <img
                    src="{{ asset('images/storefront/progressive-eyewear.png') }}"
                    alt="A Luma Lens optical frame photographed in natural light"
                    class="h-full w-full object-cover"
                >
            </div>
            <div class="lg:order-1" data-reveal style="--reveal-delay: 90ms">
                <h2 class="font-serif text-3xl leading-tight text-ink sm:text-4xl">Nothing here is a guess.</h2>
                <p class="mt-5 max-w-md text-base leading-relaxed text-stone">Acetate and metal come from mills we've used for years, not whichever supplier quoted lowest this season. Widths are set per shape &mdash; Narrow, Medium, or Wide, whichever that frame actually sits well in, not a default applied across the board. Hinges get checked by hand before a frame leaves for you, every one, not a sample batch.</p>
            </div>
        </section>
    @endif
</x-layouts.storefront>
