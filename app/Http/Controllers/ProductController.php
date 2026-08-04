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
        $sort = $request->string('sort')->toString();
        $minPrice = $request->filled('min_price') ? (float) $request->input('min_price') : null;
        $maxPrice = $request->filled('max_price') ? (float) $request->input('max_price') : null;

        $products = Product::query()
            ->active()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
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
            ->when($minPrice !== null, fn ($query) => $query->where('price', '>=', $minPrice))
            ->when($maxPrice !== null, fn ($query) => $query->where('price', '<=', $maxPrice))
            ->when($sort === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($sort === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when(! in_array($sort, ['price_asc', 'price_desc'], true), fn ($query) => $query->latest())
            ->paginate(9)
            ->withQueryString();

        return view('products.index', [
            'categories' => Category::withCount(['products' => fn ($query) => $query->active()])->get(),
            'featuredProducts' => Product::active()->featured()->with('category')->limit(3)->get(),
            'products' => $products,
            'search' => $search,
            'sort' => $sort,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'selectedCategory' => $selectedCategory,
            'wishlistedProductIds' => $this->wishlistedProductIds($request),
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
