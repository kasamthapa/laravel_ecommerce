<x-layouts.admin title="Customers - Luma Lens Admin">
    <x-admin.page-header title="Customers" :subtitle="$customers->total().' registered shoppers.'" />

    <div class="mt-6 overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50/60 text-xs font-black uppercase tracking-wide text-zinc-500">
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Orders</th>
                        <th class="px-6 py-3">Total spent</th>
                        <th class="px-6 py-3">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($customers as $customer)
                        <tr class="transition hover:bg-zinc-50">
                            <td class="px-6 py-3.5 font-bold"><a href="{{ route('admin.customers.show', $customer) }}" class="hover:text-[#092b83]">{{ $customer->name }}</a></td>
                            <td class="px-6 py-3.5 text-zinc-600">{{ $customer->email }}</td>
                            <td class="px-6 py-3.5 text-zinc-700">{{ $customer->orders_count }}</td>
                            <td class="px-6 py-3.5 font-bold text-zinc-950">Rs. {{ number_format((float) ($customer->total_spent ?? 0)) }}</td>
                            <td class="px-6 py-3.5 text-zinc-500">{{ $customer->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-zinc-500">No customers yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $customers->links() }}
    </div>
</x-layouts.admin>
