<x-layouts.storefront title="My Orders - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-3xl px-4 py-14 sm:px-8">
        {{-- Editorial kicker above the headline, non-interactive — same
             reasoning as cart's "Your selection", checkout's "Checkout",
             and /track's "Order status". --}}
        <p class="font-eyebrow text-[1.05rem] italic text-smoke">Your account</p>
        <h1 class="mt-3 font-display font-bold uppercase text-3xl text-bone sm:text-4xl">Order history</h1>

        @if ($orders->isEmpty())
            <div class="mt-8 rounded-[6px] border border-dashed border-hairline p-12 text-center">
                <p class="font-display font-semibold text-2xl text-bone">No orders yet</p>
                <p class="mt-2 text-sm text-smoke">Once you place an order, it will show up here.</p>
                <x-ui.button :href="route('shop')" class="mt-6">Start shopping</x-ui.button>
            </div>
        @else
            <div class="mt-8 grid gap-0">
                @foreach ($orders as $order)
                    <a href="{{ route('account.orders.show', $order) }}" class="motion-invert flex flex-wrap items-center justify-between gap-4 border-b border-hairline py-5 hover:text-volt">
                        <div>
                            <p class="font-display font-semibold text-lg text-bone">{{ $order->order_number }}</p>
                            <p class="mt-1 text-sm text-smoke">Placed {{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <x-admin.status-badge :status="$order->status" />
                        <p class="text-bone">Rs. {{ number_format((float) $order->total) }}</p>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links('vendor.pagination.luma-catalog') }}
            </div>
        @endif
    </section>
</x-layouts.storefront>
