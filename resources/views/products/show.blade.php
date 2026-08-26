<x-layouts.storefront :title="$product->name.' - Luma Lens'" :cart-count="$cartCount">
    <section class="mx-auto grid max-w-[100rem] gap-10 px-4 py-10 sm:px-8 lg:grid-cols-2 lg:gap-16 lg:py-14">
        <div class="grid gap-4 motion-fade" data-product-view>
            @if ($product->model_path)
                <div class="flex gap-6 border-b border-hairline text-sm" role="tablist" aria-label="Product view">
                    <button type="button" data-view-tab="photos" role="tab" aria-selected="true" class="motion-invert border-b-2 border-volt pb-3 font-medium text-bone">Photos</button>
                    <button type="button" data-view-tab="3d" role="tab" aria-selected="false" class="motion-invert border-b-2 border-transparent pb-3 text-smoke hover:text-bone">3D View</button>
                    <button type="button" data-view-tab="tryon" role="tab" aria-selected="false" class="motion-invert border-b-2 border-transparent pb-3 text-smoke hover:text-bone">Try On</button>
                </div>
            @endif

            <div data-view-panel="photos" data-gallery data-images="{{ json_encode($product->gallery()) }}">
                <div data-gallery-stage class="gallery-stage relative aspect-square overflow-hidden bg-charcoal">
                    <img data-gallery-main src="{{ $product->gallery()[0] }}" alt="{{ $product->name }}" class="h-full w-full object-cover {{ $product->stock < 1 ? 'grayscale' : '' }}">

                    @if ($product->model_path)
                        <button type="button" data-view-tab-proxy="tryon" class="motion-invert absolute inset-x-0 top-4 z-[2] mx-auto flex w-fit items-center gap-2 rounded-full border border-gold bg-black/90 px-4 py-2 text-xs font-medium text-bone shadow-lg">
                            <svg class="h-4 w-4 text-gold" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="6.5" cy="13" r="3.5" stroke="currentColor" stroke-width="1.6" />
                                <circle cx="17.5" cy="13" r="3.5" stroke="currentColor" stroke-width="1.6" />
                                <path d="M10 12.2c.6-1 1.4-1 2 0M3 12.5l-1.5-.6M21 12.5l1.5-.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                            </svg>
                            Try it on
                        </button>
                    @endif
                </div>

                @if (count($product->gallery()) > 1)
                    <div class="flex gap-3">
                        @foreach ($product->gallery() as $index => $image)
                            <button type="button" data-gallery-thumb data-index="{{ $index }}" aria-label="Show image {{ $index + 1 }} of {{ $product->name }}" class="h-20 w-20 shrink-0 overflow-hidden border {{ $index === 0 ? 'border-volt' : 'border-hairline' }}">
                                <img src="{{ $image }}" alt="" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($product->model_path)
                @php $modelTint = $product->modelTint(); @endphp

                <div data-view-panel="3d" class="hidden">
                    <div class="glasses-stage aspect-square min-h-0 overflow-hidden bg-charcoal" data-glasses-viewer data-model-path="{{ asset($product->model_path) }}" @if ($modelTint) data-frame-tint="{{ $modelTint['frame'] }}" data-lens-tint="{{ $modelTint['lens'] }}" @endif>
                        <canvas class="glasses-canvas" data-glasses-canvas></canvas>
                        <img src="{{ $product->gallery()[0] }}" alt="" class="glasses-3d">
                        <div data-glasses-skeleton class="absolute inset-0 z-[3] animate-pulse bg-charcoal" aria-hidden="true"></div>
                    </div>
                    <p class="mt-3 text-center text-xs text-smoke">Drag to rotate &middot; double-click to reset</p>
                </div>

                <div data-view-panel="tryon" class="hidden">
                    <div class="glasses-stage aspect-square min-h-0 overflow-hidden bg-charcoal" data-face-tryon data-model-path="{{ asset($product->model_path) }}" @if ($modelTint) data-frame-tint="{{ $modelTint['frame'] }}" data-lens-tint="{{ $modelTint['lens'] }}" @endif>
                        <video data-tryon-video class="tryon-mirror absolute inset-0 h-full w-full object-cover" autoplay muted playsinline></video>
                        <canvas class="glasses-canvas" data-tryon-canvas></canvas>
                        <div data-glasses-skeleton class="absolute inset-0 z-[3] animate-pulse bg-charcoal" aria-hidden="true"></div>
                        <div data-tryon-status class="absolute inset-0 z-[4] hidden flex-col items-center justify-center gap-3 bg-charcoal px-6 text-center" aria-live="polite">
                            <p data-tryon-status-text class="font-display text-lg font-semibold text-bone"></p>
                            <button type="button" data-tryon-retry class="hidden motion-invert border border-bone px-4 py-2 text-sm font-medium text-bone hover:bg-bone hover:text-black">Try again</button>
                        </div>

                        @if (request()->query('debug') === '1')
                            <div data-tryon-debug class="absolute right-2 top-2 z-[5] max-h-[calc(100%-1rem)] w-56 space-y-2.5 overflow-y-auto border border-hairline bg-black/95 p-3 text-xs text-bone">
                                <p class="font-medium uppercase tracking-wide text-smoke">Try On debug &mdash; 6DOF</p>

                                <label class="block">
                                    <span class="flex items-center justify-between">Scale <span data-tryon-debug-value="scale">1.01</span></span>
                                    <input type="range" data-tryon-debug-control="scale" min="0.3" max="3" step="0.01" value="1.01" class="w-full">
                                </label>

                                <p class="pt-1 font-medium uppercase tracking-wide text-smoke">Position (cm)</p>

                                <label class="block">
                                    <span class="flex items-center justify-between">X (left/right) <span data-tryon-debug-value="offsetX">-0.20</span></span>
                                    <input type="range" data-tryon-debug-control="offsetX" min="-10" max="10" step="0.1" value="-0.2" class="w-full">
                                </label>

                                <label class="block">
                                    <span class="flex items-center justify-between">Y (up/down) <span data-tryon-debug-value="offsetY">2.60</span></span>
                                    <input type="range" data-tryon-debug-control="offsetY" min="-10" max="10" step="0.1" value="2.6" class="w-full">
                                </label>

                                <label class="block">
                                    <span class="flex items-center justify-between">Z (near/far) <span data-tryon-debug-value="offsetZ">-3.70</span></span>
                                    <input type="range" data-tryon-debug-control="offsetZ" min="-10" max="10" step="0.1" value="-3.7" class="w-full">
                                </label>

                                <p class="pt-1 font-medium uppercase tracking-wide text-smoke">Base rotation (&deg;)</p>

                                <label class="block">
                                    <span class="flex items-center justify-between">Pitch <span data-tryon-debug-value="pitchDeg">4.00</span></span>
                                    <input type="range" data-tryon-debug-control="pitchDeg" min="-180" max="180" step="1" value="4" class="w-full">
                                </label>

                                <label class="block">
                                    <span class="flex items-center justify-between">Yaw <span data-tryon-debug-value="yawDeg">3.00</span></span>
                                    <input type="range" data-tryon-debug-control="yawDeg" min="-180" max="180" step="1" value="3" class="w-full">
                                </label>

                                <label class="block">
                                    <span class="flex items-center justify-between">Roll <span data-tryon-debug-value="rollDeg">-1.00</span></span>
                                    <input type="range" data-tryon-debug-control="rollDeg" min="-180" max="180" step="1" value="-1" class="w-full">
                                </label>

                                <label class="block pt-1">
                                    <span class="flex items-center justify-between">Smoothing <span data-tryon-debug-value="lerp">0.40</span></span>
                                    <input type="range" data-tryon-debug-control="lerp" min="0.05" max="1" step="0.01" value="0.4" class="w-full">
                                </label>

                                <p class="pt-1 font-medium uppercase tracking-wide text-smoke">Occlusion</p>

                                <label class="block">
                                    <span class="flex items-center justify-between">Head size <span data-tryon-debug-value="occluderScale">1.00</span></span>
                                    <input type="range" data-tryon-debug-control="occluderScale" min="0.5" max="1.6" step="0.01" value="1" class="w-full">
                                </label>

                                <label class="block">
                                    <span class="flex items-center justify-between">Head depth (Z) <span data-tryon-debug-value="occluderZ">-13.20</span></span>
                                    <input type="range" data-tryon-debug-control="occluderZ" min="-25" max="5" step="0.1" value="-13.2" class="w-full">
                                </label>
                            </div>
                        @endif
                    </div>
                    <p class="mt-3 text-center text-xs text-smoke">Runs entirely in your browser &mdash; no photo or video ever leaves your device.</p>
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-7 motion-fade-slow lg:justify-center">
            <div>
                <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="motion-invert text-xs font-medium uppercase tracking-[0.14em] text-smoke hover:text-volt">{{ $product->category->name }}</a>
                <div class="mt-3 flex items-start justify-between gap-4">
                    <h1 class="font-display text-4xl font-bold uppercase leading-[0.95] text-bone sm:text-5xl">{{ $product->name }}</h1>
                    {{-- Secondary action, not the primary CTA — outline/stroke
                         treatment (wishlist-outline kills the shared component's
                         solid bg-black fill; see app.css), not a solid fill.
                         Add to Cart stays the one solid-accent pill on this page. --}}
                    @auth
                        <livewire:wishlist-button :product="$product" :wishlisted="in_array($product->id, $wishlistedProductIds, true)" size="h-11 w-11 shrink-0 border border-hairline text-lg wishlist-outline" :key="'wishlist-main-'.$product->id" />
                    @endauth
                </div>
                <a href="#reviews" class="mt-3 inline-block motion-invert">
                    <x-star-rating :rating="(float) ($product->reviews_avg_rating ?? 0)" :count="$product->reviews_count ?? 0" size="h-4 w-4" />
                </a>
            </div>

            <div class="flex items-end gap-3">
                <p class="text-2xl text-bone">Rs. {{ number_format((float) $product->price) }}</p>
                @if ($product->compare_at_price)
                    <p class="pb-0.5 text-base text-smoke-dim line-through">Rs. {{ number_format((float) $product->compare_at_price) }}</p>
                @endif
            </div>

            <p class="text-lg leading-relaxed text-bone-soft">{{ $product->description }}</p>

            <div id="pdp-buy-box" class="flex flex-wrap gap-3">
                @if ($product->stock < 1)
                    <x-ui.button variant="secondary" disabled class="cursor-not-allowed opacity-50">Sold out</x-ui.button>
                @else
                    <livewire:add-to-cart-form :product="$product" />
                @endif
            </div>

            @if ($product->stock >= 1 && $product->stock <= 5)
                <p class="text-sm text-smoke">Only {{ $product->stock }} left in stock.</p>
            @endif

            <ul class="grid gap-2 border-t border-hairline pt-6 text-xs text-smoke">
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-volt" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3.5 20 7v5.5c0 4.6-3.4 8.2-8 9-4.6-.8-8-4.4-8-9V7l8-3.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" /></svg>
                    Secure payment with Khalti
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-volt" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12a8 8 0 1 0 8-8M4 12h4M4 12l3-3M4 12l3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    14-day returns and exchanges
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-volt" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" /><circle cx="7.5" cy="18" r="1.5" fill="currentColor" /><circle cx="17.5" cy="18" r="1.5" fill="currentColor" /></svg>
                    Free shipping over Rs. 10,000, otherwise Rs. 250
                </li>
            </ul>
        </div>
    </section>

    @if ($product->stock >= 1)
        <div data-sticky-cta data-track="#pdp-buy-box" class="fixed inset-x-0 bottom-0 z-40 translate-y-full border-t border-hairline bg-black/95 px-4 py-3 backdrop-blur transition-transform duration-200 ease-out sm:hidden">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-bone">{{ $product->name }}</p>
                    <p class="text-sm text-smoke">Rs. {{ number_format((float) $product->price) }}</p>
                </div>
                <x-ui.button type="button" data-sticky-cta-button size="sm" class="shrink-0">Add to cart</x-ui.button>
            </div>
        </div>
    @endif

    {{--
        Light-section break: PDP spec facts get a deliberate pure-white
        panel, not another band in the page's own background.

        text-bone, not text-black: --color-black is the page-background
        role token and gets swapped to near-white inside body.theme-light
        (see app.css) — text-black would read as near-white-on-white here
        and be nearly invisible. text-bone is the token that resolves to
        near-black text under this same scope, which is what this section
        actually needs regardless of theme.
    --}}
    <section class="border-y border-hairline bg-white text-bone">
        <div class="mx-auto grid max-w-[100rem] gap-8 px-4 py-12 sm:px-8 sm:grid-cols-3">
            <div>
                <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-bone/70">Fit range</h2>
                <p class="mt-2 text-base text-bone">{{ implode(', ', $product->sizes) }}</p>
            </div>
            <div>
                <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-bone/70">Finishes</h2>
                <p class="mt-2 text-base text-bone">{{ implode(', ', $product->colors) }}</p>
            </div>
            <div>
                <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-bone/70">Details</h2>
                <p class="mt-2 text-base text-bone">Prescription-ready &middot; Free delivery over Rs. 10,000 &middot; Khalti secure checkout</p>
            </div>
        </div>
    </section>

    <section id="reviews" class="mx-auto max-w-3xl px-4 py-14 sm:px-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <x-ui.section-heading eyebrow="Customer reviews" heading="What shoppers say" />
            <x-star-rating :rating="(float) ($product->reviews_avg_rating ?? 0)" :count="$product->reviews_count ?? 0" size="h-4 w-4" />
        </div>

        @auth
            <form method="POST" action="{{ route('reviews.store', $product) }}" class="mt-8 grid gap-5 rounded-[6px] border border-hairline bg-charcoal p-6">
                <p class="font-display text-lg font-semibold text-bone">{{ $userReview ? 'Update your review' : 'Write a review' }}</p>
                @csrf
                <x-ui.select label="Rating" name="rating" class="w-40">
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected(old('rating', $userReview->rating ?? 5) == $i)>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </x-ui.select>
                <x-ui.input label="Title (optional)" name="title" :value="old('title', $userReview->title ?? '')" maxlength="120" />
                <x-ui.textarea label="Review" name="body" :value="old('body', $userReview->body ?? '')" required maxlength="2000" />
                @if ($errors->any())
                    <div class="border border-signal-bad/40 bg-black p-3 text-sm text-signal-bad">{{ $errors->first() }}</div>
                @endif
                <x-ui.button type="submit" class="w-fit">{{ $userReview ? 'Update review' : 'Submit review' }}</x-ui.button>
            </form>
        @else
            <p class="mt-8 rounded-[6px] border border-dashed border-hairline p-6 text-sm text-smoke"><a href="{{ route('login') }}" class="motion-invert font-medium text-volt hover:underline">Sign in</a> to write a review.</p>
        @endauth

        {{-- Individual reviews follow the same card convention as everywhere
             else (6px radius, hairline border, charcoal/#FCFBF8 surface)
             rather than the plain divider-list this used to be. --}}
        <div class="mt-10 grid gap-4">
            @forelse ($product->reviews as $review)
                <div class="rounded-[6px] border border-hairline bg-charcoal p-6">
                    <div class="flex items-center justify-between gap-3">
                        <p class="font-medium text-bone">{{ $review->user->name }}</p>
                        <x-star-rating :rating="$review->rating" />
                    </div>
                    @if ($review->title)
                        <p class="mt-3 font-display text-lg font-semibold text-bone">{{ $review->title }}</p>
                    @endif
                    <p class="mt-2 text-sm leading-relaxed text-smoke">{{ $review->body }}</p>
                    <p class="mt-3 text-xs text-smoke-dim">{{ $review->created_at->format('d M Y') }}</p>
                </div>
            @empty
                <p class="text-sm text-smoke">No reviews yet &mdash; be the first to share your fit.</p>
            @endforelse
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="border-t border-hairline bg-charcoal">
            <div class="mx-auto max-w-[100rem] px-4 py-14 sm:px-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <x-ui.section-heading eyebrow="Continue the edit" :heading="'More from '.$product->category->name" />
                    <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="motion-invert text-sm font-medium text-bone hover:text-volt">View category</a>
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
