<div>
    @if ($added)
        <div class="flex items-start gap-3 border border-hairline bg-black p-5 text-sm text-signal-good">
            <svg class="motion-pop mt-0.5 h-6 w-6 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.6" />
                <path d="M8 12.5l2.5 2.5L16 9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div>
                <p class="font-medium">Added to your cart.</p>
                <div class="mt-3 flex flex-wrap items-center gap-4">
                    <x-ui.button :href="route('cart.index')" size="sm">View cart</x-ui.button>
                    <button type="button" wire:click="$set('added', false)" class="motion-invert text-xs font-medium uppercase tracking-wide text-signal-good underline-offset-2 hover:underline">Add another</button>
                </div>
            </div>
        </div>
    @else
        <form wire:submit="addToCart" class="grid gap-6">
            @if (count($product->sizes) > 1)
                <div>
                    <p class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Fit</p>
                    <div class="mt-2 flex flex-wrap gap-2" role="radiogroup" aria-label="Fit">
                        @foreach ($product->sizes as $sizeOption)
                            <button type="button" wire:click="$set('size', '{{ $sizeOption }}')" role="radio" aria-checked="{{ $size === $sizeOption ? 'true' : 'false' }}" class="motion-invert border px-4 py-2 text-sm {{ $size === $sizeOption ? 'border-volt text-bone' : 'border-hairline text-smoke hover:border-volt hover:text-bone' }}">
                                {{ $sizeOption }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (count($product->colors) > 1)
                <div>
                    <p class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Finish</p>
                    <div class="mt-2 flex flex-wrap gap-2" role="radiogroup" aria-label="Finish">
                        @foreach ($product->colors as $colorOption)
                            <button type="button" wire:click="$set('color', '{{ $colorOption }}')" role="radio" aria-checked="{{ $color === $colorOption ? 'true' : 'false' }}" class="motion-invert border px-4 py-2 text-sm {{ $color === $colorOption ? 'border-volt text-bone' : 'border-hairline text-smoke hover:border-volt hover:text-bone' }}">
                                {{ $colorOption }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($product->requires_prescription)
                <div>
                    {{-- A functional form-section heading, not an editorial
                         kicker — plain weight, not the Playfair italic
                         eyebrow treatment (matches "Fit"/"Finish" above). --}}
                    <p class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Prescription</p>
                    <div class="mt-2 flex flex-wrap gap-2" role="radiogroup" aria-label="Prescription">
                        <button type="button" wire:click="$set('prescriptionStatus', 'provided')" role="radio" aria-checked="{{ $prescriptionStatus === 'provided' ? 'true' : 'false' }}" class="motion-invert border px-4 py-2 text-sm {{ $prescriptionStatus === 'provided' ? 'border-volt text-bone' : 'border-hairline text-smoke hover:border-volt hover:text-bone' }}">
                            I have my prescription
                        </button>
                        <button type="button" wire:click="$set('prescriptionStatus', 'later')" role="radio" aria-checked="{{ $prescriptionStatus === 'later' ? 'true' : 'false' }}" class="motion-invert border px-4 py-2 text-sm {{ $prescriptionStatus === 'later' ? 'border-volt text-bone' : 'border-hairline text-smoke hover:border-volt hover:text-bone' }}">
                            I'll send it later
                        </button>
                    </div>
                    @error('prescriptionStatus')
                        <p class="mt-2 text-sm text-signal-bad">{{ $message }}</p>
                    @enderror

                    @if ($prescriptionStatus === 'provided')
                        <div class="mt-4 grid gap-5 rounded-[6px] border border-hairline p-5">
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div class="grid gap-4">
                                    <p class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Right eye (OD)</p>
                                    <x-ui.input label="SPH" wire:model="sphRight" name="sphRight" type="number" step="0.25" required />
                                    <x-ui.input label="CYL" wire:model="cylRight" name="cylRight" type="number" step="0.25" hint="Optional — leave blank without astigmatism correction." />
                                    <x-ui.input label="Axis" wire:model="axisRight" name="axisRight" type="number" min="0" max="180" />
                                </div>
                                <div class="grid gap-4">
                                    <p class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Left eye (OS)</p>
                                    <x-ui.input label="SPH" wire:model="sphLeft" name="sphLeft" type="number" step="0.25" required />
                                    <x-ui.input label="CYL" wire:model="cylLeft" name="cylLeft" type="number" step="0.25" hint="Optional — leave blank without astigmatism correction." />
                                    <x-ui.input label="Axis" wire:model="axisLeft" name="axisLeft" type="number" min="0" max="180" />
                                </div>
                            </div>
                            <x-ui.input label="Pupillary distance (PD)" wire:model="pd" name="pd" type="number" step="0.5" required hint="A single measurement for both eyes, in millimeters (roughly 50–75)." />
                        </div>
                    @elseif ($prescriptionStatus === 'later')
                        <p class="mt-3 text-sm text-smoke">No problem — we'll email you to collect your prescription details before your order ships.</p>
                    @endif
                </div>
            @endif

            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-smoke">Quantity</p>
                <div class="mt-2 flex items-center gap-3">
                    <button type="button" wire:click="$set('quantity', {{ max(1, $quantity - 1) }})" class="motion-invert grid h-9 w-9 place-items-center border border-hairline text-bone" aria-label="Decrease quantity">&minus;</button>
                    <span class="w-6 text-center text-bone" aria-live="polite">{{ $quantity }}</span>
                    <button type="button" wire:click="$set('quantity', {{ min(10, $product->stock, $quantity + 1) }})" class="motion-invert grid h-9 w-9 place-items-center border border-hairline text-bone" aria-label="Increase quantity">+</button>
                </div>
            </div>

            @error('quantity')
                <p class="text-sm text-signal-bad">{{ $message }}</p>
            @enderror

            <div class="flex flex-wrap gap-3">
                <x-ui.button type="submit" size="lg" wire:loading.attr="disabled" class="disabled:opacity-60">
                    <span wire:loading.remove wire:target="addToCart">Add to cart</span>
                    <span wire:loading wire:target="addToCart">Adding&hellip;</span>
                </x-ui.button>
            </div>
            <p class="text-xs text-smoke">Checkout is available after login and payment is confirmed through Khalti.</p>
        </form>
    @endif
</div>
