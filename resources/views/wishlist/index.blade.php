<x-layouts.storefront title="Wishlist - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-bold uppercase text-[#092b83]">Saved for later</p>
        <h1 class="mt-2 text-3xl font-black">Your wishlist</h1>

        @if ($products->isEmpty())
            <div class="mt-8 rounded-lg border border-dashed border-zinc-300 bg-white p-10 text-center">
                <h2 class="text-xl font-black">Your wishlist is empty</h2>
                <p class="mt-2 text-zinc-600">Tap the heart on any frame to save it here.</p>
                <a href="{{ route('shop') }}" class="motion-press mt-5 inline-flex rounded-full bg-[#092b83] px-5 py-3 font-black text-white hover:bg-zinc-950">Browse frames</a>
            </div>
        @else
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)
                    <x-product-card :product="$product" :wishlisted="true" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </section>
</x-layouts.storefront>
