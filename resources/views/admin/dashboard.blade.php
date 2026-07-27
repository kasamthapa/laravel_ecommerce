<x-layouts.admin title="Dashboard - Luma Lens Admin">
    <x-admin.page-header title="Dashboard" subtitle="A quick snapshot of orders, revenue, and stock." />

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-admin.stat-card label="Total orders" :value="$totalOrders">
            <x-slot:icon>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M6 3.5h12a.5.5 0 0 1 .5.5v16.2a.4.4 0 0 1-.62.33l-2.13-1.42-2.13 1.42a.4.4 0 0 1-.44 0l-2.13-1.42-2.13 1.42a.4.4 0 0 1-.44 0l-2.13-1.42L4.12 20.5A.4.4 0 0 1 3.5 20.2V4a.5.5 0 0 1 .5-.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M7.5 8h9M7.5 11.5h9M7.5 15h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                </svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="Revenue (paid)" :value="'Rs. '.number_format((float) $totalRevenue)" tone="success">
            <x-slot:icon>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 2.5v19M17 6.5H9.75a2.75 2.75 0 0 0 0 5.5h4.5a2.75 2.75 0 0 1 0 5.5H7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                </svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="Awaiting delivery" :value="$awaitingDeliveryCount">
            <x-slot:icon>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M3 7.5 12 3l9 4.5-9 4.5-9-4.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M3 7.5V16l9 4.5 9-4.5V7.5M12 12v8.5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                </svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="Low stock (≤ 5)" :value="$lowStockCount" :tone="$lowStockCount > 0 ? 'warning' : 'default'">
            <x-slot:icon>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 3.5 21 19.5H3L12 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M12 10v4M12 16.8h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                </svg>
            </x-slot:icon>
        </x-admin.stat-card>
    </div>

    <div class="mt-8 overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-zinc-200 px-6 py-4">
            <h2 class="font-black text-zinc-950">Recent orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-[#092b83] hover:underline">View all &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50/60 text-xs font-black uppercase tracking-wide text-zinc-500">
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Payment</th>
                        <th class="px-6 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($recentOrders as $order)
                        <tr class="transition hover:bg-zinc-50">
                            <td class="px-6 py-3.5 font-bold"><a href="{{ route('admin.orders.show', $order) }}" class="hover:text-[#092b83]">{{ $order->order_number }}</a></td>
                            <td class="px-6 py-3.5 text-zinc-700">{{ $order->customer_name }}</td>
                            <td class="px-6 py-3.5"><x-admin.status-badge :status="$order->status" /></td>
                            <td class="px-6 py-3.5 capitalize text-zinc-700">{{ $order->payment_status }}</td>
                            <td class="px-6 py-3.5 text-right font-bold">Rs. {{ number_format((float) $order->total) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-zinc-500">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
