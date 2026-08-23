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
            @if (count($product->sizes) > 1)
                <div>
                    <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Fit</p>
                    <div class="mt-2 flex flex-wrap gap-2" role="radiogroup" aria-label="Fit">
                        @foreach ($product->sizes as $sizeOption)
                            <button type="button" wire:click="$set('size', '{{ $sizeOption }}')" role="radio" aria-checked="{{ $size === $sizeOption ? 'true' : 'false' }}" class="motion-press border px-4 py-2 text-sm {{ $size === $sizeOption ? 'border-ink text-ink' : 'border-line text-stone hover:border-ink hover:text-ink' }}">
                                {{ $sizeOption }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (count($product->colors) > 1)
                <div>
                    <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Finish</p>
                    <div class="mt-2 flex flex-wrap gap-2" role="radiogroup" aria-label="Finish">
                        @foreach ($product->colors as $colorOption)
                            <button type="button" wire:click="$set('color', '{{ $colorOption }}')" role="radio" aria-checked="{{ $color === $colorOption ? 'true' : 'false' }}" class="motion-press border px-4 py-2 text-sm {{ $color === $colorOption ? 'border-ink text-ink' : 'border-line text-stone hover:border-ink hover:text-ink' }}">
                                {{ $colorOption }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Quantity</p>
                <div class="mt-2 flex items-center gap-3">
                    <button type="button" wire:click="$set('quantity', {{ max(1, $quantity - 1) }})" class="motion-press grid h-9 w-9 place-items-center border border-line text-ink" aria-label="Decrease quantity">&minus;</button>
                    <span class="w-6 text-center text-ink" aria-live="polite">{{ $quantity }}</span>
                    <button type="button" wire:click="$set('quantity', {{ min(10, $product->stock, $quantity + 1) }})" class="motion-press grid h-9 w-9 place-items-center border border-line text-ink" aria-label="Increase quantity">+</button>
                </div>
            </div>

            @error('quantity')
                <p class="text-sm text-error">{{ $message }}</p>
            @enderror

            <div class="flex flex-wrap gap-3">
                <x-ui.button type="submit" size="lg" wire:loading.attr="disabled" class="disabled:opacity-60">
                    <span wire:loading.remove wire:target="addToCart">Add to cart</span>
                    <span wire:loading wire:target="addToCart">Adding&hellip;</span>
                </x-ui.button>
            </div>
            <p class="text-xs text-stone">Checkout is available after login and payment is confirmed through Khalti.</p>
        </form>
    @endif
</div>
