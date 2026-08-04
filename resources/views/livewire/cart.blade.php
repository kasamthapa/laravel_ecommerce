<div>
    @if (count($this->cart['items']) === 0)
        <div class="mt-8 rounded-lg border border-dashed border-zinc-300 bg-white p-10 text-center">
            <h2 class="text-2xl font-black">Your cart is empty</h2>
            <p class="mt-2 text-zinc-600">Add a frame to begin your order.</p>
        </div>
    @else
        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_22rem]">
            <div class="grid gap-4" wire:loading.class="opacity-60">
                @foreach ($this->cart['items'] as $key => $item)
                    <div wire:key="cart-item-{{ $key }}" class="motion-lift grid gap-4 rounded-lg border border-zinc-200 bg-white p-4 sm:grid-cols-[7rem_1fr_auto]">
                        <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="h-28 w-28 rounded-md object-cover">
                        <div>
                            <a href="{{ route('products.show', $item['slug']) }}" class="text-lg font-black hover:text-emerald-700">{{ $item['name'] }}</a>
                            <p class="mt-1 text-sm text-zinc-600">Fit {{ $item['size'] ?? 'Any' }} &middot; {{ $item['color'] ?? 'Any finish' }}</p>
                            <p class="mt-2 font-black">Rs. {{ number_format($item['price']) }}</p>
                        </div>
                        <div class="flex items-center gap-2 sm:flex-col sm:items-end">
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})" class="grid h-9 w-9 place-items-center rounded-full border border-zinc-300 font-black hover:border-zinc-950" aria-label="Decrease quantity">&minus;</button>
                                <span class="w-8 text-center font-black">{{ $item['quantity'] }}</span>
                                <button type="button" wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})" class="grid h-9 w-9 place-items-center rounded-full border border-zinc-300 font-black hover:border-zinc-950" aria-label="Increase quantity">+</button>
                            </div>
                            <button type="button" wire:click="removeItem('{{ $key }}')" wire:confirm="Remove this item from your cart?" class="rounded-md px-3 py-2 text-sm font-bold text-red-700 hover:bg-red-50">Remove</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <aside class="h-fit rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-black">Order summary</h2>

                @if ($this->cart['coupon'])
                    <div class="mt-4 flex items-center justify-between gap-3 rounded-md bg-emerald-50 px-3 py-2.5 text-sm">
                        <span class="font-bold text-emerald-800">Coupon {{ $this->cart['coupon']->code }} applied</span>
                        <button type="button" wire:click="removeCoupon" class="font-bold text-emerald-800 underline-offset-2 hover:underline">Remove</button>
                    </div>
                @else
                    <form wire:submit="applyCoupon" class="mt-4 flex gap-2">
                        <input type="text" wire:model="couponCode" placeholder="Promo code" class="min-w-0 flex-1 rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium uppercase outline-none focus:border-[#092b83]">
                        <button type="submit" class="motion-press shrink-0 rounded-md border border-zinc-950 px-3 py-2 text-sm font-bold hover:bg-zinc-950 hover:text-white">Apply</button>
                    </form>
                @endif

                @if ($flash)
                    <p class="mt-2 text-xs font-bold text-zinc-500">{{ $flash }}</p>
                @endif

                <dl class="mt-5 grid gap-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-zinc-600">Subtotal</dt>
                        <dd class="font-bold">Rs. {{ number_format($this->cart['subtotal']) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-zinc-600">Shipping</dt>
                        <dd class="font-bold">Rs. {{ number_format($this->cart['shipping']) }}</dd>
                    </div>
                    @if ($this->cart['discount'] > 0)
                        <div class="flex justify-between gap-4">
                            <dt class="text-emerald-700">Discount</dt>
                            <dd class="font-bold text-emerald-700">&minus;Rs. {{ number_format($this->cart['discount']) }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between gap-4 border-t border-zinc-200 pt-3 text-base">
                        <dt class="font-black">Total</dt>
                        <dd class="font-black">Rs. {{ number_format($this->cart['total']) }}</dd>
                    </div>
                </dl>
                @auth
                    <a href="{{ route('checkout.create') }}" class="motion-press mt-6 block rounded-full bg-[#092b83] px-5 py-3 text-center font-black text-white hover:bg-zinc-950">Checkout</a>
                @else
                    <a href="{{ route('login') }}" class="motion-press mt-6 block rounded-full bg-[#092b83] px-5 py-3 text-center font-black text-white hover:bg-zinc-950">Login to checkout</a>
                    <p class="mt-3 text-center text-xs font-medium text-zinc-500">Create an account or sign in before placing an order.</p>
                @endauth
            </aside>
        </div>
    @endif
</div>
