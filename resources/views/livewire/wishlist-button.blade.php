<button type="button" wire:click="toggle" aria-label="{{ $wishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}" class="motion-press grid {{ $size }} place-items-center rounded-full bg-cream shadow-sm">
    <span class="{{ $wishlisted ? 'text-accent' : 'text-stone' }}">{{ $wishlisted ? '♥' : '♡' }}</span>
</button>
