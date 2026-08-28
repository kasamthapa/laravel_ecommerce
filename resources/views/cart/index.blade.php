<x-layouts.storefront title="Cart - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-[75rem] px-4 py-10 sm:px-8 lg:py-14">
        <div class="motion-fade flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                {{-- Editorial kicker above the headline, not a functional/
                     navigational label (unlike the PDP's category breadcrumb,
                     which stays functional text since it's an actual link) —
                     gets the eyebrow treatment: Playfair italic, mixed case,
                     muted, matching /style-preview-light's .eyebrow exactly. --}}
                <p class="font-eyebrow text-[1.05rem] italic text-smoke">Your selection</p>
                <h1 class="mt-3 font-display font-bold uppercase text-3xl text-bone sm:text-4xl">Frames on hold</h1>
            </div>
            <a href="{{ route('shop') }}" class="motion-invert text-sm font-medium text-bone hover:text-volt">Continue shopping</a>
        </div>

        <livewire:cart />
    </section>
</x-layouts.storefront>
