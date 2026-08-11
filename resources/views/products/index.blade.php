<x-layouts.storefront :title="request()->routeIs('shop') ? 'Shop all frames - Luma Lens' : 'Luma Lens - Independent Eyewear'" :cart-count="$cartCount">
    @if (request()->routeIs('shop'))
        <section class="mx-auto max-w-[100rem] px-4 py-10 sm:px-8 lg:py-14">
            <h1 class="sr-only">Shop all frames</h1>
            <livewire:product-catalog :is-shop-page="true" />
        </section>
    @else
        <section class="motion-fade">
            <div class="aspect-[4/3] w-full overflow-hidden bg-cream-dim sm:aspect-[21/9]">
                <img
                    src="{{ asset('images/storefront/hero-glasses-real.jpg') }}"
                    alt="A pair of Luma Lens optical frames resting on a neutral surface in natural light"
                    class="h-full w-full object-cover"
                >
            </div>
            <div class="mx-auto max-w-2xl px-4 py-14 text-center sm:px-8">
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">New season</p>
                <h1 class="mt-4 font-serif text-4xl leading-[1.08] text-ink sm:text-5xl">Frames considered, not chosen in a hurry.</h1>
                <p class="mx-auto mt-5 max-w-md text-base leading-relaxed text-stone">Optical and sun frames edited for fit, finish, and everyday wear &mdash; with lenses ready in every pair.</p>
                <x-ui.button :href="route('shop')" class="mt-8">Shop the collection</x-ui.button>
            </div>
        </section>

        <section class="border-t border-line">
            <div class="mx-auto max-w-[100rem] px-4 pt-14 sm:px-8">
                <x-ui.section-heading eyebrow="Edit" heading="Shop by category" align="center" class="mx-auto" />
            </div>
            <div class="mx-auto grid max-w-[100rem] gap-8 px-4 pb-14 pt-10 sm:px-8 sm:grid-cols-3">
                @foreach ([
                    ['label' => 'Eyeglasses', 'slug' => 'optical-frames', 'image' => 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&w=1200&q=85'],
                    ['label' => 'Sunglasses', 'slug' => 'sunglasses', 'image' => 'https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&w=1200&q=85'],
                    ['label' => 'Blue light', 'slug' => 'blue-light', 'image' => 'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?auto=format&fit=crop&w=1200&q=85'],
                ] as $tile)
                    <a href="{{ route('shop', ['category' => $tile['slug']]) }}" class="group block">
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

        @if ($featuredProducts->isNotEmpty())
            <section class="border-t border-line bg-cream-dim">
                <div class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
                    <x-ui.section-heading eyebrow="Featured" heading="This season’s edit" />
                    <div class="mt-10 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($featuredProducts as $product)
                            <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistedProductIds, true)" />
                        @endforeach
                    </div>
                    <div class="mt-12 text-center">
                        <x-ui.button :href="route('shop')" variant="secondary">View all frames</x-ui.button>
                    </div>
                </div>
            </section>
        @endif

        <section class="mx-auto grid max-w-[100rem] gap-10 px-4 py-16 sm:px-8 lg:grid-cols-2 lg:items-center lg:gap-16">
            <div class="aspect-[4/3] overflow-hidden bg-cream-dim lg:order-2">
                <img
                    src="{{ asset('images/storefront/progressive-eyewear.png') }}"
                    alt="A Luma Lens optical frame photographed in natural light"
                    class="h-full w-full object-cover"
                >
            </div>
            <div class="lg:order-1">
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Our approach</p>
                <h2 class="mt-4 font-serif text-3xl leading-tight text-ink sm:text-4xl">A small, considered collection</h2>
                <p class="mt-5 max-w-md text-base leading-relaxed text-stone">We keep the range tight on purpose. Every frame is chosen for how it wears day to day &mdash; the weight on your nose, the way it catches light, how it looks by the third wear, not just the first. No seasonal clutter to sort through, just frames worth keeping.</p>
            </div>
        </section>
    @endif
</x-layouts.storefront>
