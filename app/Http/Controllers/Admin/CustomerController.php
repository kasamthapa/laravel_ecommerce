<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('admin.customers.index', [
            'customers' => User::query()
                ->withCount('orders')
                ->withSum(['orders as total_spent' => fn ($query) => $query->where('payment_status', 'paid')], 'total')
                ->orderByDesc('total_spent')
                ->paginate(20),
        ]);
    }

    public function show(User $user): View
    {
        return view('admin.customers.show', [
            'customer' => $user,
            'orders' => $user->orders()->latest()->paginate(10),
        ]);
    }
}
