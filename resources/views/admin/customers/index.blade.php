<x-layouts.admin title="Customers - Luma Lens Admin">
    <x-admin.page-header title="Customers" :subtitle="$customers->total().' registered shoppers.'" />

    <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-cream shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line bg-cream-dim/60 text-xs font-black uppercase tracking-wide text-stone">
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Orders</th>
                        <th class="px-6 py-3">Total spent</th>
                        <th class="px-6 py-3">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-dim">
                    @forelse ($customers as $customer)
                        <tr class="transition hover:bg-cream-dim">
                            <td class="px-6 py-3.5 font-bold"><a href="{{ route('admin.customers.show', $customer) }}" class="hover:text-accent">{{ $customer->name }}</a></td>
                            <td class="px-6 py-3.5 text-stone">{{ $customer->email }}</td>
                            <td class="px-6 py-3.5 text-ink-soft">{{ $customer->orders_count }}</td>
                            <td class="px-6 py-3.5 font-bold text-ink">Rs. {{ number_format((float) ($customer->total_spent ?? 0)) }}</td>
                            <td class="px-6 py-3.5 text-stone">{{ $customer->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-stone">No customers yet.</td>
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
