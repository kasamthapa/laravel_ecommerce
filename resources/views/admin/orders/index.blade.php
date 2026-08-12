<x-layouts.admin title="Orders - Luma Lens Admin">
    <x-admin.page-header title="Orders" :subtitle="$orders->total().' orders placed.'" />

    <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-cream shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line bg-cream-dim/60 text-xs font-black uppercase tracking-wide text-stone">
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Payment</th>
                        <th class="px-6 py-3">Placed</th>
                        <th class="px-6 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-dim">
                    @forelse ($orders as $order)
                        <tr class="transition hover:bg-cream-dim">
                            <td class="px-6 py-3.5 font-bold"><a href="{{ route('admin.orders.show', $order) }}" class="hover:text-accent">{{ $order->order_number }}</a></td>
                            <td class="px-6 py-3.5 text-ink-soft">{{ $order->customer_name }}</td>
                            <td class="px-6 py-3.5"><x-admin.status-badge :status="$order->status" /></td>
                            <td class="px-6 py-3.5 capitalize text-ink-soft">{{ $order->payment_status }}</td>
                            <td class="px-6 py-3.5 text-stone">{{ $order->created_at->format('d M, H:i') }}</td>
                            <td class="px-6 py-3.5 text-right font-bold">Rs. {{ number_format((float) $order->total) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-stone">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</x-layouts.admin>
