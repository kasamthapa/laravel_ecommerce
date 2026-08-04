<x-layouts.admin :title="$customer->name.' - Luma Lens Admin'">
    <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#092b83] hover:underline">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        Back to customers
    </a>

    <div class="mt-3 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-zinc-950">{{ $customer->name }}</h1>
            <p class="mt-1 text-sm text-zinc-500">{{ $customer->email }} &middot; Joined {{ $customer->created_at->format('d M Y') }}</p>
        </div>
        @if ($customer->is_admin)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#eef1fb] px-3 py-1 text-xs font-black uppercase text-[#092b83]">Admin</span>
        @endif
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm">
        <div class="border-b border-zinc-200 px-6 py-4">
            <h2 class="font-black text-zinc-950">Orders</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50/60 text-xs font-black uppercase tracking-wide text-zinc-500">
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Placed</th>
                        <th class="px-6 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($orders as $order)
                        <tr class="transition hover:bg-zinc-50">
                            <td class="px-6 py-3.5 font-bold"><a href="{{ route('admin.orders.show', $order) }}" class="hover:text-[#092b83]">{{ $order->order_number }}</a></td>
                            <td class="px-6 py-3.5"><x-admin.status-badge :status="$order->status" /></td>
                            <td class="px-6 py-3.5 text-zinc-500">{{ $order->created_at->format('d M, H:i') }}</td>
                            <td class="px-6 py-3.5 text-right font-bold">Rs. {{ number_format((float) $order->total) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-zinc-500">No orders yet.</td>
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
