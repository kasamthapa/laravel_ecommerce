@props(['title', 'subtitle' => null])

<div class="flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="font-serif text-2xl text-ink">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-1 text-sm text-stone">{{ $subtitle }}</p>
        @endif
    </div>
    @if (isset($actions))
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
