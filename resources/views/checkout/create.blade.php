<x-layouts.storefront title="Checkout - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-[75rem] px-4 py-10 sm:px-8 lg:py-14">
        <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Checkout</p>
        <h1 class="mt-3 font-serif text-3xl text-ink sm:text-4xl">Where should we send your frames?</h1>

        <ol class="mt-8 flex items-center gap-3 text-xs font-medium uppercase tracking-wide text-stone" aria-label="Checkout progress">
            <li class="flex items-center gap-2 text-ink"><span class="grid h-6 w-6 place-items-center rounded-full bg-ink text-cream">1</span> Contact</li>
            <li class="h-px w-8 bg-line" aria-hidden="true"></li>
            <li class="flex items-center gap-2 text-ink"><span class="grid h-6 w-6 place-items-center rounded-full bg-ink text-cream">2</span> Shipping</li>
            <li class="h-px w-8 bg-line" aria-hidden="true"></li>
            <li class="flex items-center gap-2 text-ink"><span class="grid h-6 w-6 place-items-center rounded-full bg-ink text-cream">3</span> Payment</li>
        </ol>

        <div class="mt-10 grid gap-12 lg:grid-cols-[1fr_22rem] lg:items-start">
            <form method="POST" action="{{ route('checkout.store') }}" data-loading-form class="grid gap-0">
                @csrf

                <details open class="border-b border-line py-6">
                    <summary class="cursor-pointer"><h2 class="inline font-serif text-xl text-ink">1. Contact information</h2></summary>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <x-ui.input label="Full name" name="customer_name" required />
                        <x-ui.input label="Email" name="customer_email" type="email" required />
                        <x-ui.input label="Phone" name="customer_phone" required />
                    </div>
                </details>

                <details open class="border-b border-line py-6">
                    <summary class="cursor-pointer"><h2 class="inline font-serif text-xl text-ink">2. Shipping address</h2></summary>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <x-ui.input label="City" name="shipping_city" required />
                        <x-ui.input label="Address" name="shipping_address" required />
                    </div>
                </details>

                <details open class="py-6">
                    <summary class="cursor-pointer"><h2 class="inline font-serif text-xl text-ink">3. Payment</h2></summary>
                    <div class="mt-6 grid gap-5">
                        <x-ui.textarea label="Order notes (optional)" name="notes" rows="3" />

                        @if ($errors->any())
                            <div class="border border-error/30 bg-error-tint p-3 text-sm text-error">{{ $errors->first() }}</div>
                        @endif

                        <button type="submit" data-loading-label="Redirecting to Khalti…" class="motion-press inline-flex w-fit items-center justify-center gap-3 border border-ink bg-ink px-6 py-3 text-sm font-medium tracking-wide text-cream">
                            <img src="{{ asset('images/khalti_logo.svg') }}" alt="" class="h-4 w-auto">
                            Pay with Khalti
                        </button>
                        <p class="text-xs text-stone">You will be redirected to Khalti to complete payment securely, then returned here for confirmation.</p>
                    </div>
                </details>
            </form>

            <aside class="border border-line p-6 lg:sticky lg:top-24">
                <h2 class="font-serif text-xl text-ink">Your order</h2>
                <div class="mt-5 grid gap-4">
                    @foreach ($cart['items'] as $item)
                        <div class="flex justify-between gap-4 text-sm">
                            <div>
                                <p class="text-ink">{{ $item['name'] }}</p>
                                <p class="text-stone">Qty {{ $item['quantity'] }}</p>
                            </div>
                            <p class="text-ink">Rs. {{ number_format($item['price'] * $item['quantity']) }}</p>
                        </div>
                    @endforeach
                </div>
                <dl class="mt-5 grid gap-3 border-t border-line pt-5 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-stone">Subtotal</dt>
                        <dd class="text-ink">Rs. {{ number_format($cart['subtotal']) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-stone">Shipping</dt>
                        <dd class="text-ink">Rs. {{ number_format($cart['shipping']) }}</dd>
                    </div>
                    @if ($cart['discount'] > 0)
                        <div class="flex justify-between gap-4">
                            <dt class="text-success">Discount ({{ $cart['coupon']->code }})</dt>
                            <dd class="text-success">&minus;Rs. {{ number_format($cart['discount']) }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between gap-4 border-t border-line pt-3 text-base">
                        <dt class="font-medium text-ink">Total</dt>
                        <dd class="font-medium text-ink">Rs. {{ number_format($cart['total']) }}</dd>
                    </div>
                </dl>
                <div class="mt-5 border-t border-line pt-5 text-sm text-stone">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-xs font-medium uppercase tracking-wide text-stone">Payment method</span>
                        <img src="{{ asset('images/khalti_logo.svg') }}" alt="Khalti" class="h-5 w-auto">
                    </div>
                    <p class="mt-3">Orders are confirmed automatically once Khalti verifies your payment.</p>
                </div>
            </aside>
        </div>
    </section>
</x-layouts.storefront>
