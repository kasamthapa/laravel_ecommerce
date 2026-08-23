<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuickAddButton extends Component
{
    public Product $product;

    public bool $added = false;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * Adds the product's first listed size/color straight from the catalog
     * grid, without visiting the product page — same session cart format
     * AddToCartForm writes, so both stay compatible.
     */
    public function quickAdd(): void
    {
        $this->product->refresh();

        if ($this->product->stock < 1) {
            return;
        }

        $size = $this->product->sizes[0] ?? null;
        $color = $this->product->colors[0] ?? null;

        $items = session('cart.items', []);
        $key = implode(':', [$this->product->id, $size ?: 'any-size', $color ?: 'any-color']);

        $requestedQuantity = ($items[$key]['quantity'] ?? 0) + 1;

        $items[$key] = [
            'product_id' => $this->product->id,
            'name' => $this->product->name,
            'slug' => $this->product->slug,
            'image_url' => $this->product->image_url,
            'price' => (float) $this->product->price,
            'size' => $size,
            'color' => $color,
            'quantity' => min($requestedQuantity, $this->product->stock, 10),
        ];

        session(['cart.items' => $items]);

        $this->added = true;
        $this->dispatch('cart-updated');
    }

    public function render(): View
    {
        return view('livewire.quick-add-button');
    }
}
