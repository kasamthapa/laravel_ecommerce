<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('products.index', [
            'cartCount' => collect(session('cart.items', []))->sum('quantity'),
        ]);
    }

    public function show(Request $request, Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->loadAvg('reviews', 'rating');
        $product->loadCount('reviews');

        return view('products.show', [
            'product' => $product->load(['category', 'reviews' => fn ($query) => $query->with('user')->latest()]),
            'relatedProducts' => Product::active()
                ->whereBelongsTo($product->category)
                ->whereKeyNot($product->getKey())
                ->limit(4)
                ->get(),
            'wishlistedProductIds' => $this->wishlistedProductIds($request),
            'userReview' => $request->user()?->reviews()->where('product_id', $product->id)->first(),
            'cartCount' => collect(session('cart.items', []))->sum('quantity'),
        ]);
    }

    /**
     * @return array<int, int>
     */
    private function wishlistedProductIds(Request $request): array
    {
        return $request->user()?->wishlistedProducts()->pluck('products.id')->all() ?? [];
    }
}
