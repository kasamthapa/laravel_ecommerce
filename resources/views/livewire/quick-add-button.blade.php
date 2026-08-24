<button
    type="button"
    wire:click="quickAdd"
    wire:loading.attr="disabled"
    wire:target="quickAdd"
    class="motion-invert w-full border border-volt bg-black/90 px-3 py-2.5 text-xs font-medium uppercase tracking-wide text-bone backdrop-blur-sm hover:bg-volt hover:text-bone disabled:cursor-not-allowed disabled:opacity-60"
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
