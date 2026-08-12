@props(['label', 'value', 'tone' => 'default'])

@php
    $iconTone = match ($tone) {
        'warning' => 'bg-error-tint text-error',
        'success' => 'bg-success-tint text-success',
        default => 'bg-accent-tint text-accent',
    };
@endphp

<div class="rounded-2xl border border-line bg-cream p-5 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl {{ $iconTone }}">
            {{ $icon ?? '' }}
        </div>
        <p class="text-xs font-medium uppercase tracking-wide text-stone">{{ $label }}</p>
    </div>
    <p class="mt-4 font-serif text-3xl text-ink">{{ $value }}</p>
</div>
