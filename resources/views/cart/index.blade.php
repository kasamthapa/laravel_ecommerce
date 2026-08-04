<x-layouts.storefront title="Cart - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="motion-fade flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-[#092b83]">Your selection</p>
                <h1 class="mt-2 text-3xl font-black">Frames on hold</h1>
            </div>
            <a href="{{ route('products.index') }}" class="motion-press rounded-md border border-zinc-300 px-4 py-2 text-sm font-bold hover:border-zinc-950">Continue shopping</a>
        </div>

        @if (count($cart['items']) === 0)
            <div class="mt-8 rounded-lg border border-dashed border-zinc-300 bg-white p-10 text-center">
                <h2 class="text-2xl font-black">Your cart is empty</h2>
                <p class="mt-2 text-zinc-600">Add a frame to begin your order.</p>
            </div>
        @else
            <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_22rem]">
                <div class="grid gap-4">
                    @foreach ($cart['items'] as $key => $item)
                        <div data-motion-reveal class="motion-lift grid gap-4 rounded-lg border border-zinc-200 bg-white p-4 sm:grid-cols-[7rem_1fr_auto]">
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="h-28 w-28 rounded-md object-cover">
                            <div>
                                <a href="{{ route('products.show', $item['slug']) }}" class="text-lg font-black hover:text-emerald-700">{{ $item['name'] }}</a>
                                <p class="mt-1 text-sm text-zinc-600">Fit {{ $item['size'] ?? 'Any' }} · {{ $item['color'] ?? 'Any finish' }}</p>
                                <p class="mt-2 font-black">Rs. {{ number_format($item['price']) }}</p>
                            </div>
                            <div class="flex items-center gap-2 sm:flex-col sm:items-end">
                                <form method="POST" action="{{ route('cart.update', $key) }}" class="flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10" class="w-20 rounded-md border border-zinc-300 px-3 py-2">
                                    <button class="motion-press rounded-full bg-zinc-950 px-3 py-2 text-sm font-bold text-white">Update</button>
                                </form>
                                <form method="POST" action="{{ route('cart.destroy', $key) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-md px-3 py-2 text-sm font-bold text-red-700 hover:bg-red-50">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="h-fit rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                    <h2 class="text-xl font-black">Order summary</h2>

                    @if ($cart['coupon'])
                        <div class="mt-4 flex items-center justify-between gap-3 rounded-md bg-emerald-50 px-3 py-2.5 text-sm">
                            <span class="font-bold text-emerald-800">Coupon {{ $cart['coupon']->code }} applied</span>
                            <form method="POST" action="{{ route('cart.coupon.remove') }}">
                                @csrf
                                @method('DELETE')
                                <button class="font-bold text-emerald-800 underline-offset-2 hover:underline">Remove</button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('cart.coupon.apply') }}" class="mt-4 flex gap-2">
                            @csrf
                            <input type="text" name="code" placeholder="Promo code" class="min-w-0 flex-1 rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium uppercase outline-none focus:border-[#092b83]">
                            <button class="motion-press shrink-0 rounded-md border border-zinc-950 px-3 py-2 text-sm font-bold hover:bg-zinc-950 hover:text-white">Apply</button>
                        </form>
                    @endif

                    <dl class="mt-5 grid gap-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-zinc-600">Subtotal</dt>
                            <dd class="font-bold">Rs. {{ number_format($cart['subtotal']) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-zinc-600">Shipping</dt>
                            <dd class="font-bold">Rs. {{ number_format($cart['shipping']) }}</dd>
                        </div>
                        @if ($cart['discount'] > 0)
                            <div class="flex justify-between gap-4">
                                <dt class="text-emerald-700">Discount</dt>
                                <dd class="font-bold text-emerald-700">&minus;Rs. {{ number_format($cart['discount']) }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between gap-4 border-t border-zinc-200 pt-3 text-base">
                            <dt class="font-black">Total</dt>
                            <dd class="font-black">Rs. {{ number_format($cart['total']) }}</dd>
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
    </section>
</x-layouts.storefront>
