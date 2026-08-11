<div>
    @if ($added)
        <div class="border border-line bg-success-tint p-5 text-sm text-success">
            <p class="font-medium">Added to your cart.</p>
            <div class="mt-3 flex flex-wrap items-center gap-4">
                <x-ui.button :href="route('cart.index')" size="sm">View cart</x-ui.button>
                <button type="button" wire:click="$set('added', false)" class="motion-press text-xs font-medium uppercase tracking-wide text-success underline-offset-2 hover:underline">Add another</button>
            </div>
        </div>
    @else
        <form wire:submit="addToCart" class="grid gap-6">
            <div class="grid gap-4 sm:grid-cols-3">
                <x-ui.select label="Fit" wire:model="size">
                    @foreach ($product->sizes as $sizeOption)
                        <option value="{{ $sizeOption }}">{{ $sizeOption }}</option>
                    @endforeach
                </x-ui.select>
                <x-ui.select label="Finish" wire:model="color">
                    @foreach ($product->colors as $colorOption)
                        <option value="{{ $colorOption }}">{{ $colorOption }}</option>
                    @endforeach
                </x-ui.select>
                <x-ui.input label="Qty" type="number" wire:model="quantity" min="1" :max="min(10, $product->stock)" />
            </div>

            @error('quantity')
                <p class="text-sm text-error">{{ $message }}</p>
            @enderror

            <div class="flex flex-wrap gap-3">
                <x-ui.button type="submit" wire:loading.attr="disabled" class="disabled:opacity-60">
                    <span wire:loading.remove wire:target="addToCart">Add to cart</span>
                    <span wire:loading wire:target="addToCart">Adding&hellip;</span>
                </x-ui.button>
                <button type="button" disabled title="Virtual try-on is coming in a future update" class="motion-press inline-flex cursor-not-allowed items-center justify-center gap-2 border border-line px-6 py-3 text-sm font-medium tracking-wide text-stone opacity-70">
                    Try on &middot; coming soon
                </button>
            </div>
            <p class="text-xs text-stone">Checkout is available after login and payment is confirmed through Khalti.</p>
        </form>
    @endif
</div>
