@props(['variant' => 'primary', 'href' => null, 'type' => 'button', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'px-4 py-2 text-xs',
        'md' => 'px-6 py-3 text-sm',
        'lg' => 'px-8 py-4 text-sm',
    ];

    $variants = [
        'primary' => 'border border-accent bg-accent text-cream',
        'secondary' => 'border border-ink bg-transparent text-ink',
        'ghost' => 'border-0 bg-transparent p-0 text-ink underline decoration-1 underline-offset-4',
    ];

    $base = 'motion-press inline-flex items-center justify-center gap-2 rounded-sm font-medium tracking-wide disabled:opacity-50 disabled:pointer-events-none';
    $classes = trim($base.' '.($variants[$variant] ?? $variants['primary']).' '.($variant === 'ghost' ? '' : ($sizes[$size] ?? $sizes['md'])));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
