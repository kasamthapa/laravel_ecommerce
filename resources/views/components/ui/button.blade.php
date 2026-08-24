@props(['variant' => 'primary', 'href' => null, 'type' => 'button', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'px-4 py-2 text-xs',
        'md' => 'px-6 py-3 text-sm',
        'lg' => 'px-8 py-4 text-sm',
    ];

    $variants = [
        'primary' => 'border border-volt bg-volt text-bone hover:bg-bone hover:text-black',
        'secondary' => 'border border-bone bg-transparent text-bone hover:bg-bone hover:text-black',
        'ghost' => 'border-0 bg-transparent p-0 text-bone underline decoration-1 underline-offset-4 hover:text-volt',
    ];

    $base = 'motion-invert inline-flex items-center justify-center gap-2 rounded-sm font-medium uppercase tracking-wide disabled:opacity-50 disabled:pointer-events-none';
    $classes = trim($base.' '.($variants[$variant] ?? $variants['primary']).' '.($variant === 'ghost' ? '' : ($sizes[$size] ?? $sizes['md'])));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
