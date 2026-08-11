@props(['eyebrow' => null, 'heading', 'body' => null, 'align' => 'left'])

<div {{ $attributes->merge(['class' => ($align === 'center' ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl')]) }}>
    @if ($eyebrow)
        <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">{{ $eyebrow }}</p>
    @endif
    <h2 class="mt-3 font-serif text-3xl leading-tight text-ink sm:text-4xl">{{ $heading }}</h2>
    @if ($body)
        <p class="mt-4 text-base leading-relaxed text-stone">{{ $body }}</p>
    @endif
</div>
