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
            <div class="mx-auto max-w-[100rem] px-4 pt-14 sm:px-8">
                <x-ui.section-heading eyebrow="Edit" heading="Shop by category" align="center" class="mx-auto" data-reveal />
            </div>
            <div class="mx-auto grid max-w-[100rem] gap-8 px-4 pb-14 pt-10 sm:px-8 sm:grid-cols-3">
                @foreach ([
                    ['label' => 'Eyeglasses', 'slug' => 'optical-frames', 'image' => 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&w=1200&q=85'],
                    ['label' => 'Sunglasses', 'slug' => 'sunglasses', 'image' => 'https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&w=1200&q=85'],
                    ['label' => 'Blue light', 'slug' => 'blue-light', 'image' => 'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?auto=format&fit=crop&w=1200&q=85'],
                ] as $tile)
                    <a href="{{ route('shop', ['category' => $tile['slug']]) }}" class="group block" data-reveal style="--reveal-delay: {{ $loop->index * 80 }}ms">
                        <div class="aspect-[4/5] overflow-hidden bg-cream-dim">
                            <img
                                src="{{ $tile['image'] }}"
                                alt="{{ $tile['label'] }} frames"
                                class="h-full w-full object-cover transition-opacity duration-200 ease-out group-hover:opacity-85"
                            >
                        </div>
                        <p class="motion-press mt-4 font-serif text-xl text-ink">{{ $tile['label'] }}</p>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="border-b border-line bg-cream-dim">
            <div class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
                <x-ui.section-heading eyebrow="Craft" heading="What goes into every pair" align="center" class="mx-auto" data-reveal />
                <div class="mt-10 grid gap-8 sm:grid-cols-3">
                    @foreach ([
                        ['step' => '01', 'label' => 'Sourced', 'image' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=1000&q=85', 'body' => 'Acetate and metal from mills we\'ve worked with for years — not whatever\'s cheapest that season.'],
                        ['step' => '02', 'label' => 'Fitted', 'image' => 'https://images.unsplash.com/photo-1587310311582-aa7610e90826?auto=format&fit=crop&w=1000&q=85', 'body' => 'Every shape is tested across real face widths before it reaches the shop, not just modeled on a screen.'],
                        ['step' => '03', 'label' => 'Finished', 'image' => 'https://images.unsplash.com/photo-1610136649349-0f646f318053?auto=format&fit=crop&w=1000&q=85', 'body' => 'Hinges, hardware, and lens edging are checked by hand before a frame ever ships to you.'],
                    ] as $step)
                        <div data-reveal style="--reveal-delay: {{ $loop->index * 90 }}ms">
                            <div class="aspect-[4/5] overflow-hidden bg-cream">
                                <img src="{{ $step['image'] }}" alt="{{ $step['label'] }}" class="h-full w-full object-cover">
                            </div>
                            <p class="mt-4 text-xs font-medium tracking-[0.14em] text-stone">{{ $step['step'] }}</p>
                            <h3 class="mt-1 font-serif text-xl text-ink">{{ $step['label'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-stone">{{ $step['body'] }}</p>
                        </div>
                    @endforeach
                </div>
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
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Our approach</p>
                <h2 class="mt-4 font-serif text-3xl leading-tight text-ink sm:text-4xl">A small, considered collection</h2>
                <p class="mt-5 max-w-md text-base leading-relaxed text-stone">We keep the range tight on purpose. Every frame is chosen for how it wears day to day &mdash; the weight on your nose, the way it catches light, how it looks by the third wear, not just the first. No seasonal clutter to sort through, just frames worth keeping.</p>
            </div>
        </section>
    @endif
</x-layouts.storefront>
