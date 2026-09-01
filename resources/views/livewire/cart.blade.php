<div>
    @if (count($this->cart['items']) === 0)
        <div class="mt-10 rounded-[6px] border border-dashed border-hairline p-12 text-center">
            <p class="font-display font-semibold text-2xl text-bone">Your cart is empty</p>
            <p class="mt-2 text-sm text-smoke">Add a frame to begin your order.</p>
            <x-ui.button :href="route('shop')" class="mt-6">Shop the collection</x-ui.button>
        </div>
    @else
        <div class="mt-10 grid gap-12 lg:grid-cols-[1fr_22rem] lg:items-start">
            <div class="grid gap-6" wire:loading.class="opacity-60">
                @foreach ($this->cart['items'] as $key => $item)
                    <div wire:key="cart-item-{{ $key }}" class="grid gap-4 border-b border-hairline pb-6 last:border-0 sm:grid-cols-[9rem_1fr_auto]">
                        <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="h-36 w-36 bg-charcoal object-cover">
                        <div>
                            <a href="{{ route('products.show', $item['slug']) }}" class="motion-invert font-display font-semibold text-lg text-bone">{{ $item['name'] }}</a>
                            <p class="mt-1 text-sm text-smoke">Fit {{ $item['size'] ?? 'Any' }} &middot; {{ $item['color'] ?? 'Any finish' }}</p>
                            @if (isset($item['prescription']))
                                <p class="mt-1 text-sm {{ ($item['prescription']['status'] ?? null) === 'later' ? 'text-smoke' : 'text-signal-good' }}">
                                    {{ ($item['prescription']['status'] ?? null) === 'later' ? "Prescription: we'll follow up by email" : 'Prescription on file' }}
                                </p>
                            @endif
                            <p class="mt-2 text-bone">Rs. {{ number_format($item['price']) }}</p>
                        </div>
                        <div class="flex items-center gap-3 sm:flex-col sm:items-end sm:justify-between">
                            <div class="flex items-center gap-3">
                                <button type="button" wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})" class="motion-invert grid h-8 w-8 place-items-center border border-hairline text-bone" aria-label="Decrease quantity of {{ $item['name'] }}">&minus;</button>
                                <span class="w-6 text-center text-bone" aria-live="polite">{{ $item['quantity'] }}</span>
                                <button type="button" wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})" class="motion-invert grid h-8 w-8 place-items-center border border-hairline text-bone" aria-label="Increase quantity of {{ $item['name'] }}">+</button>
                            </div>
                            <button type="button" wire:click="removeItem('{{ $key }}')" wire:confirm="Remove this item from your cart?" class="motion-invert text-xs font-medium uppercase tracking-wide text-smoke hover:text-signal-bad">Remove</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <aside class="rounded-[6px] border border-hairline p-6 lg:sticky lg:top-24">
                <h2 class="font-display font-semibold text-xl text-bone">Order summary</h2>

                @if ($this->cart['coupon'])
                    <div class="mt-4 flex items-center justify-between gap-3 rounded-[6px] border border-hairline bg-black px-3 py-2.5 text-sm">
                        <span class="text-signal-good">Coupon {{ $this->cart['coupon']->code }} applied</span>
                        <button type="button" wire:click="removeCoupon" class="motion-invert font-medium text-signal-good underline-offset-2 hover:underline">Remove</button>
                    </div>
                @else
                    <form wire:submit="applyCoupon" class="mt-4 flex items-end gap-3">
                        <label class="grid flex-1 gap-1 text-xs font-medium text-smoke">
                            Promo code
                            <input type="text" wire:model="couponCode" class="w-full border-0 border-b border-hairline bg-transparent py-2 text-sm uppercase text-bone outline-none focus:border-volt">
                        </label>
                        <button type="submit" class="motion-invert shrink-0 border border-volt px-4 py-2 text-sm text-bone">Apply</button>
                    </form>
                @endif

                {{-- Was plain text-smoke regardless of outcome — an invalid
                     coupon code read identically to a successful one, no
                     error signal at all. --}}
                @if ($flash)
                    <p class="mt-2 text-xs {{ $flashType === 'error' ? 'text-signal-bad' : 'text-signal-good' }}">{{ $flash }}</p>
                @endif

                <dl class="mt-6 grid gap-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-smoke">Subtotal</dt>
                        <dd class="text-bone">Rs. {{ number_format($this->cart['subtotal']) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-smoke">Shipping</dt>
                        <dd class="text-bone">Rs. {{ number_format($this->cart['shipping']) }}</dd>
                    </div>
                    @if ($this->cart['discount'] > 0)
                        <div class="flex justify-between gap-4">
                            <dt class="text-signal-good">Discount</dt>
                            <dd class="text-signal-good">&minus;Rs. {{ number_format($this->cart['discount']) }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between gap-4 border-t border-hairline pt-3 text-base">
                        <dt class="font-medium text-bone">Total</dt>
                        <dd class="font-medium text-bone">Rs. {{ number_format($this->cart['total']) }}</dd>
                    </div>
                </dl>
                @auth
                    <x-ui.button :href="route('checkout.create')" class="mt-6 w-full">Checkout</x-ui.button>
                @else
                    <x-ui.button :href="route('login')" class="mt-6 w-full">Login to checkout</x-ui.button>
                    <p class="mt-3 text-center text-xs text-smoke">Create an account or sign in before placing an order.</p>
                @endauth

                <ul class="mt-6 grid gap-2 border-t border-hairline pt-6 text-xs text-smoke">
                    <li class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3.5 20 7v5.5c0 4.6-3.4 8.2-8 9-4.6-.8-8-4.4-8-9V7l8-3.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" /></svg>
                        Secure payment with Khalti
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12a8 8 0 1 0 8-8M4 12h4M4 12l3-3M4 12l3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        14-day returns and exchanges
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" /><circle cx="7.5" cy="18" r="1.5" fill="currentColor" /><circle cx="17.5" cy="18" r="1.5" fill="currentColor" /></svg>
                        Free shipping over Rs. 10,000, otherwise Rs. 250
                    </li>
                </ul>
            </aside>
        </div>
    @endif
</div>
