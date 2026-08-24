<div>
    <div class="flex items-center justify-between gap-4 border-b border-hairline pb-6 lg:hidden">
        <p class="text-sm text-smoke">{{ $products->total() }} {{ $products->total() === 1 ? 'frame' : 'frames' }}</p>
        <button type="button" wire:click="toggleFilters" aria-expanded="{{ $filtersOpen ? 'true' : 'false' }}" class="motion-invert border border-volt px-4 py-2 text-sm text-bone">
            {{ $filtersOpen ? 'Hide filters' : 'Filters' }}
        </button>
    </div>

    <div class="lg:grid lg:grid-cols-[16rem_1fr] lg:items-start lg:gap-12">
        <aside class="{{ $filtersOpen ? 'block' : 'hidden' }} border-b border-hairline py-6 lg:sticky lg:top-24 lg:block lg:border-b-0 lg:py-0">
            <div>
                <h2 class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Category</h2>
                <div class="mt-3 grid gap-2.5 text-sm">
                    <button type="button" wire:click="selectCategory('')" class="motion-invert w-fit text-left {{ $selectedCategory === null ? 'font-medium text-bone' : 'text-smoke hover:text-bone' }}">All frames</button>
                    @foreach ($categories as $categoryOption)
                        <button type="button" wire:click="selectCategory('{{ $categoryOption->slug }}')" class="motion-invert w-fit text-left {{ $selectedCategory?->is($categoryOption) ? 'font-medium text-bone' : 'text-smoke hover:text-bone' }}">
                            {{ $categoryOption->name }} ({{ $categoryOption->products_count }})
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 border-t border-hairline pt-8">
                <h2 class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Price (Rs.)</h2>
                <div class="mt-3 grid grid-cols-2 gap-4">
                    <label class="grid gap-1 text-xs text-smoke">
                        Min
                        <input type="number" wire:model.live.debounce.500ms="minPrice" min="0" class="border-0 border-b border-hairline bg-transparent py-1.5 text-sm text-bone outline-none focus:border-volt">
                    </label>
                    <label class="grid gap-1 text-xs text-smoke">
                        Max
                        <input type="number" wire:model.live.debounce.500ms="maxPrice" min="0" class="border-0 border-b border-hairline bg-transparent py-1.5 text-sm text-bone outline-none focus:border-volt">
                    </label>
                </div>
            </div>

            @if ($availableColors->isNotEmpty())
                <div class="mt-8 border-t border-hairline pt-8">
                    <h2 class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Color</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($availableColors as $colorOption)
                            <button type="button" wire:click="selectColor('{{ $colorOption }}')" class="motion-invert border px-3 py-1.5 text-xs {{ $color === $colorOption ? 'border-volt text-bone' : 'border-hairline text-smoke hover:border-volt hover:text-bone' }}">
                                {{ $colorOption }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($search !== '' || $category !== '' || $color !== '' || $minPrice !== '' || $maxPrice !== '')
                <button type="button" wire:click="clearFilters" class="motion-invert mt-8 text-xs font-medium uppercase tracking-wide text-volt hover:underline">Clear filters</button>
            @endif
        </aside>

        <div>
            <div class="mb-8 hidden items-center justify-between lg:flex">
                <p class="text-sm text-smoke">{{ $products->total() }} {{ $products->total() === 1 ? 'frame' : 'frames' }}</p>
                <label class="flex items-center gap-2 text-sm text-smoke">
                    Sort by
                    <select wire:model.live="sort" class="border-0 border-b border-hairline bg-transparent py-1 text-sm text-bone outline-none focus:border-volt">
                        <option value="">Newest</option>
                        <option value="price_asc">Price: Low to high</option>
                        <option value="price_desc">Price: High to low</option>
                    </select>
                </label>
            </div>

            <div class="mt-6 lg:mt-0">
                <label class="sr-only" for="catalog-search">Search frames</label>
                <input
                    id="catalog-search"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Search by name, finish, or fit"
                    autocomplete="off"
                    class="w-full border-0 border-b border-hairline bg-transparent py-2.5 text-base text-bone outline-none placeholder:text-smoke-dim focus:border-volt"
                >
            </div>

            <label class="mt-6 flex items-center gap-2 text-sm text-smoke lg:hidden">
                Sort by
                <select wire:model.live="sort" class="border-0 border-b border-hairline bg-transparent py-1 text-sm text-bone outline-none focus:border-volt">
                    <option value="">Newest</option>
                    <option value="price_asc">Price: Low to high</option>
                    <option value="price_desc">Price: High to low</option>
                </select>
            </label>

            @php
                $loadingTargets = 'search, category, color, sort, minPrice, maxPrice, selectCategory, selectColor, clearFilters';
            @endphp

            <div class="hidden" wire:loading.class.remove="hidden" wire:target="{{ $loadingTargets }}">
                <div class="mt-8 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3" aria-hidden="true">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="animate-pulse">
                            <div class="aspect-[4/5] bg-charcoal"></div>
                            <div class="mt-4 h-5 w-2/3 bg-charcoal"></div>
                            <div class="mt-2 h-4 w-1/3 bg-charcoal"></div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="mt-8" wire:loading.class="hidden" wire:target="{{ $loadingTargets }}">
                @if ($products->isEmpty())
                    <div class="border border-dashed border-hairline px-6 py-16 text-center">
                        <p class="font-display font-semibold text-2xl text-bone">No frames found</p>
                        <p class="mt-2 text-sm text-smoke">Try a different search term or clear your filters.</p>
                        <button type="button" wire:click="clearFilters" class="motion-invert mt-6 text-sm font-medium text-volt hover:underline">Clear filters</button>
                    </div>
                @else
                    <div class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistedProductIds, true)" wire:key="catalog-product-{{ $product->id }}" />
                        @endforeach
                    </div>
                @endif

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
