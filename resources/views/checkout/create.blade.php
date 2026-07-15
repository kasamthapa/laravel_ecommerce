<x-layouts.storefront title="Checkout - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto grid max-w-6xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1fr_22rem] lg:px-8">
        <div class="motion-fade">
            <p class="text-sm font-bold uppercase text-[#092b83]">Checkout</p>
            <h1 class="mt-2 text-3xl font-black">Where should we send your frames?</h1>

            <form method="POST" action="{{ route('checkout.store') }}" class="mt-8 grid gap-5 rounded-lg border border-zinc-200 bg-white p-5">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="grid gap-2 text-sm font-bold">
                        Full name
                        <input name="customer_name" value="{{ old('customer_name') }}" class="rounded-md border border-zinc-300 px-3 py-3 font-medium" required>
                    </label>
                    <label class="grid gap-2 text-sm font-bold">
                        Email
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}" class="rounded-md border border-zinc-300 px-3 py-3 font-medium" required>
                    </label>
                    <label class="grid gap-2 text-sm font-bold">
                        Phone
                        <input name="customer_phone" value="{{ old('customer_phone') }}" class="rounded-md border border-zinc-300 px-3 py-3 font-medium" required>
                    </label>
                    <label class="grid gap-2 text-sm font-bold">
                        City
                        <input name="shipping_city" value="{{ old('shipping_city') }}" class="rounded-md border border-zinc-300 px-3 py-3 font-medium" required>
                    </label>
                </div>
                <label class="grid gap-2 text-sm font-bold">
                    Address
                    <input name="shipping_address" value="{{ old('shipping_address') }}" class="rounded-md border border-zinc-300 px-3 py-3 font-medium" required>
                </label>
                <label class="grid gap-2 text-sm font-bold">
                    Notes
                    <textarea name="notes" rows="4" class="rounded-md border border-zinc-300 px-3 py-3 font-medium">{{ old('notes') }}</textarea>
                </label>

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-3 text-sm font-medium text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button class="motion-press inline-flex items-center justify-center gap-3 rounded-full bg-zinc-950 px-5 py-3 font-black text-white hover:bg-[#092b83]">
                    <span class="grid h-7 w-16 place-items-center rounded-full bg-white px-2">
                        <img src="{{ asset('images/khalti_logo.svg') }}" alt="Khalti" class="max-h-4 w-auto">
                    </span>
                    Pay with Khalti
                </button>
            </form>
        </div>

        <aside class="motion-fade-slow motion-glow h-fit rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
            <h2 class="text-xl font-black">Your order</h2>
            <div class="mt-5 grid gap-4">
                @foreach ($cart['items'] as $item)
                    <div class="flex justify-between gap-4 text-sm">
                        <div>
                            <p class="font-bold">{{ $item['name'] }}</p>
                            <p class="text-zinc-500">Qty {{ $item['quantity'] }}</p>
                        </div>
                        <p class="font-bold">Rs. {{ number_format($item['price'] * $item['quantity']) }}</p>
                    </div>
                @endforeach
            </div>
            <dl class="mt-5 grid gap-3 border-t border-zinc-200 pt-5 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-zinc-600">Subtotal</dt>
                    <dd class="font-bold">Rs. {{ number_format($cart['subtotal']) }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-zinc-600">Shipping</dt>
                    <dd class="font-bold">Rs. {{ number_format($cart['shipping']) }}</dd>
                </div>
                <div class="flex justify-between gap-4 text-base">
                    <dt class="font-black">Total</dt>
                    <dd class="font-black">Rs. {{ number_format($cart['total']) }}</dd>
                </div>
            </dl>
            <div class="mt-5 rounded-lg border border-[#092b83]/20 bg-[#f4f8fb] p-4 text-sm text-zinc-700">
                <div class="flex items-center justify-between gap-4 border-b border-zinc-950/10 pb-4">
                    <div>
                        <p class="text-xs font-black uppercase text-[#092b83]">Payment method</p>
                        <p class="mt-1 font-black text-zinc-950">Khalti Checkout</p>
                    </div>
                    <img src="{{ asset('images/khalti_logo.svg') }}" alt="Khalti" class="h-7 w-auto">
                </div>
                <p class="mt-2">You will be redirected to Khalti to complete payment securely.</p>
                <div class="mt-4 flex items-center justify-center gap-2 rounded-md border border-zinc-200 bg-white px-3 py-2 text-xs font-bold uppercase text-zinc-500">
                    <span>Powered by</span>
                    <img src="{{ asset('images/khalti_logo.svg') }}" alt="Khalti" class="h-4 w-auto">
                </div>
            </div>
        </aside>
    </section>
</x-layouts.storefront>
