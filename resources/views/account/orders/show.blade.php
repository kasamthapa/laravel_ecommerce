<x-layouts.storefront :title="'Order '.$order->order_number.' - Luma Lens'" :cart-count="$cartCount">
    <section class="mx-auto max-w-3xl px-4 py-14 sm:px-8">
        <a href="{{ route('account.orders.index') }}" class="motion-press text-sm font-medium text-ink hover:opacity-70">&larr; Back to orders</a>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="font-serif text-3xl text-ink">{{ $order->order_number }}</h1>
                <p class="mt-1 text-sm text-stone">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <x-admin.status-badge :status="$order->status" />
        </div>

        <div class="mt-8 grid gap-4">
            @foreach ($order->orderItems as $item)
                <div class="flex items-center justify-between gap-4 border-b border-line pb-4 last:border-0">
                    <div class="flex items-center gap-4">
                        @if ($item->product)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="h-16 w-16 bg-cream-dim object-cover">
                        @endif
                        <div>
                            <p class="text-ink">{{ $item->product_name }}</p>
                            <p class="text-sm text-stone">Qty {{ $item->quantity }} &middot; Fit {{ $item->size ?? 'Any' }} &middot; {{ $item->color ?? 'Any finish' }}</p>
                        </div>
                    </div>
                    <p class="text-ink">Rs. {{ number_format((float) $item->line_total) }}</p>
                </div>
            @endforeach
        </div>

        <dl class="mt-8 grid gap-2 border border-line p-6 text-sm">
            <div class="flex justify-between"><dt class="text-stone">Subtotal</dt><dd class="text-ink">Rs. {{ number_format((float) $order->subtotal) }}</dd></div>
            <div class="flex justify-between"><dt class="text-stone">Shipping</dt><dd class="text-ink">Rs. {{ number_format((float) $order->shipping_total) }}</dd></div>
            @if ((float) $order->discount_total > 0)
                <div class="flex justify-between"><dt class="text-success">Discount</dt><dd class="text-success">&minus;Rs. {{ number_format((float) $order->discount_total) }}</dd></div>
            @endif
            <div class="flex justify-between border-t border-line pt-2 text-base"><dt class="font-medium text-ink">Total</dt><dd class="font-medium text-ink">Rs. {{ number_format((float) $order->total) }}</dd></div>
        </dl>

        <div class="mt-8 grid gap-8 sm:grid-cols-2">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Shipping to</p>
                <p class="mt-2 text-ink">{{ $order->customer_name }}</p>
                <p class="text-sm text-stone">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Payment</p>
                <p class="mt-2 capitalize text-ink">{{ $order->payment_method }} &middot; {{ $order->payment_status }}</p>
                @if ($order->paid_at)
                    <p class="text-sm text-stone">Paid {{ $order->paid_at->format('d M Y, H:i') }}</p>
                @endif
            </div>
        </div>
    </section>
</x-layouts.storefront>
