<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        return view('wishlist.index', [
            'products' => $request->user()->wishlistedProducts()->with('category')->paginate(12),
            'cartCount' => collect(session('cart.items', []))->sum('quantity'),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->user()->wishlistedProducts()->syncWithoutDetaching([$product->id]);

        return back()->with('status', "{$product->name} added to your wishlist.");
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->user()->wishlistedProducts()->detach($product->id);

        return back()->with('status', "{$product->name} removed from your wishlist.");
    }
}
