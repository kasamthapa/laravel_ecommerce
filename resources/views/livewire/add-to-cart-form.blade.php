<div class="motion-glow grid gap-5 rounded-lg border border-zinc-950/10 bg-white p-5 shadow-sm">
    @if ($added)
        <div class="rounded-lg bg-emerald-50 p-4 text-sm leading-6 text-emerald-800">
            <p class="font-black">Added to your cart.</p>
            <div class="mt-3 flex flex-wrap gap-3">
                <a href="{{ route('cart.index') }}" class="motion-press rounded-full bg-emerald-700 px-4 py-2 text-xs font-black uppercase text-white hover:bg-emerald-800">View cart</a>
                <button type="button" wire:click="$set('added', false)" class="text-xs font-black uppercase text-emerald-800 underline-offset-2 hover:underline">Add another</button>
            </div>
        </div>
    @else
        <form wire:submit="addToCart" class="grid gap-5">
            <div class="rounded-lg bg-[#eef7fb] p-4 text-sm leading-6 text-zinc-700">
                <p class="font-black text-[#092b83]">Select lenses and buy</p>
                <p class="mt-1">Choose your fit and finish here. You will review cart totals before Khalti checkout.</p>
            </div>
            @if ($product->stock <= 5)
                <p class="-mt-2 text-sm font-bold text-[#e25822]">Only {{ $product->stock }} left in stock &mdash; order soon.</p>
            @endif
            <div class="grid gap-4 sm:grid-cols-3">
                <label class="grid gap-2 text-sm font-bold">
                    Fit
                    <select wire:model="size" class="rounded-full border border-zinc-300 px-4 py-3 font-medium">
                        @foreach ($product->sizes as $sizeOption)
                            <option value="{{ $sizeOption }}">{{ $sizeOption }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-bold">
                    Finish
                    <select wire:model="color" class="rounded-full border border-zinc-300 px-4 py-3 font-medium">
                        @foreach ($product->colors as $colorOption)
                            <option value="{{ $colorOption }}">{{ $colorOption }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-bold">
                    Qty
                    <input type="number" wire:model="quantity" min="1" max="{{ min(10, $product->stock) }}" class="rounded-full border border-zinc-300 px-4 py-3 font-medium">
                </label>
            </div>

            @error('quantity')
                <div class="rounded-md bg-red-50 p-3 text-sm font-medium text-red-700">{{ $message }}</div>
            @enderror

            <button type="submit" wire:loading.attr="disabled" class="motion-press rounded-full bg-[#092b83] px-5 py-3 font-black text-white hover:bg-zinc-950 disabled:opacity-60">
                <span wire:loading.remove wire:target="addToCart">Select lenses and buy</span>
                <span wire:loading wire:target="addToCart">Adding&hellip;</span>
            </button>
            <p class="text-center text-xs font-medium text-zinc-500">Checkout is available after login and payment is confirmed through Khalti.</p>
        </form>
    @endif
</div>
