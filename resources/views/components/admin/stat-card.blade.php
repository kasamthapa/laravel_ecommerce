@props(['label', 'value', 'tone' => 'default'])

@php
    $iconTone = match ($tone) {
        'warning' => 'bg-[#fdf0e8] text-[#e25822]',
        'success' => 'bg-emerald-50 text-emerald-600',
        default => 'bg-[#eef1fb] text-[#092b83]',
    };
@endphp

<div class="motion-lift rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl {{ $iconTone }}">
            {{ $icon ?? '' }}
        </div>
        <p class="text-xs font-black uppercase tracking-wide text-zinc-500">{{ $label }}</p>
    </div>
    <p class="mt-4 text-3xl font-black tracking-tight text-zinc-950">{{ $value }}</p>
</div>
