<x-layouts.storefront :title="$product->name.' - Luma Lens'" :cart-count="$cartCount">
    <section class="mx-auto grid max-w-[100rem] gap-10 px-4 py-10 sm:px-8 lg:grid-cols-2 lg:gap-16 lg:py-14">
        <div class="grid gap-4 motion-fade" data-product-view>
            @if ($product->model_path)
                <div class="flex gap-6 border-b border-line text-sm" role="tablist" aria-label="Product view">
                    <button type="button" data-view-tab="photos" role="tab" aria-selected="true" class="motion-press border-b-2 border-ink pb-3 font-medium text-ink">Photos</button>
                    <button type="button" data-view-tab="3d" role="tab" aria-selected="false" class="motion-press border-b-2 border-transparent pb-3 text-stone hover:text-ink">3D View</button>
                    <button type="button" data-view-tab="tryon" role="tab" aria-selected="false" class="motion-press border-b-2 border-transparent pb-3 text-stone hover:text-ink">Try On</button>
                </div>
            @endif

            <div data-view-panel="photos" data-gallery data-images="{{ json_encode($product->gallery()) }}">
                <div data-gallery-stage class="gallery-stage relative aspect-square overflow-hidden bg-cream-dim">
                    <img data-gallery-main src="{{ $product->gallery()[0] }}" alt="{{ $product->name }}" class="h-full w-full object-cover {{ $product->stock < 1 ? 'grayscale' : '' }}">
                </div>

                @if (count($product->gallery()) > 1)
                    <div class="flex gap-3">
                        @foreach ($product->gallery() as $index => $image)
                            <button type="button" data-gallery-thumb data-index="{{ $index }}" aria-label="Show image {{ $index + 1 }} of {{ $product->name }}" class="h-20 w-20 shrink-0 overflow-hidden border {{ $index === 0 ? 'border-ink' : 'border-line' }}">
                                <img src="{{ $image }}" alt="" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($product->model_path)
                <div data-view-panel="3d" class="hidden">
                    <div class="glasses-stage aspect-square min-h-0 overflow-hidden bg-cream-dim" data-glasses-viewer data-model-path="{{ asset($product->model_path) }}">
                        <canvas class="glasses-canvas" data-glasses-canvas></canvas>
                        <img src="{{ $product->gallery()[0] }}" alt="" class="glasses-3d">
                        <div data-glasses-skeleton class="absolute inset-0 z-[3] animate-pulse bg-cream-dim" aria-hidden="true"></div>
                    </div>
                    <p class="mt-3 text-center text-xs text-stone">Drag to rotate &middot; double-click to reset</p>
                </div>

                <div data-view-panel="tryon" class="hidden">
                    <div class="glasses-stage aspect-square min-h-0 overflow-hidden bg-cream-dim" data-face-tryon data-model-path="{{ asset($product->model_path) }}">
                        <div class="tryon-mirror absolute inset-0">
                            <video data-tryon-video class="absolute inset-0 h-full w-full object-cover" autoplay muted playsinline></video>
                            <canvas class="glasses-canvas" data-tryon-canvas></canvas>
                        </div>
                        <div data-glasses-skeleton class="absolute inset-0 z-[3] animate-pulse bg-cream-dim" aria-hidden="true"></div>
                        <div data-tryon-status class="absolute inset-0 z-[4] hidden flex-col items-center justify-center gap-3 bg-cream-dim px-6 text-center" aria-live="polite">
                            <p data-tryon-status-text class="font-serif text-lg text-ink"></p>
                            <button type="button" data-tryon-retry class="hidden motion-press border border-ink px-4 py-2 text-sm font-medium text-ink">Try again</button>
                        </div>
                    </div>
                    <p class="mt-3 text-center text-xs text-stone">Runs entirely in your browser &mdash; no photo or video ever leaves your device.</p>
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-7 motion-fade-slow lg:justify-center">
            <div>
                <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="motion-press text-xs font-medium uppercase tracking-[0.14em] text-stone hover:text-ink">{{ $product->category->name }}</a>
                <div class="mt-3 flex items-start justify-between gap-4">
                    <h1 class="font-serif text-4xl leading-tight text-ink sm:text-5xl">{{ $product->name }}</h1>
                    @auth
                        <livewire:wishlist-button :product="$product" :wishlisted="in_array($product->id, $wishlistedProductIds, true)" size="h-11 w-11 shrink-0 border border-line text-lg" :key="'wishlist-main-'.$product->id" />
                    @endauth
                </div>
                <a href="#reviews" class="mt-3 inline-block motion-press">
                    <x-star-rating :rating="(float) ($product->reviews_avg_rating ?? 0)" :count="$product->reviews_count ?? 0" size="h-4 w-4" />
                </a>
            </div>

            <div class="flex items-end gap-3">
                <p class="text-2xl text-ink">Rs. {{ number_format((float) $product->price) }}</p>
                @if ($product->compare_at_price)
                    <p class="pb-0.5 text-base text-stone-light line-through">Rs. {{ number_format((float) $product->compare_at_price) }}</p>
                @endif
            </div>

            <p class="font-serif text-lg leading-relaxed text-ink-soft">{{ $product->description }}</p>

            <div class="flex flex-wrap gap-3">
                @if ($product->stock < 1)
                    <x-ui.button variant="secondary" disabled class="cursor-not-allowed opacity-50">Sold out</x-ui.button>
                    <button type="button" disabled title="Virtual try-on is coming in a future update" class="motion-press inline-flex cursor-not-allowed items-center justify-center gap-2 border border-line px-6 py-3 text-sm font-medium tracking-wide text-stone opacity-70">
                        Try on &middot; coming soon
                    </button>
                @else
                    <livewire:add-to-cart-form :product="$product" />
                @endif
            </div>

            @if ($product->stock >= 1 && $product->stock <= 5)
                <p class="text-sm text-stone">Only {{ $product->stock }} left in stock.</p>
            @endif
        </div>
    </section>

    <section class="border-y border-line bg-cream-dim">
        <div class="mx-auto grid max-w-[100rem] gap-8 px-4 py-12 sm:px-8 sm:grid-cols-3">
            <div>
                <h2 class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Fit range</h2>
                <p class="mt-2 text-base text-ink">{{ implode(', ', $product->sizes) }}</p>
            </div>
            <div>
                <h2 class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Finishes</h2>
                <p class="mt-2 text-base text-ink">{{ implode(', ', $product->colors) }}</p>
            </div>
            <div>
                <h2 class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Details</h2>
                <p class="mt-2 text-base text-ink">Prescription-ready &middot; Free delivery over Rs. 10,000 &middot; Khalti secure checkout</p>
            </div>
        </div>
    </section>

    <section id="reviews" class="mx-auto max-w-3xl px-4 py-14 sm:px-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <x-ui.section-heading eyebrow="Customer reviews" heading="What shoppers say" />
            <x-star-rating :rating="(float) ($product->reviews_avg_rating ?? 0)" :count="$product->reviews_count ?? 0" size="h-4 w-4" />
        </div>

        @auth
            <form method="POST" action="{{ route('reviews.store', $product) }}" class="mt-8 grid gap-5 border border-line p-6">
                <p class="font-serif text-lg text-ink">{{ $userReview ? 'Update your review' : 'Write a review' }}</p>
                @csrf
                <x-ui.select label="Rating" name="rating" class="w-40">
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected(old('rating', $userReview->rating ?? 5) == $i)>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </x-ui.select>
                <x-ui.input label="Title (optional)" name="title" :value="old('title', $userReview->title ?? '')" maxlength="120" />
                <x-ui.textarea label="Review" name="body" :value="old('body', $userReview->body ?? '')" required maxlength="2000" />
                @if ($errors->any())
                    <div class="border border-error/30 bg-error-tint p-3 text-sm text-error">{{ $errors->first() }}</div>
                @endif
                <x-ui.button type="submit" class="w-fit">{{ $userReview ? 'Update review' : 'Submit review' }}</x-ui.button>
            </form>
        @else
            <p class="mt-8 border border-dashed border-line p-6 text-sm text-stone"><a href="{{ route('login') }}" class="motion-press font-medium text-accent hover:underline">Sign in</a> to write a review.</p>
        @endauth

        <div class="mt-10 grid gap-8">
            @forelse ($product->reviews as $review)
                <div class="border-b border-line pb-8 last:border-0">
                    <div class="flex items-center justify-between gap-3">
                        <p class="font-medium text-ink">{{ $review->user->name }}</p>
                        <x-star-rating :rating="$review->rating" />
                    </div>
                    @if ($review->title)
                        <p class="mt-3 font-serif text-lg text-ink">{{ $review->title }}</p>
                    @endif
                    <p class="mt-2 text-sm leading-relaxed text-stone">{{ $review->body }}</p>
                    <p class="mt-3 text-xs text-stone-light">{{ $review->created_at->format('d M Y') }}</p>
                </div>
            @empty
                <p class="text-sm text-stone">No reviews yet &mdash; be the first to share your fit.</p>
            @endforelse
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="border-t border-line bg-cream-dim">
            <div class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <x-ui.section-heading eyebrow="Continue the edit" :heading="'More from '.$product->category->name" />
                    <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="motion-press text-sm font-medium text-ink hover:opacity-70">View category</a>
                </div>
                <div class="mt-10 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($relatedProducts as $relatedProduct)
                        <x-product-card :product="$relatedProduct" :wishlisted="in_array($relatedProduct->id, $wishlistedProductIds, true)" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.storefront>
