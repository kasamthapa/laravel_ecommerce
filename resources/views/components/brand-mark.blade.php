@props(['iconClass' => 'h-5 w-8 text-accent', 'textClass' => 'font-serif text-2xl tracking-tight text-ink'])

{{--
    A custom-drawn glasses mark (two lenses, bridge, temple arms) rather
    than a plain text wordmark — the single biggest "this looks like every
    other template" tell is having no distinct mark at all.
--}}
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
    <svg class="{{ $iconClass }} shrink-0" viewBox="0 0 40 20" fill="none" aria-hidden="true">
        <circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.6" />
        <circle cx="30" cy="10" r="7" stroke="currentColor" stroke-width="1.6" />
        <path d="M17 8.5c1-1.6 5-1.6 6 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
        <path d="M3 7 0 4.5M37 7l3-2.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
    </svg>
    <span class="{{ $textClass }}">Luma Lens</span>
</span>
