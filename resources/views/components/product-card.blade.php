@props(['product', 'wishlisted' => false])

<article {{ $attributes->merge(['class' => 'group relative']) }}>
    @php $gallery = $product->gallery(); @endphp

    <a href="{{ route('products.show', $product) }}" class="block" aria-label="{{ $product->name }}">
        <div class="relative aspect-[4/5] overflow-hidden bg-cream-dim">
            <x-ui.product-image
                :src="$gallery[0]"
                :alt="$product->name"
                class="h-full w-full object-cover transition-opacity duration-200 ease-out {{ $product->stock < 1 ? 'grayscale' : '' }} {{ count($gallery) > 1 ? 'group-hover:opacity-0' : 'group-hover:opacity-85' }}"
            />
            @if (count($gallery) > 1)
                <x-ui.product-image
                    :src="$gallery[1]"
                    :alt="''"
                    aria-hidden="true"
                    class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-200 ease-out group-hover:opacity-100 {{ $product->stock < 1 ? 'grayscale' : '' }}"
                />
            @endif
        </div>
    </a>

    @if ($product->model_path)
        <p class="pointer-events-none absolute left-3 top-3 border border-gold bg-cream/90 px-2 py-1 text-[0.65rem] font-medium uppercase tracking-[0.1em] text-ink">
            3D &middot; Try On
        </p>
    @endif

    @auth
        <div class="absolute right-3 top-3">
            <livewire:wishlist-button :product="$product" :wishlisted="$wishlisted" size="h-8 w-8 text-base" :key="'wishlist-card-'.$product->id" />
        </div>
    @endauth

    <a href="{{ route('products.show', $product) }}" class="mt-4 block">
        <h3 class="font-serif text-lg text-ink">{{ $product->name }}</h3>
        <p class="mt-1 text-sm text-stone">
            @if ($product->stock < 1)
                Sold out
            @else
                @if ($product->compare_at_price)
                    <span class="text-stone-light line-through">Rs. {{ number_format((float) $product->compare_at_price) }}</span>
                @endif
                Rs. {{ number_format((float) $product->price) }}
            @endif
        </p>
    </a>
</article>
