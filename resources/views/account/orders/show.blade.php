<x-layouts.storefront :title="'Order '.$order->order_number.' - Luma Lens'" :cart-count="$cartCount">
    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('account.orders.index') }}" class="text-sm font-bold text-[#092b83] hover:underline">&larr; Back to orders</a>

        <div class="mt-3 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black">{{ $order->order_number }}</h1>
                <p class="mt-1 text-sm text-zinc-500">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <x-admin.status-badge :status="$order->status" class="text-sm" />
        </div>

        <div class="mt-6 grid gap-4">
            @foreach ($order->orderItems as $item)
                <div class="flex items-center justify-between gap-4 rounded-lg border border-zinc-200 bg-white p-4">
                    <div class="flex items-center gap-3">
                        @if ($item->product)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="h-14 w-14 rounded-lg object-cover">
                        @endif
                        <div>
                            <p class="font-black">{{ $item->product_name }}</p>
                            <p class="text-sm text-zinc-500">Qty {{ $item->quantity }} &middot; Fit {{ $item->size ?? 'Any' }} &middot; {{ $item->color ?? 'Any finish' }}</p>
                        </div>
                    </div>
                    <p class="font-black">Rs. {{ number_format((float) $item->line_total) }}</p>
                </div>
            @endforeach
        </div>

        <dl class="mt-6 grid gap-2 rounded-lg border border-zinc-200 bg-white p-5 text-sm">
            <div class="flex justify-between"><dt class="text-zinc-500">Subtotal</dt><dd class="font-bold">Rs. {{ number_format((float) $order->subtotal) }}</dd></div>
            <div class="flex justify-between"><dt class="text-zinc-500">Shipping</dt><dd class="font-bold">Rs. {{ number_format((float) $order->shipping_total) }}</dd></div>
            @if ((float) $order->discount_total > 0)
                <div class="flex justify-between"><dt class="text-emerald-700">Discount</dt><dd class="font-bold text-emerald-700">&minus;Rs. {{ number_format((float) $order->discount_total) }}</dd></div>
            @endif
            <div class="flex justify-between border-t border-zinc-200 pt-2 text-base"><dt class="font-black">Total</dt><dd class="font-black">Rs. {{ number_format((float) $order->total) }}</dd></div>
        </dl>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-zinc-200 bg-white p-5">
                <p class="text-xs font-black uppercase text-zinc-400">Shipping to</p>
                <p class="mt-2 font-bold">{{ $order->customer_name }}</p>
                <p class="text-sm text-zinc-600">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5">
                <p class="text-xs font-black uppercase text-zinc-400">Payment</p>
                <p class="mt-2 font-bold capitalize">{{ $order->payment_method }} &middot; {{ $order->payment_status }}</p>
                @if ($order->paid_at)
                    <p class="text-sm text-zinc-600">Paid {{ $order->paid_at->format('d M Y, H:i') }}</p>
                @endif
            </div>
        </div>
    </section>
</x-layouts.storefront>
