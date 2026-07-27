<x-layouts.admin :title="'Order '.$order->order_number.' - Luma Lens Admin'">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#092b83] hover:underline">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        Back to orders
    </a>

    <div class="mt-3 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-zinc-950">{{ $order->order_number }}</h1>
            <p class="mt-1 text-sm text-zinc-500">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <x-admin.status-badge :status="$order->status" class="text-sm" />
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_20rem]">
        <div class="grid gap-6">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="font-black text-zinc-950">Items</h2>
                <div class="mt-4 grid gap-4">
                    @foreach ($order->orderItems as $item)
                        <div class="flex items-center justify-between gap-4 border-b border-zinc-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                @if ($item->product)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="h-12 w-12 rounded-xl object-cover shadow-sm">
                                @endif
                                <div>
                                    <p class="font-bold text-zinc-950">{{ $item->product_name }}</p>
                                    <p class="text-sm text-zinc-500">Qty {{ $item->quantity }} &middot; Fit {{ $item->size ?? 'Any' }} &middot; {{ $item->color ?? 'Any finish' }}</p>
                                </div>
                            </div>
                            <p class="font-bold text-zinc-950">Rs. {{ number_format((float) $item->line_total) }}</p>
                        </div>
                    @endforeach
                </div>

                <dl class="mt-5 grid gap-2 border-t border-zinc-100 pt-4 text-sm">
                    <div class="flex justify-between"><dt class="text-zinc-500">Subtotal</dt><dd class="font-bold text-zinc-950">Rs. {{ number_format((float) $order->subtotal) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-zinc-500">Shipping</dt><dd class="font-bold text-zinc-950">Rs. {{ number_format((float) $order->shipping_total) }}</dd></div>
                    <div class="flex justify-between text-base"><dt class="font-black text-zinc-950">Total</dt><dd class="font-black text-zinc-950">Rs. {{ number_format((float) $order->total) }}</dd></div>
                </dl>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="font-black text-zinc-950">Customer &amp; shipping</h2>
                <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                    <div><dt class="text-xs font-black uppercase tracking-wide text-zinc-400">Name</dt><dd class="mt-1 font-bold text-zinc-950">{{ $order->customer_name }}</dd></div>
                    <div><dt class="text-xs font-black uppercase tracking-wide text-zinc-400">Email</dt><dd class="mt-1 font-bold text-zinc-950">{{ $order->customer_email }}</dd></div>
                    <div><dt class="text-xs font-black uppercase tracking-wide text-zinc-400">Phone</dt><dd class="mt-1 font-bold text-zinc-950">{{ $order->customer_phone }}</dd></div>
                    <div><dt class="text-xs font-black uppercase tracking-wide text-zinc-400">City</dt><dd class="mt-1 font-bold text-zinc-950">{{ $order->shipping_city }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs font-black uppercase tracking-wide text-zinc-400">Address</dt><dd class="mt-1 font-bold text-zinc-950">{{ $order->shipping_address }}</dd></div>
                    @if ($order->notes)
                        <div class="sm:col-span-2"><dt class="text-xs font-black uppercase tracking-wide text-zinc-400">Notes</dt><dd class="mt-1 font-bold text-zinc-950">{{ $order->notes }}</dd></div>
                    @endif
                </dl>
            </div>
        </div>

        <div class="grid gap-6">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="font-black text-zinc-950">Payment</h2>
                <dl class="mt-4 grid gap-3 text-sm">
                    <div class="flex justify-between"><dt class="text-zinc-500">Method</dt><dd class="font-bold capitalize text-zinc-950">{{ $order->payment_method }}</dd></div>
                    <div class="flex justify-between"><dt class="text-zinc-500">Status</dt><dd class="font-bold capitalize text-zinc-950">{{ $order->payment_status }}</dd></div>
                    @if ($order->paid_at)
                        <div class="flex justify-between"><dt class="text-zinc-500">Paid at</dt><dd class="font-bold text-zinc-950">{{ $order->paid_at->format('d M Y, H:i') }}</dd></div>
                    @endif
                    @if ($order->khalti_transaction_id)
                        <div class="flex justify-between gap-3"><dt class="shrink-0 text-zinc-500">Transaction</dt><dd class="truncate font-bold text-zinc-950" title="{{ $order->khalti_transaction_id }}">{{ $order->khalti_transaction_id }}</dd></div>
                    @endif
                </dl>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="font-black text-zinc-950">Update status</h2>
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="mt-4 grid gap-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="rounded-xl border border-zinc-300 px-4 py-3 font-medium outline-none focus:border-[#092b83] focus:ring-2 focus:ring-[#092b83]/20">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                    <button class="motion-press rounded-full bg-[#092b83] px-5 py-3 font-black text-white hover:bg-zinc-950">Update status</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
