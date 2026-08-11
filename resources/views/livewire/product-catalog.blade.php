<div>
    <div class="flex items-center justify-between gap-4 border-b border-line pb-6 lg:hidden">
        <p class="text-sm text-stone">{{ $products->total() }} {{ $products->total() === 1 ? 'frame' : 'frames' }}</p>
        <button type="button" wire:click="toggleFilters" aria-expanded="{{ $filtersOpen ? 'true' : 'false' }}" class="motion-press border border-ink px-4 py-2 text-sm text-ink">
            {{ $filtersOpen ? 'Hide filters' : 'Filters' }}
        </button>
    </div>

    <div class="lg:grid lg:grid-cols-[16rem_1fr] lg:items-start lg:gap-12">
        <aside class="{{ $filtersOpen ? 'block' : 'hidden' }} border-b border-line py-6 lg:sticky lg:top-24 lg:block lg:border-b-0 lg:py-0">
            <div>
                <h2 class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Category</h2>
                <div class="mt-3 grid gap-2.5 text-sm">
                    <button type="button" wire:click="selectCategory('')" class="motion-press w-fit text-left {{ $selectedCategory === null ? 'font-medium text-ink' : 'text-stone hover:text-ink' }}">All frames</button>
                    @foreach ($categories as $categoryOption)
                        <button type="button" wire:click="selectCategory('{{ $categoryOption->slug }}')" class="motion-press w-fit text-left {{ $selectedCategory?->is($categoryOption) ? 'font-medium text-ink' : 'text-stone hover:text-ink' }}">
                            {{ $categoryOption->name }} ({{ $categoryOption->products_count }})
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 border-t border-line pt-8">
                <h2 class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Price (Rs.)</h2>
                <div class="mt-3 grid grid-cols-2 gap-4">
                    <label class="grid gap-1 text-xs text-stone">
                        Min
                        <input type="number" wire:model.live.debounce.500ms="minPrice" min="0" class="border-0 border-b border-line bg-transparent py-1.5 text-sm text-ink outline-none focus:border-accent">
                    </label>
                    <label class="grid gap-1 text-xs text-stone">
                        Max
                        <input type="number" wire:model.live.debounce.500ms="maxPrice" min="0" class="border-0 border-b border-line bg-transparent py-1.5 text-sm text-ink outline-none focus:border-accent">
                    </label>
                </div>
            </div>

            @if ($availableColors->isNotEmpty())
                <div class="mt-8 border-t border-line pt-8">
                    <h2 class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Color</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($availableColors as $colorOption)
                            <button type="button" wire:click="selectColor('{{ $colorOption }}')" class="motion-press border px-3 py-1.5 text-xs {{ $color === $colorOption ? 'border-ink text-ink' : 'border-line text-stone hover:border-ink hover:text-ink' }}">
                                {{ $colorOption }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($search !== '' || $category !== '' || $color !== '' || $minPrice !== '' || $maxPrice !== '')
                <button type="button" wire:click="clearFilters" class="motion-press mt-8 text-xs font-medium uppercase tracking-wide text-accent hover:underline">Clear filters</button>
            @endif
        </aside>

        <div>
            <div class="mb-8 hidden items-center justify-between lg:flex">
                <p class="text-sm text-stone">{{ $products->total() }} {{ $products->total() === 1 ? 'frame' : 'frames' }}</p>
                <label class="flex items-center gap-2 text-sm text-stone">
                    Sort by
                    <select wire:model.live="sort" class="border-0 border-b border-line bg-transparent py-1 text-sm text-ink outline-none focus:border-accent">
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
                    class="w-full border-0 border-b border-line bg-transparent py-2.5 text-base text-ink outline-none placeholder:text-stone-light focus:border-accent"
                >
            </div>

            <label class="mt-6 flex items-center gap-2 text-sm text-stone lg:hidden">
                Sort by
                <select wire:model.live="sort" class="border-0 border-b border-line bg-transparent py-1 text-sm text-ink outline-none focus:border-accent">
                    <option value="">Newest</option>
                    <option value="price_asc">Price: Low to high</option>
                    <option value="price_desc">Price: High to low</option>
                </select>
            </label>

            <div class="mt-8" wire:loading.class="opacity-60" wire:target="search, category, color, sort, minPrice, maxPrice, selectCategory, selectColor, clearFilters">
                @if ($products->isEmpty())
                    <div class="border border-dashed border-line px-6 py-16 text-center">
                        <p class="font-serif text-2xl text-ink">No frames found</p>
                        <p class="mt-2 text-sm text-stone">Try a different search term or clear your filters.</p>
                        <button type="button" wire:click="clearFilters" class="motion-press mt-6 text-sm font-medium text-accent hover:underline">Clear filters</button>
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
