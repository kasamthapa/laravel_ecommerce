<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index', [
            'cart' => $this->cart(),
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'size' => ['nullable', 'string', 'max:10'],
            'color' => ['nullable', 'string', 'max:30'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $cart = $this->cart();
        $key = $this->cartKey($product, $validated['size'] ?? null, $validated['color'] ?? null);

        $cart['items'][$key] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image_url' => $product->image_url,
            'price' => (float) $product->price,
            'size' => $validated['size'] ?? null,
            'color' => $validated['color'] ?? null,
            'quantity' => ($cart['items'][$key]['quantity'] ?? 0) + (int) $validated['quantity'],
        ];

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('status', "{$product->name} added to your cart.");
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $cart = $this->cart();

        if (isset($cart['items'][$key])) {
            $cart['items'][$key]['quantity'] = (int) $validated['quantity'];
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('status', 'Cart updated.');
    }

    public function destroy(string $key): RedirectResponse
    {
        $cart = $this->cart();
        unset($cart['items'][$key]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('status', 'Item removed from your cart.');
    }

    private function cart(): array
    {
        $cart = session('cart', ['items' => []]);
        $cart['subtotal'] = collect($cart['items'])->sum(fn (array $item): float => $item['price'] * $item['quantity']);
        $cart['shipping'] = $cart['subtotal'] > 0 ? 250.00 : 0.00;
        $cart['total'] = $cart['subtotal'] + $cart['shipping'];

        return $cart;
    }

    private function cartCount(): int
    {
        return (int) collect(session('cart.items', []))->sum('quantity');
    }

    private function cartKey(Product $product, ?string $size, ?string $color): string
    {
        return implode(':', [$product->id, $size ?: 'any-size', $color ?: 'any-color']);
    }
}
