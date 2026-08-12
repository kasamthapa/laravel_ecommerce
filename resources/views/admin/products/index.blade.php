<x-layouts.admin title="Products - Luma Lens Admin">
    <x-admin.page-header title="Products" :subtitle="$products->total().' frames in the catalog.'">
        <x-slot:actions>
            <a href="{{ route('admin.products.create') }}" class="motion-press inline-flex items-center gap-2 rounded-full bg-accent px-5 py-3 text-sm font-black text-white hover:bg-ink">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                Add product
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-cream shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line bg-cream-dim/60 text-xs font-black uppercase tracking-wide text-stone">
                        <th class="px-6 py-3">Product</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Price</th>
                        <th class="px-6 py-3">Stock</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-dim">
                    @forelse ($products as $product)
                        <tr class="transition hover:bg-cream-dim">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-11 w-11 rounded-xl object-cover shadow-sm">
                                    <span class="font-bold text-ink">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-stone">{{ $product->category->name }}</td>
                            <td class="px-6 py-3.5 font-medium text-ink-soft">Rs. {{ number_format((float) $product->price) }}</td>
                            <td class="px-6 py-3.5 {{ $product->stock <= 5 ? 'font-black text-[#e25822]' : 'text-ink-soft' }}">{{ $product->stock }}</td>
                            <td class="px-6 py-3.5">
                                @if (! $product->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-cream-dim px-3 py-1 text-xs font-black uppercase text-stone"><span class="h-1.5 w-1.5 rounded-full bg-stone-light"></span>Hidden</span>
                                @elseif ($product->stock < 1)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-error-tint px-3 py-1 text-xs font-black uppercase text-error"><span class="h-1.5 w-1.5 rounded-full bg-error"></span>Sold out</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-success-tint px-3 py-1 text-xs font-black uppercase text-success"><span class="h-1.5 w-1.5 rounded-full bg-success"></span>Live</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.products.edit', $product) }}" aria-label="Edit {{ $product->name }}" class="grid h-9 w-9 place-items-center rounded-lg text-stone hover:bg-accent-tint hover:text-accent">
                                        <svg class="h-[1.125rem] w-[1.125rem]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M16.5 3.5 20.5 7.5 8 20H4v-4L16.5 3.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete {{ $product->name }}? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button aria-label="Delete {{ $product->name }}" class="grid h-9 w-9 place-items-center rounded-lg text-stone hover:bg-error-tint hover:text-error">
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
                            <td colspan="6" class="px-6 py-10 text-center text-stone">No products yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</x-layouts.admin>
