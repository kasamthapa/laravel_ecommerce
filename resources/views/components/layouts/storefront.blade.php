@props(['title' => 'Luma Lens - Independent Eyewear', 'cartCount' => 0])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Luma Lens - Independent Eyewear' }}</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-zinc-950 antialiased">
        <div class="min-h-screen">
            <div class="bg-[#092b83] text-white">
                <div class="mx-auto grid min-h-14 max-w-[120rem] grid-cols-1 items-center gap-3 px-4 py-3 text-sm font-black sm:grid-cols-[1fr_auto_1fr] sm:px-8">
                    <div class="hidden items-center gap-3 sm:flex">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 15.5c0-2.5 1.9-4.5 4.2-4.5 2.2 0 3.8 1.6 4.8 4 1-2.4 2.6-4 4.8-4 2.3 0 4.2 2 4.2 4.5S19.1 20 16.8 20c-2.2 0-3.8-1.6-4.8-4-1 2.4-2.6 4-4.8 4C4.9 20 3 18 3 15.5Z" stroke="currentColor" stroke-width="1.8" />
                            <path d="M8 11l1-4M16 11l-1-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        <span>Premium eyewear, starting at Rs. 8,800</span>
                    </div>

                    <a href="{{ route('products.index') }}" class="text-center text-[1.35rem] font-black uppercase tracking-[0.24em]">
                        Luma Lens
                    </a>

                    <div class="hidden items-center justify-end gap-3 sm:flex">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 8c3.5 0 7 2.1 7 7M7 13c2 0 4 1.2 4 4M6 18h.01M13 6c2.8 0 5 2.2 5 5 0 2.1-1.3 3.9-3.1 4.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        <span>Secure checkout powered by Khalti</span>
                    </div>
                </div>
            </div>

            <header class="sticky top-0 z-30 border-b border-zinc-200 bg-white">
                <div data-scroll-progress class="scroll-progress"></div>
                <div class="mx-auto flex max-w-[120rem] items-center justify-between gap-5 px-4 py-5 sm:px-8">
                    <nav class="hidden items-center gap-6 text-base font-black text-zinc-800 min-[1800px]:flex">
                        <a href="{{ route('products.index', ['category' => 'optical-frames']) }}#collection" class="hover:text-[#092b83]">Eyeglasses</a>
                        <a href="{{ route('products.index', ['category' => 'sunglasses']) }}#collection" class="hover:text-[#092b83]">Sunglasses</a>
                        <a href="{{ route('products.index', ['category' => 'blue-light']) }}#collection" class="hover:text-[#092b83]">Contacts</a>
                        <a href="#materials" class="hover:text-[#092b83]">Eye exams</a>
                        <a href="#materials" class="hover:text-[#092b83]">Insurance</a>
                        <a href="#quiz" class="hover:text-[#092b83]">Accessories</a>
                        <a href="{{ route('products.index') }}#shape" class="hover:text-[#092b83]">Style quiz</a>
                        <a href="{{ route('products.index', ['q' => 'lightweight']) }}#collection" class="hover:text-[#092b83]">Intelligent Eyewear</a>
                    </nav>

                    <a href="{{ route('products.index') }}" class="text-lg font-black uppercase tracking-[0.18em] text-[#092b83] min-[1800px]:hidden">Luma Lens</a>

                    <nav class="flex shrink-0 items-center justify-end gap-4 text-sm font-semibold">
                        @guest
                            <a href="{{ route('login') }}" class="motion-press inline-flex items-center gap-2 rounded-full border-2 border-zinc-950 px-4 py-2 text-base font-black text-zinc-950 hover:border-[#092b83] hover:text-[#092b83]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.8" />
                                    <path d="M5 20c.8-3.7 3.4-5.8 7-5.8s6.2 2.1 7 5.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                </svg>
                                <span class="hidden sm:inline">Sign in</span>
                            </a>
                            <a href="{{ route('register') }}" class="motion-press hidden rounded-full bg-[#092b83] px-4 py-2 text-base font-black text-white hover:bg-[#0f5be8] md:inline-flex">Create account</a>
                        @endguest
                        @auth
                            <span class="hidden max-w-72 shrink-0 overflow-hidden text-ellipsis whitespace-nowrap rounded-full border-2 border-zinc-950 px-4 py-2 text-base font-black text-zinc-950 lg:inline-flex">Hi, {{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="motion-press rounded-full px-3 py-2 text-zinc-700 hover:bg-zinc-100 hover:text-zinc-950">Logout</button>
                            </form>
                        @endauth
                        <a href="{{ route('products.index') }}#collection" class="motion-press hidden text-zinc-950 hover:text-[#092b83] sm:inline-flex" aria-label="Search">
                            <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="10.5" cy="10.5" r="6.5" stroke="currentColor" stroke-width="1.8" />
                                <path d="M16 16l4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                        </a>
                        <a href="{{ route('products.index') }}#collection" class="motion-press hidden text-zinc-950 hover:text-[#092b83] sm:inline-flex" aria-label="Wishlist">
                            <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 20s-7-4.3-8.7-9.1C2.1 7.4 4.5 4.5 7.7 4.5c1.8 0 3.4 1 4.3 2.4.9-1.4 2.5-2.4 4.3-2.4 3.2 0 5.6 2.9 4.4 6.4C19 15.7 12 20 12 20Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <a href="{{ route('cart.index') }}" class="motion-press inline-flex items-center gap-2 text-zinc-950 hover:text-[#092b83]" aria-label="Cart">
                            <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 6h15l-1.6 8.2a2 2 0 0 1-2 1.6H8.1a2 2 0 0 1-2-1.6L4.6 3H2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="9" cy="20" r="1.5" fill="currentColor" />
                                <circle cx="17" cy="20" r="1.5" fill="currentColor" />
                            </svg>
                            <span class="rounded-full bg-[#092b83] px-1.5 py-0.5 text-xs font-black text-white">{{ $cartCount ?? 0 }}</span>
                        </a>
                    </nav>
                </div>
            </header>

            @if (session('status'))
                <div data-flash-message class="border-b border-emerald-200 bg-emerald-50 px-4 py-3 text-center text-sm font-medium text-emerald-900 transition-opacity duration-500">
                    {{ session('status') }}
                </div>
            @endif

            <main>
                {{ $slot }}
            </main>

            <section class="border-t border-zinc-200 bg-[#eef7fb]">
                <div class="mx-auto grid max-w-7xl gap-5 px-4 py-10 sm:px-6 md:grid-cols-[1fr_auto] md:items-center lg:px-8">
                    <div data-motion-reveal>
                        <h2 class="text-2xl font-black text-[#092b83]">Get the inside scoop on new frames and offers</h2>
                        <p class="mt-2 text-sm text-zinc-600">No clutter, just new arrivals, fit notes, and Luma Lens updates.</p>
                    </div>
                    <form data-motion-reveal class="grid gap-3 sm:grid-cols-[18rem_auto]">
                        <label class="sr-only" for="footer-email">Email address</label>
                        <input id="footer-email" type="email" placeholder="Email address" class="rounded-full border border-zinc-300 bg-white px-4 py-3 text-sm font-medium outline-none focus:border-[#092b83] focus:ring-2 focus:ring-[#092b83]/20">
                        <button class="motion-press rounded-full bg-[#092b83] px-5 py-3 text-sm font-black text-white hover:bg-zinc-950">Sign up</button>
                    </form>
                </div>
            </section>

            <footer class="border-t border-zinc-200 bg-white text-zinc-950">
                <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-[1.1fr_0.7fr_0.7fr_0.7fr_0.9fr] lg:px-8">
                    <div data-motion-reveal>
                        <div class="flex items-center gap-3">
                            <span class="grid h-10 w-10 place-items-center rounded-full border border-[#092b83] bg-[#eef7fb] text-sm font-black text-[#092b83]">LL</span>
                            <div>
                                <p class="font-black">Luma Lens</p>
                                <p class="text-sm text-zinc-500">Independent eyewear, edited tightly.</p>
                            </div>
                        </div>
                        <p class="mt-5 max-w-md text-sm leading-6 text-zinc-600">Optical frames, sunglasses, and screen-ready lenses selected for fit, finish, and everyday wear in Nepal.</p>
                    </div>
                    <div data-motion-reveal>
                        <p class="text-sm font-black text-[#092b83]">Products</p>
                        <div class="mt-4 grid gap-2 text-sm text-zinc-600">
                            <a href="{{ route('products.index') }}#collection" class="hover:text-zinc-950">Eyeglasses</a>
                            <a href="{{ route('products.index', ['category' => 'sunglasses']) }}#collection" class="hover:text-zinc-950">Sunglasses</a>
                            <a href="{{ route('products.index', ['category' => 'blue-light']) }}#collection" class="hover:text-zinc-950">Blue light</a>
                        </div>
                    </div>
                    <div data-motion-reveal>
                        <p class="text-sm font-black text-[#092b83]">Shop online</p>
                        <div class="mt-4 grid gap-2 text-sm text-zinc-600">
                            <a href="{{ route('products.index') }}#quiz" class="hover:text-zinc-950">Style quiz</a>
                            <a href="{{ route('products.index') }}#shape" class="hover:text-zinc-950">Frame shapes</a>
                            <a href="{{ route('cart.index') }}" class="hover:text-zinc-950">Cart</a>
                            @guest
                                <a href="{{ route('login') }}" class="hover:text-zinc-950">Login</a>
                            @endguest
                        </div>
                    </div>
                    <div data-motion-reveal>
                        <p class="text-sm font-black text-[#092b83]">Visit a store</p>
                        <div class="mt-4 grid gap-2 text-sm text-zinc-600">
                            <p>Kathmandu pickup ready</p>
                            <p>Sun to screen collections</p>
                            <p>Fast local checkout</p>
                        </div>
                    </div>
                    <div data-motion-reveal>
                        <p class="text-sm font-black text-[#092b83]">Checkout</p>
                        <div class="mt-4 rounded-lg border border-zinc-200 bg-[#fbfaf7] p-4">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm font-bold text-zinc-600">Powered by</span>
                                <img src="{{ asset('images/khalti_logo.svg') }}" alt="Khalti" class="h-5 w-auto">
                            </div>
                            <p class="mt-3 text-xs leading-5 text-zinc-500">Orders are confirmed after Khalti payment verification.</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center gap-x-3 gap-y-1 border-t border-zinc-200 px-4 py-4 text-center text-xs text-zinc-500">
                    <span>© {{ now()->year }} Luma Lens. Independent eyewear commerce for Nepal.</span>
                    <a href="https://github.com/KhronosGroup/glTF-Sample-Assets/tree/main/Models/SunglassesKhronos" target="_blank" rel="noreferrer" class="text-zinc-500 underline-offset-4 hover:text-[#092b83] hover:underline">3D eyewear: DGG GmbH, CC BY 4.0</a>
                </div>
            </footer>
        </div>
    </body>
</html>
