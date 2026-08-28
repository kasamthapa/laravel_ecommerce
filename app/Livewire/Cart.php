<?php

namespace App\Livewire;

use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Cart extends Component
{
    public string $couponCode = '';

    public ?string $flash = null;

    /**
     * @var 'success'|'error'|null
     */
    public ?string $flashType = null;

    /**
     * @return array{items: array<string, array>, coupon: ?Coupon, coupon_code: ?string, subtotal: float, shipping: float, discount: float, total: float}
     */
    #[Computed]
    public function cart(): array
    {
        return app(CartService::class)->snapshot();
    }

    public function updateQuantity(string $key, int $quantity): void
    {
        $items = session('cart.items', []);

        if (isset($items[$key])) {
            $stock = Product::find($items[$key]['product_id'])?->stock;
            $items[$key]['quantity'] = $stock !== null
                ? min(max($quantity, 1), max($stock, 1))
                : max($quantity, 1);

            session(['cart.items' => $items]);
        }

        unset($this->cart);
        $this->dispatch('cart-updated');
    }

    public function removeItem(string $key): void
    {
        $items = session('cart.items', []);
        unset($items[$key]);
        session(['cart.items' => $items]);

        unset($this->cart);
        $this->dispatch('cart-updated');
    }

    public function applyCoupon(): void
    {
        $applied = app(CartService::class)->applyCoupon($this->couponCode);

        $this->flash = $applied ? 'Coupon applied.' : 'That coupon code is invalid or expired.';
        $this->flashType = $applied ? 'success' : 'error';
        $this->couponCode = '';

        unset($this->cart);
    }

    public function removeCoupon(): void
    {
        app(CartService::class)->removeCoupon();

        $this->flash = 'Coupon removed.';
        $this->flashType = 'success';

        unset($this->cart);
    }

    public function render(): View
    {
        return view('livewire.cart');
    }
}
