<div>
    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-black uppercase text-[#092b83]">Shop by</p>
            <h2 class="mt-2 max-w-2xl text-3xl font-black">
                @if ($search !== '')
                    Results for &ldquo;{{ $search }}&rdquo;
                @elseif ($isShopPage)
                    All frames
                @else
                    New arrivals
                @endif
            </h2>
            <p class="mt-3 max-w-xl text-sm leading-6 text-zinc-600">Try on the edit by browsing new frames, colors, and lens-ready styles.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" wire:click="selectCategory('')" class="rounded-full border px-4 py-2 text-sm font-bold {{ $selectedCategory === null ? 'border-[#092b83] bg-[#092b83] text-white' : 'border-zinc-300 bg-white text-zinc-700 hover:border-zinc-950' }}">All</button>
            @foreach ($categories as $categoryOption)
                <button type="button" wire:click="selectCategory('{{ $categoryOption->slug }}')" class="rounded-full border px-4 py-2 text-sm font-bold {{ $selectedCategory?->is($categoryOption) ? 'border-[#092b83] bg-[#092b83] text-white' : 'border-zinc-300 bg-white text-zinc-700 hover:border-zinc-950' }}">
                    {{ $categoryOption->name }} ({{ $categoryOption->products_count }})
                </button>
            @endforeach
        </div>
    </div>

    <div class="mt-8 grid gap-3 rounded-lg border border-zinc-200 bg-white p-4 shadow-sm sm:grid-cols-[1fr_auto]">
        <label class="sr-only" for="catalog-search">Search frames</label>
        <input
            id="catalog-search"
            wire:model.live.debounce.400ms="search"
            placeholder="Search by frame name, finish, fit, or category"
            autocomplete="off"
            class="rounded-full border border-zinc-300 px-4 py-3 text-sm font-medium outline-none transition focus:border-[#092b83] focus:ring-2 focus:ring-[#092b83]/20"
        >
        @if ($search !== '')
            <button type="button" wire:click="clearSearch" class="rounded-full border border-zinc-300 px-5 py-3 text-center text-sm font-black text-zinc-700 transition hover:border-zinc-950 hover:text-zinc-950">Clear</button>
        @endif

        <div class="flex flex-wrap items-center gap-3 sm:col-span-2">
            <label class="flex items-center gap-2 text-sm font-bold text-zinc-600">
                Sort
                <select wire:model.live="sort" class="rounded-full border border-zinc-300 px-3 py-2 text-sm font-bold outline-none focus:border-[#092b83]">
                    <option value="">Newest</option>
                    <option value="price_asc">Price: Low to high</option>
                    <option value="price_desc">Price: High to low</option>
                </select>
            </label>
            <label class="flex items-center gap-2 text-sm font-bold text-zinc-600">
                Min Rs.
                <input type="number" wire:model.live.debounce.600ms="minPrice" min="0" class="w-24 rounded-full border border-zinc-300 px-3 py-2 text-sm font-medium outline-none focus:border-[#092b83]">
            </label>
            <label class="flex items-center gap-2 text-sm font-bold text-zinc-600">
                Max Rs.
                <input type="number" wire:model.live.debounce.600ms="maxPrice" min="0" class="w-24 rounded-full border border-zinc-300 px-3 py-2 text-sm font-medium outline-none focus:border-[#092b83]">
            </label>
            <span wire:loading class="text-xs font-bold uppercase text-[#092b83]">Updating&hellip;</span>
        </div>
    </div>

    <div wire:loading.class="opacity-60" wire:target="search, category, sort, minPrice, maxPrice, selectCategory, clearSearch">
        @if ($products->isEmpty())
            <div class="mt-8 border border-dashed border-zinc-300 bg-white p-10 text-center">
                <h3 class="text-2xl font-black">No frames found</h3>
                <p class="mt-2 text-zinc-600">Try one of these instead:</p>
                <div class="mt-5 flex flex-wrap justify-center gap-2">
                    @foreach (['sunglasses', 'tortoise', 'round', 'blue light', 'black'] as $suggestion)
                        <button type="button" wire:click="$set('search', '{{ $suggestion }}')" class="motion-press rounded-full border border-zinc-300 bg-white px-4 py-2 text-sm font-bold text-zinc-700 hover:border-[#092b83] hover:text-[#092b83]">{{ ucfirst($suggestion) }}</button>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)
                    <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistedProductIds, true)" wire:key="catalog-product-{{ $product->id }}" />
                @endforeach
            </div>
        @endif

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </div>
</div>
