@props(['rating' => 0, 'count' => null, 'size' => 'h-4 w-4'])

@php
    $rounded = round((float) $rating);
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-1.5']) }}>
    <div class="flex items-center gap-0.5" aria-hidden="true">
        @for ($i = 1; $i <= 5; $i++)
            <svg class="{{ $size }} {{ $i <= $rounded ? 'fill-accent text-accent' : 'fill-line text-line' }}" viewBox="0 0 20 20">
                <path d="M10 1.5l2.6 5.4 5.9.7-4.3 4.1 1.1 5.9L10 14.7l-5.3 2.9 1.1-5.9L1.5 7.6l5.9-.7L10 1.5Z" />
            </svg>
        @endfor
    </div>
    @if ($count !== null)
        <span class="text-xs font-medium text-stone">{{ $count > 0 ? number_format($rating, 1).' ('.$count.')' : 'No reviews yet' }}</span>
    @endif
</div>
