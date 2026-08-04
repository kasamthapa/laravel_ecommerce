<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class WishlistButton extends Component
{
    public Product $product;

    public bool $wishlisted = false;

    public string $size = 'h-9 w-9 text-lg';

    public function mount(Product $product, bool $wishlisted = false, string $size = 'h-9 w-9 text-lg'): void
    {
        $this->product = $product;
        $this->wishlisted = $wishlisted;
        $this->size = $size;
    }

    public function toggle(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'), navigate: false);

            return;
        }

        $user = auth()->user();

        if ($this->wishlisted) {
            $user->wishlistedProducts()->detach($this->product->id);
        } else {
            $user->wishlistedProducts()->syncWithoutDetaching([$this->product->id]);
        }

        $this->wishlisted = ! $this->wishlisted;
    }

    public function render(): View
    {
        return view('livewire.wishlist-button');
    }
}
