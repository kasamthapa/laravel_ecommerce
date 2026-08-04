<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
            'title' => ['nullable', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            $validated,
        );

        return redirect(route('products.show', $product).'#reviews')->with('status', 'Thanks for your review.');
    }
}
