<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\KhaltiPaymentGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function __construct(private readonly KhaltiPaymentGateway $khalti) {}

    public function create(): View|RedirectResponse
    {
        $cart = $this->cart();

        if (count($cart['items']) === 0) {
            return redirect()->route('cart.index')->with('status', 'Add a pair to your cart before checkout.');
        }

        return view('checkout.create', [
            'cart' => $cart,
            'cartCount' => collect($cart['items'])->sum('quantity'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $this->cart();

        if (count($cart['items']) === 0) {
            return redirect()->route('products.index')->with('status', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:160'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $order = DB::transaction(function () use ($validated, $cart): Order {
            $order = Order::create([
                ...$validated,
                'order_number' => 'LUM-'.now()->format('ymd').'-'.Str::upper(Str::random(6)),
                'subtotal' => $cart['subtotal'],
                'shipping_total' => $cart['shipping'],
                'total' => $cart['total'],
                'status' => 'payment_pending',
                'payment_method' => 'khalti',
                'payment_status' => 'initiated',
            ]);

            foreach ($cart['items'] as $item) {
                $product = Product::find($item['product_id']);

                $order->orderItems()->create([
                    'product_id' => $product?->id,
                    'product_name' => $item['name'],
                    'size' => $item['size'],
                    'color' => $item['color'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'line_total' => $item['price'] * $item['quantity'],
                ]);
            }

            return $order;
        });

        try {
            $payment = $this->khalti->initiate(
                $order->load('orderItems'),
                route('khalti.callback', $order),
                route('products.index'),
            );
        } catch (RuntimeException) {
            $order->update([
                'payment_status' => 'failed',
                'status' => 'payment_failed',
            ]);

            return redirect()->route('checkout.create')->with('status', 'Khalti payment could not be started. Please try again.');
        }

        $order->update([
            'khalti_pidx' => $payment['pidx'],
            'khalti_amount' => $this->khalti->amountInPaisa($order),
        ]);

        return redirect()->away($payment['payment_url']);
    }

    public function khaltiCallback(Request $request, Order $order): RedirectResponse
    {
        $pidx = $request->string('pidx')->toString();

        if ($pidx === '' || $pidx !== $order->khalti_pidx) {
            return redirect()->route('cart.index')->with('status', 'Khalti payment reference did not match this order.');
        }

        try {
            $payment = $this->khalti->lookup($pidx);
        } catch (RuntimeException) {
            return redirect()->route('cart.index')->with('status', 'Khalti payment could not be verified yet. Please contact support with your order number.');
        }

        if ($payment['status'] !== 'Completed' || $payment['total_amount'] !== $this->khalti->amountInPaisa($order)) {
            $order->update([
                'payment_status' => Str::of($payment['status'])->lower()->replace(' ', '_')->toString(),
                'status' => 'payment_failed',
                'khalti_transaction_id' => $payment['transaction_id'] ?? null,
            ]);

            return redirect()->route('cart.index')->with('status', 'Khalti payment was not completed. Your cart is still available.');
        }

        DB::transaction(function () use ($order, $payment, $request): void {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'khalti_transaction_id' => $payment['transaction_id'] ?? $request->string('transaction_id')->toString(),
                'paid_at' => now(),
            ]);

            foreach ($order->orderItems()->with('product')->get() as $item) {
                $item->product?->decrement('stock', min($item->quantity, $item->product->stock));
            }
        });

        session()->forget('cart');

        return redirect()->route('checkout.confirmation', $order)->with('status', 'Khalti payment confirmed. Order placed successfully.');
    }

    public function confirmation(Order $order): View
    {
        return view('checkout.confirmation', [
            'order' => $order->load('orderItems'),
            'cartCount' => collect(session('cart.items', []))->sum('quantity'),
        ]);
    }

    private function cart(): array
    {
        $cart = session('cart', ['items' => []]);
        $cart['subtotal'] = collect($cart['items'])->sum(fn (array $item): float => $item['price'] * $item['quantity']);
        $cart['shipping'] = $cart['subtotal'] > 0 ? 250.00 : 0.00;
        $cart['total'] = $cart['subtotal'] + $cart['shipping'];

        return $cart;
    }
}
