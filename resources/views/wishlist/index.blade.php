<x-layouts.storefront title="Wishlist - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
        {{-- Editorial kicker above the headline, non-interactive — same
             reasoning as "Your selection" (cart), "Checkout", "Order
             status" (/track), and "Your account" (account/orders). --}}
        <p class="font-eyebrow text-[1.05rem] italic text-smoke">Saved for later</p>
        <h1 class="mt-3 font-display font-bold uppercase text-3xl text-bone sm:text-4xl">Your wishlist</h1>

        @if ($products->isEmpty())
            {{-- A normal secondary/primary action per the established
                 pattern, not a one-off — <x-ui.button> already inherits
                 the pill/sentence-case scoped rule, same as every other
                 empty-state CTA on cart, account/orders, etc. --}}
            <div class="mt-8 rounded-[6px] border border-dashed border-hairline p-12 text-center">
                <p class="font-display font-semibold text-2xl text-bone">Your wishlist is empty</p>
                <p class="mt-2 text-sm text-smoke">Tap the heart on any frame to save it here.</p>
                <x-ui.button :href="route('shop')" class="mt-6">Browse frames</x-ui.button>
            </div>
        @else
            <div class="mt-10 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" :wishlisted="true" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->links('vendor.pagination.luma-catalog') }}
            </div>
        @endif
    </section>
</x-layouts.storefront>
