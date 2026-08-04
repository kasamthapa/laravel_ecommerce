<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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
            'revenueChart' => $this->revenueByDay(),
            'statusChart' => $this->ordersByStatus(),
            'topProducts' => $this->topProducts(),
        ]);
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, float>}
     */
    private function revenueByDay(): array
    {
        $days = collect(range(13, 0))->map(fn (int $i) => now()->subDays($i)->format('Y-m-d'));

        $totalsByDay = Order::query()
            ->where('payment_status', 'paid')
            ->where('paid_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(paid_at) as day, SUM(total) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        return [
            'labels' => $days->map(fn (string $day) => Carbon::parse($day)->format('d M'))->all(),
            'values' => $days->map(fn (string $day) => (float) ($totalsByDay[$day] ?? 0))->all(),
        ];
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    private function ordersByStatus(): array
    {
        $counts = Order::query()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        return [
            'labels' => $counts->keys()->map(fn (string $status) => ucfirst(str_replace('_', ' ', $status)))->all(),
            'values' => $counts->values()->all(),
        ];
    }

    /**
     * @return Collection<int, object>
     */
    private function topProducts(): Collection
    {
        return OrderItem::query()
            ->selectRaw('product_name, SUM(quantity) as units, SUM(line_total) as revenue')
            ->groupBy('product_name')
            ->orderByDesc('units')
            ->limit(5)
            ->get();
    }
}
