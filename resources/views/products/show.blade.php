<x-layouts.storefront :title="$product->name.' - Luma Lens'" :cart-count="$cartCount">
    <section class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[1.04fr_0.96fr] lg:px-8">
        <div class="grid gap-4 motion-fade" data-gallery data-images="{{ json_encode($product->gallery()) }}">
            <div data-gallery-stage class="gallery-stage motion-glow relative overflow-hidden rounded-lg border border-zinc-200 bg-[#f3f3ef]">
                <img data-gallery-main src="{{ $product->gallery()[0] }}" alt="{{ $product->name }}" class="h-full min-h-96 w-full object-cover mix-blend-multiply {{ $product->stock < 1 ? 'grayscale' : '' }}">
                @if ($product->stock < 1)
                    <span class="pointer-events-none absolute left-5 top-5 rounded-full bg-zinc-950 px-3 py-1 text-xs font-black uppercase text-white">Sold out</span>
                @elseif ($product->compare_at_price)
                    <span class="pointer-events-none absolute left-5 top-5 rounded-full bg-[#e25822] px-3 py-1 text-xs font-black uppercase text-white">Limited offer</span>
                @elseif ($product->stock <= 5)
                    <span class="pointer-events-none absolute left-5 top-5 rounded-full bg-[#092b83] px-3 py-1 text-xs font-black uppercase text-white">Only {{ $product->stock }} left</span>
                @endif
                <span class="pointer-events-none absolute right-5 top-5 rounded-full bg-white px-4 py-2 text-xs font-black text-[#092b83] shadow-sm">Try on &middot; Click to zoom</span>
            </div>

            @if (count($product->gallery()) > 1)
                <div class="flex gap-3">
                    @foreach ($product->gallery() as $index => $image)
                        <button type="button" data-gallery-thumb data-index="{{ $index }}" class="h-20 w-20 shrink-0 overflow-hidden rounded-lg border-2 {{ $index === 0 ? 'border-[#092b83]' : 'border-zinc-200' }}">
                            <img src="{{ $image }}" alt="{{ $product->name }} angle {{ $index + 1 }}" class="h-full w-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="grid gap-3 sm:grid-cols-3">
                <div data-motion-reveal class="motion-lift rounded-lg border border-zinc-200 bg-white p-4">
                    <p class="text-xs font-black uppercase text-[#092b83]">Fit range</p>
                    <p class="mt-2 text-sm font-bold text-zinc-700">{{ implode(', ', $product->sizes) }}</p>
                </div>
                <div data-motion-reveal class="motion-lift rounded-lg border border-zinc-200 bg-white p-4">
                    <p class="text-xs font-black uppercase text-[#092b83]">Finishes</p>
                    <p class="mt-2 text-sm font-bold text-zinc-700">{{ implode(', ', $product->colors) }}</p>
                </div>
                <div data-motion-reveal class="motion-lift rounded-lg border border-zinc-200 bg-white p-4">
                    <p class="text-xs font-black uppercase text-[#092b83]">Stock</p>
                    <p class="mt-2 text-sm font-bold {{ $product->stock < 1 ? 'text-red-600' : ($product->stock <= 5 ? 'text-[#e25822]' : 'text-zinc-700') }}">
                        {{ $product->stock < 1 ? 'Sold out' : "{$product->stock} pieces ready" }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-col justify-center gap-7 motion-fade-slow">
            <div>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-sm font-bold uppercase text-[#092b83]">{{ $product->category->name }}</a>
                <div class="mt-3 flex items-start justify-between gap-4">
                    <h1 class="text-5xl font-black leading-none text-[#092b83]">{{ $product->name }}</h1>
                    @auth
                        <livewire:wishlist-button :product="$product" :wishlisted="in_array($product->id, $wishlistedProductIds, true)" size="h-12 w-12 shrink-0 border border-zinc-200 text-xl" :key="'wishlist-main-'.$product->id" />
                    @endauth
                </div>
                <a href="#reviews" class="mt-2 inline-block">
                    <x-star-rating :rating="(float) ($product->reviews_avg_rating ?? 0)" :count="$product->reviews_count ?? 0" size="h-5 w-5" />
                </a>
                <p class="mt-4 text-lg leading-8 text-zinc-600">{{ $product->description }}</p>
            </div>

            <div class="flex items-end gap-3">
                <p class="text-3xl font-black">Rs. {{ number_format((float) $product->price) }}</p>
                @if ($product->compare_at_price)
                    <p class="pb-1 font-medium text-zinc-400 line-through">Rs. {{ number_format((float) $product->compare_at_price) }}</p>
                @endif
            </div>

            <div class="grid gap-3 border-y border-zinc-200 py-5 sm:grid-cols-3">
                <div>
                    <p class="text-xs font-black uppercase text-zinc-500">Lens service</p>
                    <p class="mt-1 text-sm font-bold">Prescription-ready</p>
                </div>
                <div>
                    <p class="text-xs font-black uppercase text-zinc-500">Delivery</p>
                    <p class="mt-1 text-sm font-bold">Free over Rs. 10,000</p>
                </div>
                <div>
                    <p class="text-xs font-black uppercase text-zinc-500">Payment</p>
                    <p class="mt-1 text-sm font-bold">Khalti verified</p>
                </div>
            </div>

            @if ($product->stock < 1)
                <div class="motion-glow grid gap-3 rounded-lg border border-zinc-950/10 bg-white p-5 shadow-sm">
                    <p class="font-black text-zinc-950">This frame is sold out</p>
                    <p class="text-sm leading-6 text-zinc-600">Check back soon, or browse similar frames below while we restock.</p>
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}#collection" class="motion-press rounded-full bg-[#092b83] px-5 py-3 text-center font-black text-white hover:bg-zinc-950">Browse similar frames</a>
                </div>
            @else
                <livewire:add-to-cart-form :product="$product" />
            @endif
        </div>
    </section>

    <section id="reviews" class="mx-auto max-w-4xl px-4 pb-12 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-black uppercase text-[#092b83]">Customer reviews</p>
                <h2 class="text-2xl font-black">What shoppers say</h2>
            </div>
            <x-star-rating :rating="(float) ($product->reviews_avg_rating ?? 0)" :count="$product->reviews_count ?? 0" size="h-5 w-5" />
        </div>

        @auth
            <form method="POST" action="{{ route('reviews.store', $product) }}" class="mt-6 grid gap-4 rounded-lg border border-zinc-200 bg-white p-5">
                @csrf
                <p class="text-sm font-black text-zinc-950">{{ $userReview ? 'Update your review' : 'Write a review' }}</p>
                <label class="grid gap-2 text-sm font-bold">
                    Rating
                    <select name="rating" class="w-32 rounded-full border border-zinc-300 px-4 py-2 font-medium">
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('rating', $userReview->rating ?? 5) == $i)>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-bold">
                    Title (optional)
                    <input name="title" value="{{ old('title', $userReview->title ?? '') }}" maxlength="120" class="rounded-md border border-zinc-300 px-3 py-2 font-medium">
                </label>
                <label class="grid gap-2 text-sm font-bold">
                    Review
                    <textarea name="body" rows="3" required maxlength="2000" class="rounded-md border border-zinc-300 px-3 py-2 font-medium">{{ old('body', $userReview->body ?? '') }}</textarea>
                </label>
                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-3 text-sm font-medium text-red-700">{{ $errors->first() }}</div>
                @endif
                <button class="motion-press w-fit rounded-full bg-[#092b83] px-5 py-2.5 text-sm font-black text-white hover:bg-zinc-950">{{ $userReview ? 'Update review' : 'Submit review' }}</button>
            </form>
        @else
            <p class="mt-6 rounded-lg border border-dashed border-zinc-300 bg-white p-5 text-sm text-zinc-600"><a href="{{ route('login') }}" class="font-bold text-[#092b83] hover:underline">Sign in</a> to write a review.</p>
        @endauth

        <div class="mt-8 grid gap-5">
            @forelse ($product->reviews as $review)
                <div class="border-b border-zinc-100 pb-5 last:border-0">
                    <div class="flex items-center justify-between gap-3">
                        <p class="font-black text-zinc-950">{{ $review->user->name }}</p>
                        <x-star-rating :rating="$review->rating" />
                    </div>
                    @if ($review->title)
                        <p class="mt-2 font-bold text-zinc-950">{{ $review->title }}</p>
                    @endif
                    <p class="mt-1 text-sm leading-6 text-zinc-600">{{ $review->body }}</p>
                    <p class="mt-2 text-xs font-medium text-zinc-400">{{ $review->created_at->format('d M Y') }}</p>
                </div>
            @empty
                <p class="text-sm text-zinc-500">No reviews yet &mdash; be the first to share your fit.</p>
            @endforelse
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
            <div data-motion-reveal class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-black uppercase text-[#092b83]">Continue the edit</p>
                    <h2 class="text-2xl font-black">More from {{ $product->category->name }}</h2>
                </div>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}#collection" class="text-sm font-black uppercase text-zinc-600 hover:text-zinc-950">View category</a>
            </div>
            <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($relatedProducts as $relatedProduct)
                    <x-product-card :product="$relatedProduct" :wishlisted="in_array($relatedProduct->id, $wishlistedProductIds, true)" data-motion-reveal />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.storefront>
