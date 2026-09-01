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

        $rules = [
            'size' => ['nullable', 'string', 'max:10'],
            'color' => ['nullable', 'string', 'max:30'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ];

        if ($product->requires_prescription) {
            $rules['prescription_status'] = ['required', 'in:provided,later'];

            if ($request->input('prescription_status') === 'provided') {
                $rules = [
                    ...$rules,
                    'sph_right' => ['required', 'numeric', 'between:-20,20'],
                    'sph_left' => ['required', 'numeric', 'between:-20,20'],
                    'cyl_right' => ['nullable', 'numeric', 'between:-10,10'],
                    'cyl_left' => ['nullable', 'numeric', 'between:-10,10'],
                    'axis_right' => ['nullable', 'required_with:cyl_right', 'integer', 'between:0,180'],
                    'axis_left' => ['nullable', 'required_with:cyl_left', 'integer', 'between:0,180'],
                    'pd' => ['required', 'numeric', 'between:50,75'],
                ];
            }
        }

        $validated = $request->validate($rules);

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
            'prescription' => $this->buildPrescription($product, $validated),
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

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>|null
     */
    private function buildPrescription(Product $product, array $validated): ?array
    {
        if (! $product->requires_prescription) {
            return null;
        }

        if (($validated['prescription_status'] ?? null) === 'later') {
            return ['status' => 'later'];
        }

        return [
            'status' => 'provided',
            'sph_right' => (float) $validated['sph_right'],
            'sph_left' => (float) $validated['sph_left'],
            'cyl_right' => isset($validated['cyl_right']) && $validated['cyl_right'] !== '' ? (float) $validated['cyl_right'] : null,
            'cyl_left' => isset($validated['cyl_left']) && $validated['cyl_left'] !== '' ? (float) $validated['cyl_left'] : null,
            'axis_right' => isset($validated['axis_right']) && $validated['axis_right'] !== '' ? (int) $validated['axis_right'] : null,
            'axis_left' => isset($validated['axis_left']) && $validated['axis_left'] !== '' ? (int) $validated['axis_left'] : null,
            'pd' => (float) $validated['pd'],
        ];
    }
}
