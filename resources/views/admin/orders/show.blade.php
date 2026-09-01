<x-layouts.admin :title="'Order '.$order->order_number.' - Luma Lens Admin'">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-accent hover:underline">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        Back to orders
    </a>

    <div class="mt-3 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-ink">{{ $order->order_number }}</h1>
            <p class="mt-1 text-sm text-stone">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <x-admin.status-badge :status="$order->status" class="text-sm" />
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_20rem]">
        <div class="grid gap-6">
            <div class="rounded-2xl border border-line bg-cream p-6 shadow-sm">
                <h2 class="font-black text-ink">Items</h2>
                <div class="mt-4 grid gap-4">
                    @foreach ($order->orderItems as $item)
                        <div class="flex items-center justify-between gap-4 border-b border-cream-dim pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                @if ($item->product)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="h-12 w-12 rounded-xl object-cover shadow-sm">
                                @endif
                                <div>
                                    <p class="font-bold text-ink">{{ $item->product_name }}</p>
                                    <p class="text-sm text-stone">Qty {{ $item->quantity }} &middot; Fit {{ $item->size ?? 'Any' }} &middot; {{ $item->color ?? 'Any finish' }}</p>
                                    @if ($item->prescription)
                                        <div class="mt-2 rounded-xl bg-cream-dim px-3 py-2 text-xs font-medium text-stone">
                                            @if (($item->prescription['status'] ?? null) === 'later')
                                                Prescription: customer will provide later &mdash; follow up before shipping.
                                            @else
                                                SPH R {{ $item->prescription['sph_right'] ?? '—' }} / L {{ $item->prescription['sph_left'] ?? '—' }}
                                                &middot; CYL R {{ $item->prescription['cyl_right'] ?? '—' }} / L {{ $item->prescription['cyl_left'] ?? '—' }}
                                                &middot; Axis R {{ $item->prescription['axis_right'] ?? '—' }} / L {{ $item->prescription['axis_left'] ?? '—' }}
                                                &middot; PD {{ $item->prescription['pd'] ?? '—' }}mm
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <p class="font-bold text-ink">Rs. {{ number_format((float) $item->line_total) }}</p>
                        </div>
                    @endforeach
                </div>

                <dl class="mt-5 grid gap-2 border-t border-cream-dim pt-4 text-sm">
                    <div class="flex justify-between"><dt class="text-stone">Subtotal</dt><dd class="font-bold text-ink">Rs. {{ number_format((float) $order->subtotal) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-stone">Shipping</dt><dd class="font-bold text-ink">Rs. {{ number_format((float) $order->shipping_total) }}</dd></div>
                    <div class="flex justify-between text-base"><dt class="font-black text-ink">Total</dt><dd class="font-black text-ink">Rs. {{ number_format((float) $order->total) }}</dd></div>
                </dl>
            </div>

            <div class="rounded-2xl border border-line bg-cream p-6 shadow-sm">
                <h2 class="font-black text-ink">Customer &amp; shipping</h2>
                <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                    <div><dt class="text-xs font-black uppercase tracking-wide text-stone-light">Name</dt><dd class="mt-1 font-bold text-ink">{{ $order->customer_name }}</dd></div>
                    <div><dt class="text-xs font-black uppercase tracking-wide text-stone-light">Email</dt><dd class="mt-1 font-bold text-ink">{{ $order->customer_email }}</dd></div>
                    <div><dt class="text-xs font-black uppercase tracking-wide text-stone-light">Phone</dt><dd class="mt-1 font-bold text-ink">{{ $order->customer_phone }}</dd></div>
                    <div><dt class="text-xs font-black uppercase tracking-wide text-stone-light">City</dt><dd class="mt-1 font-bold text-ink">{{ $order->shipping_city }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs font-black uppercase tracking-wide text-stone-light">Address</dt><dd class="mt-1 font-bold text-ink">{{ $order->shipping_address }}</dd></div>
                    @if ($order->notes)
                        <div class="sm:col-span-2"><dt class="text-xs font-black uppercase tracking-wide text-stone-light">Notes</dt><dd class="mt-1 font-bold text-ink">{{ $order->notes }}</dd></div>
                    @endif
                </dl>
            </div>
        </div>

        <div class="grid gap-6">
            <div class="rounded-2xl border border-line bg-cream p-6 shadow-sm">
                <h2 class="font-black text-ink">Payment</h2>
                <dl class="mt-4 grid gap-3 text-sm">
                    <div class="flex justify-between"><dt class="text-stone">Method</dt><dd class="font-bold capitalize text-ink">{{ $order->payment_method }}</dd></div>
                    <div class="flex justify-between"><dt class="text-stone">Status</dt><dd class="font-bold capitalize text-ink">{{ $order->payment_status }}</dd></div>
                    @if ($order->paid_at)
                        <div class="flex justify-between"><dt class="text-stone">Paid at</dt><dd class="font-bold text-ink">{{ $order->paid_at->format('d M Y, H:i') }}</dd></div>
                    @endif
                    @if ($order->khalti_transaction_id)
                        <div class="flex justify-between gap-3"><dt class="shrink-0 text-stone">Transaction</dt><dd class="truncate font-bold text-ink" title="{{ $order->khalti_transaction_id }}">{{ $order->khalti_transaction_id }}</dd></div>
                    @endif
                </dl>
            </div>

            <div class="rounded-2xl border border-line bg-cream p-6 shadow-sm">
                <h2 class="font-black text-ink">Update status</h2>
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="mt-4 grid gap-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="rounded-xl border border-line px-4 py-3 font-medium outline-none focus:border-accent focus:ring-2 focus:ring-accent/20">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                    <button class="motion-press rounded-full bg-accent px-5 py-3 font-black text-white hover:bg-ink">Update status</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
