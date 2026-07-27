<x-layouts.storefront title="Luma Lens - Independent Eyewear" :cart-count="$cartCount">
    <section class="overflow-hidden bg-[#f7f8fa]">
        <div class="mx-auto grid min-h-[44rem] max-w-[120rem] items-center gap-8 px-4 py-8 sm:px-8 lg:grid-cols-[0.46fr_0.54fr] lg:py-16">
            <div class="motion-fade order-2 max-w-[34rem] lg:order-1">
                <p class="text-sm font-black uppercase tracking-[0.16em] text-[#08765e]">New season eyewear</p>
                <h1 class="wp-serif mt-5 text-[3.2rem] font-normal leading-[1.02] tracking-normal text-zinc-950 sm:text-[5rem] lg:text-[5.7rem]">New takes on classic shapes</h1>
                <p class="mt-6 max-w-[29rem] text-xl leading-8 text-zinc-700">Sculpted frames, premium lenses, and a clean checkout built for Nepal.</p>
                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="#shape" class="motion-press rounded-full bg-[#115be8] px-8 py-4 text-base font-black text-white hover:bg-[#092b83]">Start with a quiz</a>
                    <a href="#collection" class="motion-press rounded-full bg-[#092b83] px-8 py-4 text-base font-black text-white hover:bg-[#115be8]">Shop eyeglasses</a>
                </div>
                <a href="{{ route('products.index', ['q' => 'tortoise']) }}#collection" class="mt-7 inline-flex items-center gap-3 text-lg font-black text-zinc-950 hover:text-[#092b83]">
                    Shop Tortoise Color Block
                    <span class="text-3xl leading-none">›</span>
                </a>
            </div>

            <div class="glasses-stage motion-fade-slow order-1 lg:order-2" data-glasses-viewer aria-hidden="true">
                <canvas class="glasses-canvas" data-glasses-canvas></canvas>
                <img
                    src="{{ asset('images/storefront/hero-glasses-3d.png') }}"
                    alt=""
                    class="glasses-3d"
                >
            </div>
        </div>

        <div class="border-y border-zinc-200 bg-white">
            <div class="mx-auto grid max-w-[120rem] gap-4 px-4 py-5 text-sm font-black text-zinc-700 sm:grid-cols-4 sm:px-8 lg:text-base">
                <p>20% off first contacts order</p>
                <p>Free shipping</p>
                <p>Free 30-day returns</p>
                <p>Vision benefits accepted</p>
            </div>
        </div>
    </section>

    <section id="progressives" class="bg-white px-4 py-16 sm:px-8">
        <div class="motion-fade-slow mx-auto grid max-w-[114rem] overflow-hidden rounded-3xl bg-[#c9ecf3] lg:grid-cols-[minmax(0,0.42fr)_minmax(0,0.58fr)]">
            <div class="flex items-center px-8 py-14 sm:px-16 lg:px-20">
                <div class="max-w-[34rem]">
                    <p class="text-sm font-black uppercase tracking-[0.16em] text-[#08765e]">Lens options</p>
                    <h2 class="wp-serif mt-5 text-[3rem] font-normal leading-[1.08] text-zinc-950 sm:text-[4.2rem]">Turn any pair into progressives</h2>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="#collection" class="motion-press rounded-full bg-[#115be8] px-8 py-4 text-base font-black text-white hover:bg-[#092b83]">Shop eyeglasses</a>
                        <a href="#materials" class="motion-press rounded-full bg-[#092b83] px-8 py-4 text-base font-black text-white hover:bg-[#115be8]">Lens options</a>
                    </div>
                </div>
            </div>
            <div class="min-h-[28rem] min-w-0 overflow-hidden">
                <img
                    src="{{ asset('images/storefront/progressive-eyewear.png') }}"
                    alt="Smiling woman wearing blue tortoise optical glasses"
                    class="h-full min-h-[28rem] w-full object-cover object-[68%_center]"
                >
            </div>
        </div>
    </section>

    <section id="materials" class="overflow-hidden bg-[#f7fbfd] px-4 py-16 sm:px-8">
        <div class="mx-auto grid max-w-[120rem] items-center gap-10 lg:grid-cols-[0.38fr_0.62fr]">
            <div class="max-w-[32rem]">
                <p class="text-lg font-black text-[#08765e]">Starting at Rs. 8,800</p>
                <h2 class="wp-serif mt-5 text-[3.2rem] font-normal leading-[1.08] text-zinc-950 sm:text-[4.6rem]">Premium materials in every pair</h2>
                <p class="mt-6 text-xl leading-8 text-zinc-700">Light, balanced frames with lens coatings included.</p>
                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="#collection" class="motion-press rounded-full bg-[#115be8] px-8 py-4 text-base font-black text-white hover:bg-[#092b83]">Shop Rs. 8,800 frames</a>
                    <a href="#collection" class="motion-press rounded-full bg-[#092b83] px-8 py-4 text-base font-black text-white hover:bg-[#115be8]">Shop all</a>
                </div>
            </div>

            <div class="relative min-h-[38rem] overflow-hidden rounded-[2rem] bg-white shadow-[0_35px_120px_rgb(9_43_131_/_12%)]">
                <img
                    src="https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=2200&q=92"
                    alt="Premium tortoise frame with optical lenses"
                    class="absolute inset-0 h-full w-full object-cover object-[58%_center]"
                >
                <div class="absolute inset-x-0 bottom-0 grid gap-3 bg-white/88 p-5 backdrop-blur sm:grid-cols-3">
                    <div class="rounded-2xl border border-zinc-200 bg-white p-4 text-sm font-bold leading-6 text-[#092b83]">UVA and UVB blocking prescription lenses</div>
                    <div class="rounded-2xl border border-zinc-200 bg-white p-4 text-sm font-bold leading-6 text-[#092b83]">Impact-resistant polycarbonate lenses</div>
                    <div class="rounded-2xl border border-zinc-200 bg-white p-4 text-sm font-bold leading-6 text-[#092b83]">Anti-reflective, scratch-resistant coatings</div>
                </div>
            </div>
        </div>
    </section>

    <section id="quiz" class="grid bg-white lg:grid-cols-2">
        <a href="{{ route('products.index', ['q' => 'tinted']) }}#collection" class="group relative min-h-[43rem] overflow-hidden bg-zinc-100">
            <img src="https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&w=1400&q=90" alt="Stacked tinted sunglasses" class="motion-image absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/45 via-transparent to-transparent"></div>
            <div class="absolute bottom-12 left-8 max-w-[28rem] sm:left-16">
                <h2 class="wp-serif text-5xl font-black leading-tight text-white">A subtle hue for your view</h2>
                <span class="motion-press mt-8 inline-flex rounded-full bg-white px-8 py-4 text-lg font-black text-zinc-950">Shop tinted lenses</span>
            </div>
        </a>
        <a href="{{ route('products.index', ['q' => 'lightweight']) }}#collection" class="group relative min-h-[43rem] overflow-hidden bg-[#f8efe8]">
            <img src="{{ asset('images/storefront/lightweight-eyewear.png') }}" alt="Man wearing lightweight titanium optical glasses" class="motion-image absolute inset-0 h-full w-full object-cover object-[center_38%]">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/45 via-transparent to-transparent"></div>
            <div class="absolute bottom-12 left-8 max-w-[28rem] sm:left-16">
                <h2 class="wp-serif text-5xl font-black leading-tight text-white">A new level of lightweight</h2>
                <span class="motion-press mt-8 inline-flex rounded-full bg-white px-8 py-4 text-lg font-black text-zinc-950">Shop Aero Series</span>
            </div>
        </a>
    </section>

    <section id="shape" class="mx-auto max-w-[120rem] px-4 py-16 sm:px-8">
        <div data-motion-reveal class="flex flex-col gap-4 border-b border-zinc-200 pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-lg font-black text-[#08765e]">Find your fit</p>
                <h2 class="wp-serif mt-2 text-5xl font-black leading-tight">Shop by frame shape</h2>
            </div>
            <a href="#collection" class="motion-press inline-flex rounded-full border-2 border-[#115be8] px-8 py-3 text-lg font-black text-[#115be8] hover:bg-[#115be8] hover:text-white">Shop all</a>
        </div>
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @foreach (['Square', 'Round', 'Cat Eye', 'Clubmaster', 'Aviator'] as $shape)
                <a data-motion-reveal href="{{ route('products.index', ['q' => $shape]) }}#collection" class="motion-lift rounded-3xl border border-zinc-200 bg-white px-5 py-8 text-center hover:border-[#092b83] hover:shadow-lg">
                    <span class="mx-auto grid h-16 w-24 place-items-center rounded-full border-2 border-zinc-950 text-sm font-black">{{ $shape }}</span>
                    <span class="mt-5 block text-sm font-black uppercase text-zinc-600">Explore</span>
                </a>
            @endforeach
        </div>
    </section>

    <section id="collection" class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div data-motion-reveal class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-black uppercase text-[#092b83]">Shop by</p>
                <h2 class="mt-2 max-w-2xl text-3xl font-black">
                    @if ($search !== '')
                        Results for “{{ $search }}”
                    @else
                        New arrivals
                    @endif
                </h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-zinc-600">Try on the edit by browsing new frames, colors, and lens-ready styles.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('products.index', array_filter(['q' => $search ?: null])) }}#collection" class="rounded-full border px-4 py-2 text-sm font-bold {{ $selectedCategory === null ? 'border-[#092b83] bg-[#092b83] text-white' : 'border-zinc-300 bg-white text-zinc-700 hover:border-zinc-950' }}">All</a>
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', array_filter(['category' => $category->slug, 'q' => $search ?: null])) }}#collection" class="rounded-full border px-4 py-2 text-sm font-bold {{ $selectedCategory?->is($category) ? 'border-[#092b83] bg-[#092b83] text-white' : 'border-zinc-300 bg-white text-zinc-700 hover:border-zinc-950' }}">
                        {{ $category->name }} ({{ $category->products_count }})
                    </a>
                @endforeach
            </div>
        </div>

        <form method="GET" action="{{ route('products.index') }}#collection" data-auto-search-form data-motion-reveal class="mt-8 grid gap-3 rounded-lg border border-zinc-200 bg-white p-4 shadow-sm sm:grid-cols-[1fr_auto_auto]">
            @if ($selectedCategory !== null)
                <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
            @endif
            <label class="sr-only" for="q">Search frames</label>
            <input
                id="q"
                name="q"
                value="{{ $search }}"
                placeholder="Search by frame name, finish, fit, or category"
                autocomplete="off"
                data-auto-search-input
                class="rounded-full border border-zinc-300 px-4 py-3 text-sm font-medium outline-none transition focus:border-[#092b83] focus:ring-2 focus:ring-[#092b83]/20"
            >
            <button class="motion-press rounded-full bg-[#092b83] px-5 py-3 text-sm font-black text-white hover:bg-zinc-950">Search</button>
            @if ($search !== '')
                <a href="{{ route('products.index', array_filter(['category' => $selectedCategory?->slug])) }}#collection" class="rounded-full border border-zinc-300 px-5 py-3 text-center text-sm font-black text-zinc-700 transition hover:border-zinc-950 hover:text-zinc-950">Clear</a>
            @endif
        </form>

        @if ($products->isEmpty())
            <div class="mt-8 border border-dashed border-zinc-300 bg-white p-10 text-center">
                <h3 class="text-2xl font-black">No frames found</h3>
                <p class="mt-2 text-zinc-600">Try one of these instead:</p>
                <div class="mt-5 flex flex-wrap justify-center gap-2">
                    @foreach (['sunglasses', 'tortoise', 'round', 'blue light', 'black'] as $suggestion)
                        <a href="{{ route('products.index', ['q' => $suggestion]) }}#collection" class="motion-press rounded-full border border-zinc-300 bg-white px-4 py-2 text-sm font-bold text-zinc-700 hover:border-[#092b83] hover:text-[#092b83]">{{ ucfirst($suggestion) }}</a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)
                    <x-product-card :product="$product" data-motion-reveal />
                @endforeach
            </div>
        @endif

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </section>

    <section class="bg-[#092b83] text-white">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-12 sm:px-6 md:grid-cols-[1fr_1.2fr] lg:px-8">
            <div data-motion-reveal>
                <p class="text-sm font-black uppercase text-[#d9f4fb]">Store in Nepal</p>
                <h2 class="mt-2 text-4xl font-black">A small store experience, online.</h2>
                <p class="mt-4 max-w-md text-sm leading-6 text-white/75">The product range is intentionally edited so the page feels more like a studio wall than a warehouse feed.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-3">
                <div data-motion-reveal class="motion-lift rounded-lg bg-white/10 p-5">
                    <h3 class="font-black">Kathmandu Studio</h3>
                    <p class="mt-2 text-sm text-white/75">Durbarmarg optical pickup desk, appointment friendly.</p>
                </div>
                <div data-motion-reveal class="motion-lift rounded-lg bg-white/10 p-5">
                    <h3 class="font-black">Online Orders</h3>
                    <p class="mt-2 text-sm text-white/75">Browse, login, checkout, and confirm your frame order online.</p>
                </div>
                <div data-motion-reveal class="motion-lift rounded-lg bg-white/10 p-5">
                    <h3 class="font-black">Khalti Powered</h3>
                    <p class="mt-2 text-sm text-white/75">Payment flow uses Khalti initiation and lookup before order confirmation.</p>
                </div>
            </div>
        </div>
    </section>
</x-layouts.storefront>
