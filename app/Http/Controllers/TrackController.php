<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackController extends Controller
{
    public function create(): View
    {
        return view('tracking.create', [
            'order' => null,
            'cartCount' => collect(session('cart.items', []))->sum('quantity'),
        ]);
    }

    public function show(Request $request): View
    {
        $validated = $request->validate([
            'order_number' => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        $order = Order::where('order_number', $validated['order_number'])
            ->where('customer_email', $validated['email'])
            ->with('orderItems')
            ->first();

        return view('tracking.create', [
            'order' => $order,
            'notFound' => $order === null,
            'cartCount' => collect(session('cart.items', []))->sum('quantity'),
        ]);
    }
}
