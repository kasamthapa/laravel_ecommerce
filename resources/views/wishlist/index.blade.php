<x-layouts.storefront title="Wishlist - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
        <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Saved for later</p>
        <h1 class="mt-3 font-serif text-3xl text-ink sm:text-4xl">Your wishlist</h1>

        @if ($products->isEmpty())
            <div class="mt-8 border border-dashed border-line p-12 text-center">
                <p class="font-serif text-2xl text-ink">Your wishlist is empty</p>
                <p class="mt-2 text-sm text-stone">Tap the heart on any frame to save it here.</p>
                <x-ui.button :href="route('shop')" class="mt-6">Browse frames</x-ui.button>
            </div>
        @else
            <div class="mt-10 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" :wishlisted="true" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @endif
    </section>
</x-layouts.storefront>
