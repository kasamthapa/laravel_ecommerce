@php
    $steps = ['confirmed', 'shipped', 'delivered'];
    $currentStepIndex = $order ? array_search($order->status, $steps, true) : false;
@endphp

<x-layouts.storefront title="Track Order - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-bold uppercase text-[#092b83]">Order status</p>
        <h1 class="mt-2 text-3xl font-black">Track your order</h1>
        <p class="mt-3 text-zinc-600">Enter your order number and the email used at checkout.</p>

        <form method="POST" action="{{ route('track.show') }}" class="mt-8 grid gap-4 rounded-lg border border-zinc-200 bg-white p-5 sm:grid-cols-[1fr_1fr_auto]">
            @csrf
            <label class="grid gap-2 text-sm font-bold">
                Order number
                <input name="order_number" value="{{ old('order_number') }}" placeholder="LUM-260101-ABC123" required class="rounded-md border border-zinc-300 px-3 py-3 font-medium uppercase">
            </label>
            <label class="grid gap-2 text-sm font-bold">
                Email
                <input type="email" name="email" value="{{ old('email') }}" required class="rounded-md border border-zinc-300 px-3 py-3 font-medium">
            </label>
            <button class="motion-press self-end rounded-full bg-[#092b83] px-6 py-3 font-black text-white hover:bg-zinc-950">Track</button>
        </form>

        @if ($errors->any())
            <div class="mt-4 rounded-md bg-red-50 p-3 text-sm font-medium text-red-700">{{ $errors->first() }}</div>
        @endif

        @isset($notFound)
            @if ($notFound)
                <div class="mt-6 rounded-lg border border-dashed border-zinc-300 bg-white p-6 text-center text-zinc-600">
                    No order matched that order number and email. Double-check both and try again.
                </div>
            @endif
        @endisset

        @if ($order)
            <div class="mt-8 rounded-lg border border-zinc-200 bg-white p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="font-black text-zinc-950">{{ $order->order_number }}</p>
                        <p class="text-sm text-zinc-500">Placed {{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <x-admin.status-badge :status="$order->status" />
                </div>

                @if ($currentStepIndex !== false)
                    <div class="mt-8 grid grid-cols-3 gap-2">
                        @foreach ($steps as $index => $step)
                            <div class="text-center">
                                <div class="mx-auto grid h-10 w-10 place-items-center rounded-full border-2 text-sm font-black {{ $index <= $currentStepIndex ? 'border-[#092b83] bg-[#092b83] text-white' : 'border-zinc-300 text-zinc-400' }}">
                                    {{ $index + 1 }}
                                </div>
                                <p class="mt-2 text-xs font-black uppercase {{ $index <= $currentStepIndex ? 'text-[#092b83]' : 'text-zinc-400' }}">{{ ucfirst($step) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-6 text-sm font-bold text-zinc-600">Current status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                @endif

                <div class="mt-8 grid gap-3">
                    @foreach ($order->orderItems as $item)
                        <div class="flex justify-between gap-4 border-b border-zinc-100 pb-3 text-sm last:border-0">
                            <span class="font-bold">{{ $item->product_name }} &times; {{ $item->quantity }}</span>
                            <span class="font-bold">Rs. {{ number_format((float) $item->line_total) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</x-layouts.storefront>
