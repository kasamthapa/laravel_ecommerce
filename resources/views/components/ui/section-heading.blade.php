@props(['eyebrow' => null, 'heading', 'body' => null, 'align' => 'left'])

<div {{ $attributes->merge(['class' => ($align === 'center' ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl')]) }}>
    @if ($eyebrow)
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-volt">{{ $eyebrow }}</p>
    @endif
    <h2 class="mt-3 font-display text-4xl font-extrabold uppercase leading-[0.95] tracking-tight text-bone sm:text-5xl">{{ $heading }}</h2>
    @if ($body)
        <p class="mt-4 text-base leading-relaxed text-smoke">{{ $body }}</p>
    @endif
</div>
