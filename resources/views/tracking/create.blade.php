@php
    $steps = ['confirmed', 'shipped', 'delivered'];
    $currentStepIndex = $order ? array_search($order->status, $steps, true) : false;
@endphp

<x-layouts.storefront title="Track Order - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-2xl px-4 py-14 sm:px-8">
        <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Order status</p>
        <h1 class="mt-3 font-serif text-3xl text-ink sm:text-4xl">Track your order</h1>
        <p class="mt-3 text-base text-stone">Enter your order number and the email used at checkout.</p>

        <form method="POST" action="{{ route('track.show') }}" data-loading-form class="mt-8 grid gap-5 border border-line p-6 sm:grid-cols-2">
            @csrf
            <x-ui.input label="Order number" name="order_number" placeholder="LUM-260101-ABC123" required class="uppercase" />
            <x-ui.input label="Email" name="email" type="email" required />
            <x-ui.button type="submit" data-loading-label="Searching…" class="w-fit sm:col-span-2">Track order</x-ui.button>
        </form>

        @if ($errors->any())
            <p class="mt-4 text-sm text-error">{{ $errors->first() }}</p>
        @endif

        @isset($notFound)
            @if ($notFound)
                <div class="mt-6 border border-dashed border-line p-6 text-center text-sm text-stone">
                    No order matched that order number and email. Double-check both and try again.
                </div>
            @endif
        @endisset

        @if ($order)
            <div class="mt-10 border border-line p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="font-serif text-lg text-ink">{{ $order->order_number }}</p>
                        <p class="text-sm text-stone">Placed {{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <x-admin.status-badge :status="$order->status" />
                </div>

                @if ($currentStepIndex !== false)
                    <div class="mt-8 grid grid-cols-3 gap-2">
                        @foreach ($steps as $index => $step)
                            <div class="text-center">
                                <div class="mx-auto grid h-9 w-9 place-items-center rounded-full border {{ $index <= $currentStepIndex ? 'border-ink bg-ink text-cream' : 'border-line text-stone' }}">
                                    {{ $index + 1 }}
                                </div>
                                <p class="mt-2 text-xs font-medium uppercase tracking-wide {{ $index <= $currentStepIndex ? 'text-ink' : 'text-stone' }}">{{ ucfirst($step) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-6 text-sm text-stone">Current status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                @endif

                <div class="mt-8 grid gap-3">
                    @foreach ($order->orderItems as $item)
                        <div class="flex justify-between gap-4 border-b border-line pb-3 text-sm last:border-0">
                            <span class="text-ink">{{ $item->product_name }} &times; {{ $item->quantity }}</span>
                            <span class="text-ink">Rs. {{ number_format((float) $item->line_total) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</x-layouts.storefront>
