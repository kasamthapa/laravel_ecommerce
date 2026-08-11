<x-layouts.storefront title="My Orders - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-3xl px-4 py-14 sm:px-8">
        <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Your account</p>
        <h1 class="mt-3 font-serif text-3xl text-ink sm:text-4xl">Order history</h1>

        @if ($orders->isEmpty())
            <div class="mt-8 border border-dashed border-line p-12 text-center">
                <p class="font-serif text-2xl text-ink">No orders yet</p>
                <p class="mt-2 text-sm text-stone">Once you place an order, it will show up here.</p>
                <x-ui.button :href="route('shop')" class="mt-6">Start shopping</x-ui.button>
            </div>
        @else
            <div class="mt-8 grid gap-0">
                @foreach ($orders as $order)
                    <a href="{{ route('account.orders.show', $order) }}" class="motion-press flex flex-wrap items-center justify-between gap-4 border-b border-line py-5">
                        <div>
                            <p class="font-serif text-lg text-ink">{{ $order->order_number }}</p>
                            <p class="mt-1 text-sm text-stone">Placed {{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <x-admin.status-badge :status="$order->status" />
                        <p class="text-ink">Rs. {{ number_format((float) $order->total) }}</p>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </section>
</x-layouts.storefront>
