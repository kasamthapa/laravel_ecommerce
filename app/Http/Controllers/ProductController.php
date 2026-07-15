<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $selectedCategory = null;
        $search = trim($request->string('q')->toString());

        $products = Product::query()
            ->active()
            ->with('category')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $likeSearch = "%{$search}%";

                    $query
                        ->where('name', 'like', $likeSearch)
                        ->orWhere('description', 'like', $likeSearch)
                        ->orWhere('colors', 'like', $likeSearch)
                        ->orWhere('sizes', 'like', $likeSearch)
                        ->orWhereHas('category', fn ($query) => $query->where('name', 'like', $likeSearch));
                });
            })
            ->when($request->string('category')->isNotEmpty(), function ($query) use ($request, &$selectedCategory): void {
                $selectedCategory = Category::where('slug', $request->string('category'))->first();

                if ($selectedCategory !== null) {
                    $query->whereBelongsTo($selectedCategory);
                }
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('products.index', [
            'categories' => Category::withCount(['products' => fn ($query) => $query->active()])->get(),
            'featuredProducts' => Product::active()->featured()->with('category')->limit(3)->get(),
            'products' => $products,
            'search' => $search,
            'selectedCategory' => $selectedCategory,
            'cartCount' => collect(session('cart.items', []))->sum('quantity'),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('products.show', [
            'product' => $product->load('category'),
            'relatedProducts' => Product::active()
                ->whereBelongsTo($product->category)
                ->whereKeyNot($product->getKey())
                ->limit(4)
                ->get(),
            'cartCount' => collect(session('cart.items', []))->sum('quantity'),
        ]);
    }
}
