<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddToCartForm extends Component
{
    public Product $product;

    #[Validate('nullable|string|max:10')]
    public ?string $size = null;

    #[Validate('nullable|string|max:30')]
    public ?string $color = null;

    #[Validate('required|integer|min:1|max:10')]
    public int $quantity = 1;

    public bool $added = false;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->size = $product->sizes[0] ?? null;
        $this->color = $product->colors[0] ?? null;
    }

    #[On('sticky-add-to-cart')]
    public function addToCart(): void
    {
        $this->product->refresh();

        if ($this->product->stock < 1) {
            $this->addError('quantity', 'This frame just sold out.');

            return;
        }

        $this->validate();

        $items = session('cart.items', []);
        $key = implode(':', [$this->product->id, $this->size ?: 'any-size', $this->color ?: 'any-color']);

        $requestedQuantity = ($items[$key]['quantity'] ?? 0) + $this->quantity;

        $items[$key] = [
            'product_id' => $this->product->id,
            'name' => $this->product->name,
            'slug' => $this->product->slug,
            'image_url' => $this->product->image_url,
            'price' => (float) $this->product->price,
            'size' => $this->size,
            'color' => $this->color,
            'quantity' => min($requestedQuantity, $this->product->stock, 10),
        ];

        session(['cart.items' => $items]);

        $this->added = true;
        $this->dispatch('cart-updated');
    }

    public function render(): View
    {
        return view('livewire.add-to-cart-form');
    }
}
