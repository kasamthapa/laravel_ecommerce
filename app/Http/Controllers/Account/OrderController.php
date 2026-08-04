<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        return view('account.orders.index', [
            'orders' => $request->user()->orders()->latest()->paginate(10),
            'cartCount' => collect(session('cart.items', []))->sum('quantity'),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return view('account.orders.show', [
            'order' => $order->load('orderItems.product'),
            'cartCount' => collect(session('cart.items', []))->sum('quantity'),
        ]);
    }
}
