<button type="button" wire:click="toggle" aria-label="{{ $wishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}" class="motion-invert grid {{ $size }} place-items-center rounded-full bg-black shadow-lg">
    <span class="{{ $wishlisted ? 'text-volt' : 'text-smoke' }}">{{ $wishlisted ? '♥' : '♡' }}</span>
</button>
