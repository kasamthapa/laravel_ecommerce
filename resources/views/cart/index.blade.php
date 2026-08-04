<x-layouts.storefront title="Cart - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="motion-fade flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-[#092b83]">Your selection</p>
                <h1 class="mt-2 text-3xl font-black">Frames on hold</h1>
            </div>
            <a href="{{ route('products.index') }}" class="motion-press rounded-md border border-zinc-300 px-4 py-2 text-sm font-bold hover:border-zinc-950">Continue shopping</a>
        </div>

        <livewire:cart />
    </section>
</x-layouts.storefront>
