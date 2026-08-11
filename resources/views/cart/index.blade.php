<x-layouts.storefront title="Cart - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-[75rem] px-4 py-10 sm:px-8 lg:py-14">
        <div class="motion-fade flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Your selection</p>
                <h1 class="mt-3 font-serif text-3xl text-ink sm:text-4xl">Frames on hold</h1>
            </div>
            <a href="{{ route('shop') }}" class="motion-press text-sm font-medium text-ink hover:opacity-70">Continue shopping</a>
        </div>

        <livewire:cart />
    </section>
</x-layouts.storefront>
