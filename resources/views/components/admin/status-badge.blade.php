@props(['status'])

@php
    $tone = match ($status) {
        'delivered', 'confirmed' => ['border-success/30 bg-success-tint text-success', 'bg-success'],
        'shipped' => ['border-line bg-cream-dim text-ink', 'bg-ink'],
        'cancelled', 'payment_failed' => ['border-error/30 bg-error-tint text-error', 'bg-error'],
        default => ['border-line bg-cream-dim text-stone', 'bg-stone'],
    };
    [$badgeTone, $dotTone] = $tone;
@endphp

<span {{ $attributes->merge(['class' => "inline-flex w-fit items-center gap-1.5 rounded-sm border px-3 py-1 text-xs font-medium uppercase tracking-wide {$badgeTone}"]) }}>
    <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $dotTone }}"></span>
    {{ str_replace('_', ' ', $status) }}
</span>
