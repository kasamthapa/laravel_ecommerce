<button type="button" wire:click="toggle" aria-label="{{ $wishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}" class="motion-press grid {{ $size }} place-items-center rounded-full bg-white shadow-sm hover:bg-zinc-50">
    <span class="{{ $wishlisted ? 'text-[#e25822]' : 'text-zinc-400' }}">{{ $wishlisted ? '♥' : '♡' }}</span>
</button>
