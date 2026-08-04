<x-layouts.admin title="Coupons - Luma Lens Admin">
    <x-admin.page-header title="Coupons" :subtitle="$coupons->total().' coupons.'">
        <x-slot:actions>
            <a href="{{ route('admin.coupons.create') }}" class="motion-press inline-flex items-center gap-2 rounded-full bg-[#092b83] px-5 py-3 text-sm font-black text-white hover:bg-zinc-950">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                Add coupon
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="mt-6 overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50/60 text-xs font-black uppercase tracking-wide text-zinc-500">
                        <th class="px-6 py-3">Code</th>
                        <th class="px-6 py-3">Discount</th>
                        <th class="px-6 py-3">Uses</th>
                        <th class="px-6 py-3">Expires</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($coupons as $coupon)
                        <tr class="transition hover:bg-zinc-50">
                            <td class="px-6 py-3.5 font-black text-zinc-950">{{ $coupon->code }}</td>
                            <td class="px-6 py-3.5 text-zinc-700">{{ $coupon->type === 'percent' ? $coupon->value.'%' : 'Rs. '.number_format((float) $coupon->value) }}</td>
                            <td class="px-6 py-3.5 text-zinc-700">{{ $coupon->used_count }}{{ $coupon->max_uses ? ' / '.$coupon->max_uses : '' }}</td>
                            <td class="px-6 py-3.5 text-zinc-500">{{ $coupon->expires_at?->format('d M Y') ?? 'Never' }}</td>
                            <td class="px-6 py-3.5">
                                @if ($coupon->isValid())
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-black uppercase text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Active</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100 px-3 py-1 text-xs font-black uppercase text-zinc-500"><span class="h-1.5 w-1.5 rounded-full bg-zinc-400"></span>Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" aria-label="Edit {{ $coupon->code }}" class="grid h-9 w-9 place-items-center rounded-lg text-zinc-500 hover:bg-[#eef1fb] hover:text-[#092b83]">
                                        <svg class="h-[1.125rem] w-[1.125rem]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M16.5 3.5 20.5 7.5 8 20H4v-4L16.5 3.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete {{ $coupon->code }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button aria-label="Delete {{ $coupon->code }}" class="grid h-9 w-9 place-items-center rounded-lg text-zinc-500 hover:bg-red-50 hover:text-red-700">
                                            <svg class="h-[1.125rem] w-[1.125rem]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M5 7h14M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-9 0 .8 12.2a1 1 0 0 0 1 .8h6.4a1 1 0 0 0 1-.8L19 7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-zinc-500">No coupons yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $coupons->links() }}
    </div>
</x-layouts.admin>
