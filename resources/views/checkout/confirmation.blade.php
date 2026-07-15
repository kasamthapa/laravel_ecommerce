<x-layouts.storefront title="Order {{ $order->order_number }} - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="motion-fade motion-glow rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-bold uppercase text-[#092b83]">Order confirmed</p>
            <h1 class="mt-2 text-3xl font-black">Thanks, {{ $order->customer_name }}.</h1>
            <p class="mt-3 text-zinc-600">Your order <span class="font-bold text-zinc-950">{{ $order->order_number }}</span> has been received.</p>
            <div class="mt-3 inline-flex items-center gap-3 rounded-full border border-[#092b83]/20 bg-[#f4f8fb] px-4 py-2 text-sm font-bold text-[#092b83]">
                <img src="{{ asset('images/khalti_logo.svg') }}" alt="Khalti" class="h-5 w-auto">
                <span>Payment: {{ ucfirst($order->payment_status) }} via Khalti</span>
            </div>
            <div class="mt-3 flex w-fit items-center gap-2 rounded-md border border-zinc-200 bg-white px-3 py-2 text-xs font-bold uppercase text-zinc-500">
                <span>Powered by</span>
                <img src="{{ asset('images/khalti_logo.svg') }}" alt="Khalti" class="h-4 w-auto">
            </div>

            <div class="mt-8 grid gap-4">
                @foreach ($order->orderItems as $item)
                    <div class="flex justify-between gap-4 border-b border-zinc-100 pb-4">
                        <div>
                            <p class="font-black">{{ $item->product_name }}</p>
                            <p class="text-sm text-zinc-500">Qty {{ $item->quantity }} · Fit {{ $item->size ?? 'Any' }} · {{ $item->color ?? 'Any finish' }}</p>
                        </div>
                        <p class="font-black">Rs. {{ number_format((float) $item->line_total) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-between gap-4 text-lg">
                <span class="font-black">Total</span>
                <span class="font-black">Rs. {{ number_format((float) $order->total) }}</span>
            </div>

            <a href="{{ route('products.index') }}" class="motion-press mt-8 inline-flex rounded-full bg-zinc-950 px-5 py-3 font-black text-white hover:bg-[#092b83]">Back to shop</a>
        </div>
    </section>
</x-layouts.storefront>
