<button
    type="button"
    wire:click="quickAdd"
    wire:loading.attr="disabled"
    wire:target="quickAdd"
    class="motion-press w-full border border-ink bg-cream/95 px-3 py-2.5 text-xs font-medium uppercase tracking-wide text-ink backdrop-blur-sm disabled:cursor-not-allowed disabled:opacity-60"
>
    <span wire:loading.remove wire:target="quickAdd">
        @if ($added)
            Added &#10003;
        @else
            Quick add
        @endif
    </span>
    <span wire:loading wire:target="quickAdd">Adding&hellip;</span>
</button>
