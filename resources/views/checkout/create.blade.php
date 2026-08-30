<x-layouts.storefront title="Checkout - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-[75rem] px-4 py-10 sm:px-8 lg:py-14">
        {{-- Editorial kicker above the headline, not a functional label —
             same reasoning as cart's "Your selection": non-interactive,
             pure voice-setting text, so it gets the eyebrow treatment. --}}
        <p class="font-eyebrow text-[1.05rem] italic text-smoke">Checkout</p>
        <h1 class="mt-3 font-display font-semibold text-3xl text-bone sm:text-4xl">Where should we send your frames?</h1>

        {{-- All three steps render open/expanded simultaneously (see the
             <details open> below) — this is an orientation strip, not a
             real multi-step selector, so nothing here is ever "not yet
             reached". Was bg-volt solid fill on all three circles at once,
             which is exactly the "accent as a repeated fill instead of a
             one-CTA-per-page fill" bug the shared rule warns about. Thin
             accent border instead, matching every other selected/active
             indicator on the site. --}}
        <ol class="mt-8 flex items-center gap-3 text-xs font-medium uppercase tracking-wide text-smoke" aria-label="Checkout progress">
            <li class="flex items-center gap-2 text-bone"><span class="grid h-6 w-6 place-items-center rounded-full border border-volt text-volt">1</span> Contact</li>
            <li class="h-px w-8 bg-hairline" aria-hidden="true"></li>
            <li class="flex items-center gap-2 text-bone"><span class="grid h-6 w-6 place-items-center rounded-full border border-volt text-volt">2</span> Shipping</li>
            <li class="h-px w-8 bg-hairline" aria-hidden="true"></li>
            <li class="flex items-center gap-2 text-bone"><span class="grid h-6 w-6 place-items-center rounded-full border border-volt text-volt">3</span> Payment</li>
        </ol>

        <div class="mt-10 grid gap-12 lg:grid-cols-[1fr_22rem] lg:items-start">
            <form method="POST" action="{{ route('checkout.store') }}" data-loading-form class="grid gap-0">
                @csrf

                <details open class="border-b border-hairline py-6">
                    <summary class="cursor-pointer"><h2 class="inline font-display font-semibold text-xl text-bone">1. Contact information</h2></summary>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <x-ui.input label="Full name" name="customer_name" required />
                        <x-ui.input label="Email" name="customer_email" type="email" required />
                        <x-ui.input label="Phone" name="customer_phone" required />
                    </div>
                </details>

                <details open class="border-b border-hairline py-6">
                    <summary class="cursor-pointer"><h2 class="inline font-display font-semibold text-xl text-bone">2. Shipping address</h2></summary>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <x-ui.input label="City" name="shipping_city" required />
                        <x-ui.input label="Address" name="shipping_address" required />
                    </div>
                </details>

                <details open class="py-6">
                    <summary class="cursor-pointer"><h2 class="inline font-display font-semibold text-xl text-bone">3. Payment</h2></summary>
                    <div class="mt-6 grid gap-5">
                        <x-ui.textarea label="Order notes (optional)" name="notes" rows="3" />

                        @if ($errors->any())
                            <div class="rounded-[6px] border border-signal-bad/30 bg-black p-3 text-sm text-signal-bad">{{ $errors->first() }}</div>
                        @endif

                        {{-- The one primary CTA on this page — pill, sentence
                             case, solid accent fill, matching every other
                             primary CTA site-wide. Was hand-styled square/
                             all-caps instead of going through <x-ui.button>. --}}
                        <button type="submit" data-loading-label="Redirecting to Khalti…" class="motion-invert inline-flex w-fit items-center justify-center gap-3 rounded-full border border-volt bg-volt px-6 py-3 text-sm font-medium text-bone hover:bg-bone hover:text-black">
                            <img src="{{ asset('images/khalti_logo.svg') }}" alt="" class="h-4 w-auto">
                            Pay with Khalti
                        </button>
                        <p class="text-xs text-smoke">You will be redirected to Khalti to complete payment securely, then returned here for confirmation.</p>
                    </div>
                </details>
            </form>

            <aside class="rounded-[6px] border border-hairline p-6 lg:sticky lg:top-24">
                <h2 class="font-display font-semibold text-xl text-bone">Your order</h2>
                <div class="mt-5 grid gap-4">
                    @foreach ($cart['items'] as $item)
                        <div class="flex justify-between gap-4 text-sm">
                            <div>
                                <p class="text-bone">{{ $item['name'] }}</p>
                                <p class="text-smoke">Qty {{ $item['quantity'] }}</p>
                            </div>
                            <p class="text-bone">Rs. {{ number_format($item['price'] * $item['quantity']) }}</p>
                        </div>
                    @endforeach
                </div>
                <dl class="mt-5 grid gap-3 border-t border-hairline pt-5 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-smoke">Subtotal</dt>
                        <dd class="text-bone">Rs. {{ number_format($cart['subtotal']) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-smoke">Shipping</dt>
                        <dd class="text-bone">Rs. {{ number_format($cart['shipping']) }}</dd>
                    </div>
                    @if ($cart['discount'] > 0)
                        <div class="flex justify-between gap-4">
                            <dt class="text-signal-good">Discount ({{ $cart['coupon']->code }})</dt>
                            <dd class="text-signal-good">&minus;Rs. {{ number_format($cart['discount']) }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between gap-4 border-t border-hairline pt-3 text-base">
                        <dt class="font-medium text-bone">Total</dt>
                        <dd class="font-medium text-bone">Rs. {{ number_format($cart['total']) }}</dd>
                    </div>
                </dl>
                <div class="mt-5 border-t border-hairline pt-5 text-sm text-smoke">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-xs font-medium uppercase tracking-wide text-smoke">Payment method</span>
                        <img src="{{ asset('images/khalti_logo.svg') }}" alt="Khalti" class="h-5 w-auto">
                    </div>
                    <p class="mt-3">Orders are confirmed automatically once Khalti verifies your payment.</p>
                </div>
            </aside>
        </div>
    </section>
</x-layouts.storefront>
