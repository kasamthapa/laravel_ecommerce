@props(['title' => 'Luma Lens - Independent Eyewear', 'cartCount' => 0])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Luma Lens - Independent Eyewear' }}</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    {{--
        The real homepage (products.index), the /shop catalog (shop), the
        PDP (products.show), the cart (cart.index), checkout
        (checkout.create), and the order confirmation page
        (checkout.confirmation) all get the chosen light design system now.
        Every other storefront page (account, admin) keeps today's dark
        tokens, completely untouched — see the body.theme-light override
        block in app.css for how the same utility classes below re-skin
        from one scoped class alone.
    --}}
    <body class="storefront {{ request()->routeIs('products.index', 'shop', 'products.show', 'cart.index', 'checkout.create', 'checkout.confirmation') ? 'theme-light' : '' }} bg-black font-sans text-bone antialiased">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-sm focus:bg-volt focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-bone">Skip to content</a>

        <div class="flex min-h-screen flex-col">
            <div class="border-b border-hairline bg-charcoal">
                <p class="mx-auto max-w-[100rem] px-4 py-2.5 text-center text-xs font-medium tracking-wide text-smoke sm:px-8">
                    Free shipping on orders over Rs. 10,000 &middot; Secure checkout with Khalti
                </p>
            </div>

            <header class="sticky top-0 z-30 border-b border-hairline bg-black">
                <div class="mx-auto flex max-w-[100rem] items-center justify-between gap-4 px-4 py-5 sm:px-8">
                    <div class="flex shrink-0 items-center gap-3">
                        <button type="button" data-mobile-menu-toggle aria-expanded="false" aria-controls="mobile-menu" class="motion-invert grid h-10 w-10 place-items-center border border-hairline text-bone lg:hidden">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <span class="sr-only">Menu</span>
                        </button>
                        <a href="{{ route('products.index') }}"><x-brand-mark icon-class="h-5 w-8 text-volt" text-class="font-display text-xl font-bold uppercase tracking-wide text-bone" /></a>
                    </div>

                    <nav aria-label="Primary" class="hidden items-center gap-8 text-sm font-medium text-bone lg:flex">
                        <a href="{{ route('shop') }}" class="motion-invert border-b border-transparent pb-0.5 hover:border-volt hover:text-volt {{ request()->routeIs('shop') && ! request('category') ? 'border-volt text-volt' : '' }}">Shop all</a>
                        <a href="{{ route('shop', ['category' => 'optical-frames']) }}" class="motion-invert border-b border-transparent pb-0.5 hover:border-volt hover:text-volt">Eyeglasses</a>
                        <a href="{{ route('shop', ['category' => 'sunglasses']) }}" class="motion-invert border-b border-transparent pb-0.5 hover:border-volt hover:text-volt">Sunglasses</a>
                        <a href="{{ route('shop', ['category' => 'blue-light']) }}" class="motion-invert border-b border-transparent pb-0.5 hover:border-volt hover:text-volt">Blue light</a>
                    </nav>

                    <nav aria-label="Account" class="flex shrink-0 items-center justify-end gap-5 text-sm font-medium">
                        @guest
                            <a href="{{ route('login') }}" class="motion-invert hidden text-bone hover:text-volt sm:inline">Sign in</a>
                        @endguest
                        @auth
                            <div class="group relative hidden lg:block">
                                <button type="button" class="motion-invert max-w-40 truncate text-bone hover:text-volt">{{ auth()->user()->name }}</button>
                                <div class="invisible absolute right-0 top-full z-40 w-48 border border-hairline bg-charcoal p-1 opacity-0 shadow-lg transition-opacity duration-200 ease-out group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100">
                                    <a href="{{ route('account.orders.index') }}" class="block px-3 py-2.5 text-bone hover:bg-black">My orders</a>
                                    <a href="{{ route('wishlist.index') }}" class="block px-3 py-2.5 text-bone hover:bg-black">Wishlist</a>
                                    <a href="{{ route('track.create') }}" class="block px-3 py-2.5 text-bone hover:bg-black">Track an order</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="block w-full px-3 py-2.5 text-left text-bone hover:bg-black">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @endauth
                        <a href="{{ auth()->check() ? route('wishlist.index') : route('login') }}" class="motion-invert hidden text-bone hover:text-volt sm:inline-flex" aria-label="Wishlist">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 20s-7-4.3-8.7-9.1C2.1 7.4 4.5 4.5 7.7 4.5c1.8 0 3.4 1 4.3 2.4.9-1.4 2.5-2.4 4.3-2.4 3.2 0 5.6 2.9 4.4 6.4C19 15.7 12 20 12 20Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <a href="{{ route('cart.index') }}" class="motion-invert inline-flex items-center gap-1.5 text-bone hover:text-volt" aria-label="Cart">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 6h15l-1.6 8.2a2 2 0 0 1-2 1.6H8.1a2 2 0 0 1-2-1.6L4.6 3H2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="9" cy="20" r="1.3" fill="currentColor" />
                                <circle cx="17" cy="20" r="1.3" fill="currentColor" />
                            </svg>
                            <livewire:cart-count :count="$cartCount ?? 0" />
                        </a>
                    </nav>
                </div>

                <div id="mobile-menu" data-mobile-menu class="hidden border-t border-hairline bg-black px-4 py-3 lg:hidden">
                    <nav aria-label="Mobile" class="grid gap-1 text-base text-bone">
                        <a href="{{ route('shop') }}" class="px-3 py-3 hover:bg-charcoal">Shop all</a>
                        <a href="{{ route('shop', ['category' => 'optical-frames']) }}" class="px-3 py-3 hover:bg-charcoal">Eyeglasses</a>
                        <a href="{{ route('shop', ['category' => 'sunglasses']) }}" class="px-3 py-3 hover:bg-charcoal">Sunglasses</a>
                        <a href="{{ route('shop', ['category' => 'blue-light']) }}" class="px-3 py-3 hover:bg-charcoal">Blue light</a>
                        <div class="my-1 border-t border-hairline"></div>
                        <a href="{{ route('cart.index') }}" class="flex items-center gap-2 px-3 py-3 hover:bg-charcoal">Cart <livewire:cart-count :count="$cartCount ?? 0" /></a>
                        <a href="{{ route('track.create') }}" class="px-3 py-3 hover:bg-charcoal">Track an order</a>
                        @guest
                            <a href="{{ route('login') }}" class="px-3 py-3 hover:bg-charcoal">Sign in</a>
                            <a href="{{ route('register') }}" class="px-3 py-3 hover:bg-charcoal">Create account</a>
                        @endguest
                        @auth
                            <a href="{{ route('account.orders.index') }}" class="px-3 py-3 hover:bg-charcoal">My orders</a>
                            <a href="{{ route('wishlist.index') }}" class="px-3 py-3 hover:bg-charcoal">Wishlist</a>
                            <span class="px-3 py-2 text-sm text-smoke">Signed in as {{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full px-3 py-3 text-left hover:bg-charcoal">Logout</button>
                            </form>
                        @endauth
                    </nav>
                </div>
            </header>

            @if (session('status'))
                <div data-flash-message role="status" class="border-b border-hairline bg-volt-tint px-4 py-3 text-center text-sm text-bone transition-opacity duration-500">
                    {{ session('status') }}
                </div>
            @endif

            <main id="main-content" class="flex-1">
                {{ $slot }}
            </main>

            <section class="border-t border-hairline bg-charcoal">
                <div class="mx-auto grid max-w-[100rem] gap-5 px-4 py-10 sm:px-8 md:grid-cols-[1fr_auto] md:items-center">
                    <div>
                        <h2 class="font-display text-2xl font-bold uppercase tracking-wide text-bone">Get the inside scoop on new frames and offers</h2>
                        <p class="mt-2 text-sm text-smoke">No clutter, just new arrivals, fit notes, and Luma Lens updates.</p>
                    </div>
                    <form class="grid gap-3 sm:grid-flow-col sm:items-end">
                        <label class="grid gap-2 text-sm font-medium text-bone" for="footer-email">
                            <span class="sr-only">Email address</span>
                            <input id="footer-email" type="email" placeholder="Email address" class="w-64 border-0 border-b border-smoke-dim bg-transparent px-0 py-2.5 text-sm text-bone outline-none transition-colors duration-200 ease-out placeholder:text-smoke-dim focus:border-volt">
                        </label>
                        <x-ui.button type="submit">Sign up</x-ui.button>
                    </form>
                </div>
            </section>

            <footer class="border-t border-hairline bg-charcoal text-bone">
                <div class="mx-auto grid max-w-[100rem] gap-10 px-4 py-14 sm:px-8 md:grid-cols-[1.2fr_0.8fr_0.8fr_0.8fr_1fr]">
                    <div>
                        <x-brand-mark icon-class="h-4 w-7 text-volt" text-class="font-display text-xl font-bold uppercase tracking-wide text-bone" />
                        <p class="mt-4 max-w-xs text-sm leading-relaxed text-bone/70">Optical frames, sunglasses, and screen-ready lenses selected for fit, finish, and everyday wear in Nepal.</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.14em] text-bone/60">Products</p>
                        <div class="mt-4 grid gap-2.5 text-sm text-bone">
                            <a href="{{ route('shop', ['category' => 'optical-frames']) }}" class="motion-invert w-fit hover:text-volt">Eyeglasses</a>
                            <a href="{{ route('shop', ['category' => 'sunglasses']) }}" class="motion-invert w-fit hover:text-volt">Sunglasses</a>
                            <a href="{{ route('shop', ['category' => 'blue-light']) }}" class="motion-invert w-fit hover:text-volt">Blue light</a>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.14em] text-bone/60">Shop online</p>
                        <div class="mt-4 grid gap-2.5 text-sm text-bone">
                            <a href="{{ route('shop') }}" class="motion-invert w-fit hover:text-volt">Shop all</a>
                            <a href="{{ route('products.index') }}#shape" class="motion-invert w-fit hover:text-volt">Frame shapes</a>
                            <a href="{{ route('cart.index') }}" class="motion-invert w-fit hover:text-volt">Cart</a>
                            <a href="{{ route('track.create') }}" class="motion-invert w-fit hover:text-volt">Track an order</a>
                            @guest
                                <a href="{{ route('login') }}" class="motion-invert w-fit hover:text-volt">Login</a>
                            @endguest
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.14em] text-bone/60">How we ship</p>
                        <div class="mt-4 grid gap-2.5 text-sm text-bone/70">
                            <p>Nationwide delivery</p>
                            <p>Hand-checked before dispatch</p>
                            <p>Khalti secure checkout</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.14em] text-bone/60">Checkout</p>
                        <div class="mt-4 border border-bone/20 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-medium uppercase tracking-wide text-bone/60">Powered by</span>
                                <img src="{{ asset('images/khalti_logo.svg') }}" alt="Khalti" class="h-5 w-auto">
                            </div>
                            <p class="mt-3 text-xs leading-5 text-bone/70">Orders are confirmed after Khalti payment verification.</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center gap-x-3 gap-y-1 border-t border-bone/10 px-4 py-4 text-center text-xs text-bone/60">
                    <span>&copy; {{ now()->year }} Luma Lens. Independent eyewear commerce for Nepal.</span>
                    <a href="https://github.com/KhronosGroup/glTF-Sample-Assets/tree/main/Models/SunglassesKhronos" target="_blank" rel="noreferrer" class="motion-invert text-bone/60 underline-offset-4 hover:underline hover:text-volt">3D eyewear: DGG GmbH, CC BY 4.0</a>
                </div>
            </footer>
        </div>

        <livewire:chat-widget />

        @livewireScripts
    </body>
</html>
