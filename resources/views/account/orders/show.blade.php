<x-layouts.storefront :title="'Order '.$order->order_number.' - Luma Lens'" :cart-count="$cartCount">
    <section class="mx-auto max-w-3xl px-4 py-14 sm:px-8">
        <a href="{{ route('account.orders.index') }}" class="motion-invert text-sm font-medium text-bone hover:text-volt">&larr; Back to orders</a>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="font-display font-bold uppercase text-3xl text-bone">{{ $order->order_number }}</h1>
                <p class="mt-1 text-sm text-smoke">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <x-admin.status-badge :status="$order->status" />
        </div>

        <div class="mt-8 grid gap-4">
            @foreach ($order->orderItems as $item)
                <div class="flex items-center justify-between gap-4 border-b border-hairline pb-4 last:border-0">
                    <div class="flex items-center gap-4">
                        @if ($item->product)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="h-16 w-16 bg-charcoal object-cover">
                        @endif
                        <div>
                            <p class="text-bone">{{ $item->product_name }}</p>
                            <p class="text-sm text-smoke">Qty {{ $item->quantity }} &middot; Fit {{ $item->size ?? 'Any' }} &middot; {{ $item->color ?? 'Any finish' }}</p>
                            @if ($item->prescription)
                                <p class="text-sm {{ ($item->prescription['status'] ?? null) === 'later' ? 'text-smoke' : 'text-signal-good' }}">
                                    {{ ($item->prescription['status'] ?? null) === 'later' ? "Prescription: we'll follow up by email" : 'Prescription on file' }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <p class="text-bone">Rs. {{ number_format((float) $item->line_total) }}</p>
                </div>
            @endforeach
        </div>

        <dl class="mt-8 grid gap-2 rounded-[6px] border border-hairline p-6 text-sm">
            <div class="flex justify-between"><dt class="text-smoke">Subtotal</dt><dd class="text-bone">Rs. {{ number_format((float) $order->subtotal) }}</dd></div>
            <div class="flex justify-between"><dt class="text-smoke">Shipping</dt><dd class="text-bone">Rs. {{ number_format((float) $order->shipping_total) }}</dd></div>
            @if ((float) $order->discount_total > 0)
                <div class="flex justify-between"><dt class="text-signal-good">Discount</dt><dd class="text-signal-good">&minus;Rs. {{ number_format((float) $order->discount_total) }}</dd></div>
            @endif
            <div class="flex justify-between border-t border-hairline pt-2 text-base"><dt class="font-medium text-bone">Total</dt><dd class="font-medium text-bone">Rs. {{ number_format((float) $order->total) }}</dd></div>
        </dl>

        <div class="mt-8 grid gap-8 sm:grid-cols-2">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Shipping to</p>
                <p class="mt-2 text-bone">{{ $order->customer_name }}</p>
                <p class="text-sm text-smoke">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Payment</p>
                <p class="mt-2 capitalize text-bone">{{ $order->payment_method }} &middot; {{ $order->payment_status }}</p>
                @if ($order->paid_at)
                    <p class="text-sm text-smoke">Paid {{ $order->paid_at->format('d M Y, H:i') }}</p>
                @endif
            </div>
        </div>
    </section>
</x-layouts.storefront>
