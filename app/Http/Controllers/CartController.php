<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View
    {
        return view('cart.index', [
            'cart' => $this->cart->snapshot(),
            'cartCount' => $this->cart->count(),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        if ($product->stock < 1) {
            return redirect()->route('products.show', $product)->with('status', "{$product->name} is currently sold out.");
        }

        $validated = $request->validate([
            'size' => ['nullable', 'string', 'max:10'],
            'color' => ['nullable', 'string', 'max:30'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $items = session('cart.items', []);
        $key = $this->cartKey($product, $validated['size'] ?? null, $validated['color'] ?? null);

        $requestedQuantity = ($items[$key]['quantity'] ?? 0) + (int) $validated['quantity'];

        $items[$key] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image_url' => $product->image_url,
            'price' => (float) $product->price,
            'size' => $validated['size'] ?? null,
            'color' => $validated['color'] ?? null,
            'quantity' => min($requestedQuantity, $product->stock, 10),
        ];

        session(['cart.items' => $items]);

        return redirect()->route('cart.index')->with('status', "{$product->name} added to your cart.");
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $items = session('cart.items', []);

        if (isset($items[$key])) {
            $stock = Product::find($items[$key]['product_id'])?->stock;
            $items[$key]['quantity'] = $stock !== null
                ? min((int) $validated['quantity'], max($stock, 1))
                : (int) $validated['quantity'];
            session(['cart.items' => $items]);
        }

        return redirect()->route('cart.index')->with('status', 'Cart updated.');
    }

    public function destroy(string $key): RedirectResponse
    {
        $items = session('cart.items', []);
        unset($items[$key]);
        session(['cart.items' => $items]);

        return redirect()->route('cart.index')->with('status', 'Item removed from your cart.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:40'],
        ]);

        if (! $this->cart->applyCoupon($validated['code'])) {
            return redirect()->route('cart.index')->with('status', 'That coupon code is invalid or expired.');
        }

        return redirect()->route('cart.index')->with('status', 'Coupon applied.');
    }

    public function removeCoupon(): RedirectResponse
    {
        $this->cart->removeCoupon();

        return redirect()->route('cart.index')->with('status', 'Coupon removed.');
    }

    private function cartKey(Product $product, ?string $size, ?string $color): string
    {
        return implode(':', [$product->id, $size ?: 'any-size', $color ?: 'any-color']);
    }
}
