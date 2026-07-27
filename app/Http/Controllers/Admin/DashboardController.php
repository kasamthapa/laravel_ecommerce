<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalOrders' => Order::count(),
            'totalRevenue' => Order::where('payment_status', 'paid')->sum('total'),
            'awaitingDeliveryCount' => Order::where('payment_status', 'paid')
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->count(),
            'lowStockCount' => Product::where('stock', '<=', 5)->count(),
            'recentOrders' => Order::latest()->take(8)->get(),
        ]);
    }
}
