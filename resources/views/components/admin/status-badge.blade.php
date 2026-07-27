@props(['status'])

@php
    $tone = match ($status) {
        'delivered', 'confirmed' => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'bg-emerald-500'],
        'shipped' => ['bg-blue-50 text-blue-700 border-blue-200', 'bg-blue-500'],
        'cancelled', 'payment_failed' => ['bg-red-50 text-red-700 border-red-200', 'bg-red-500'],
        default => ['bg-zinc-100 text-zinc-700 border-zinc-200', 'bg-zinc-400'],
    };
    [$badgeTone, $dotTone] = $tone;
@endphp

<span {{ $attributes->merge(['class' => "inline-flex w-fit items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-black uppercase {$badgeTone}"]) }}>
    <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $dotTone }}"></span>
    {{ str_replace('_', ' ', $status) }}
</span>
