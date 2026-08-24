<x-layouts.storefront title="Order {{ $order->order_number }} - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-xl px-4 py-16 text-center sm:px-8">
        <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-black border border-signal-good/40">
            <svg class="h-8 w-8 text-signal-good" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M5 12.5 10 17 19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <h1 class="mt-6 font-display font-bold uppercase text-3xl text-bone sm:text-4xl">Thank you, {{ $order->customer_name }}.</h1>
        <p class="mt-3 text-base text-smoke">Your order has been confirmed.</p>

        <p class="mt-6 text-xs font-medium uppercase tracking-[0.14em] text-smoke">Order number</p>
        <p class="mt-1 font-display font-bold uppercase text-2xl text-bone">{{ $order->order_number }}</p>

        <p class="mt-6 text-sm text-smoke">Estimated delivery: 3&ndash;5 business days within Nepal, or 1&ndash;2 days in Kathmandu Valley.</p>

        <div class="mt-10 border-y border-hairline py-8 text-left">
            <p class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">What happens next</p>
            <ol class="mt-4 grid gap-3 text-sm text-bone">
                <li class="flex gap-3"><span class="text-smoke">1.</span> We confirm your payment and prepare your frames for packing.</li>
                <li class="flex gap-3"><span class="text-smoke">2.</span> Your order ships and you'll be able to track it by order number and email.</li>
                <li class="flex gap-3"><span class="text-smoke">3.</span> Your frames arrive, ready to wear.</li>
            </ol>
        </div>

        <div class="mt-8 grid gap-3 text-left">
            @foreach ($order->orderItems as $item)
                <div class="flex justify-between gap-4 border-b border-hairline pb-3 text-sm last:border-0">
                    <div>
                        <p class="text-bone">{{ $item->product_name }}</p>
                        <p class="text-smoke">Qty {{ $item->quantity }} &middot; Fit {{ $item->size ?? 'Any' }} &middot; {{ $item->color ?? 'Any finish' }}</p>
                    </div>
                    <p class="text-bone">Rs. {{ number_format((float) $item->line_total) }}</p>
                </div>
            @endforeach
            <div class="flex justify-between gap-4 pt-2 text-base">
                <span class="font-medium text-bone">Total</span>
                <span class="font-medium text-bone">Rs. {{ number_format((float) $order->total) }}</span>
            </div>
        </div>

        <a href="{{ route('shop') }}" class="motion-invert mt-10 inline-block text-sm font-medium text-bone underline decoration-1 underline-offset-4 hover:text-volt">Continue shopping</a>
    </section>
</x-layouts.storefront>
