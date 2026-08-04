<?php

namespace App\Services;

use App\Models\Coupon;

class CartService
{
    private const SHIPPING_FLAT_RATE = 250.00;

    /**
     * @return array{items: array<string, array>, coupon: ?Coupon, coupon_code: ?string, subtotal: float, shipping: float, discount: float, total: float}
     */
    public function snapshot(): array
    {
        $cart = session('cart', ['items' => []]);
        $items = $cart['items'] ?? [];

        $subtotal = collect($items)->sum(fn (array $item): float => $item['price'] * $item['quantity']);
        $shipping = $subtotal > 0 ? self::SHIPPING_FLAT_RATE : 0.00;

        $couponCode = $cart['coupon_code'] ?? null;
        $coupon = $couponCode !== null ? Coupon::where('code', $couponCode)->first() : null;
        $discount = 0.0;

        if ($coupon !== null && $coupon->isValid()) {
            $discount = min($coupon->discountFor($subtotal), $subtotal);
        } else {
            $coupon = null;
            $couponCode = null;
        }

        return [
            'items' => $items,
            'coupon' => $coupon,
            'coupon_code' => $couponCode,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => max($subtotal + $shipping - $discount, 0),
        ];
    }

    public function count(): int
    {
        return (int) collect(session('cart.items', []))->sum('quantity');
    }

    public function applyCoupon(string $code): bool
    {
        $coupon = Coupon::where('code', $code)->first();

        if ($coupon === null || ! $coupon->isValid()) {
            return false;
        }

        session(['cart.coupon_code' => $coupon->code]);

        return true;
    }

    public function removeCoupon(): void
    {
        session()->forget('cart.coupon_code');
    }
}
