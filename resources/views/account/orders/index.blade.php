<x-layouts.storefront title="My Orders - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-bold uppercase text-[#092b83]">Your account</p>
        <h1 class="mt-2 text-3xl font-black">Order history</h1>

        @if ($orders->isEmpty())
            <div class="mt-8 rounded-lg border border-dashed border-zinc-300 bg-white p-10 text-center">
                <h2 class="text-xl font-black">No orders yet</h2>
                <p class="mt-2 text-zinc-600">Once you place an order, it will show up here.</p>
                <a href="{{ route('shop') }}" class="motion-press mt-5 inline-flex rounded-full bg-[#092b83] px-5 py-3 font-black text-white hover:bg-zinc-950">Start shopping</a>
            </div>
        @else
            <div class="mt-8 grid gap-4">
                @foreach ($orders as $order)
                    <a href="{{ route('account.orders.show', $order) }}" class="motion-lift flex flex-wrap items-center justify-between gap-4 rounded-lg border border-zinc-200 bg-white p-5 hover:border-[#092b83]">
                        <div>
                            <p class="font-black text-zinc-950">{{ $order->order_number }}</p>
                            <p class="mt-1 text-sm text-zinc-500">Placed {{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <x-admin.status-badge :status="$order->status" />
                        <p class="font-black">Rs. {{ number_format((float) $order->total) }}</p>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </section>
</x-layouts.storefront>
