@props(['title' => 'Admin - Luma Lens'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-cream-dim font-sans text-ink antialiased">
        <div class="flex min-h-screen flex-col lg:flex-row">
            <aside class="flex shrink-0 flex-col justify-between border-b border-white/10 bg-ink px-5 py-6 text-white lg:w-64 lg:border-b-0 lg:border-r lg:py-8">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                        <span>
                            <span class="block font-serif text-lg text-cream">Luma Lens</span>
                            <span class="block text-[0.65rem] font-medium uppercase tracking-widest text-cream/50">Admin console</span>
                        </span>
                    </a>

                    <nav class="mt-8 grid gap-1 text-sm font-bold lg:mt-10">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.dashboard') ? 'bg-cream text-accent shadow-sm' : 'text-white/80 hover:bg-cream/10 hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 12.5 12 5l8 7.5M6 10.5V19a1 1 0 0 0 1 1h3v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5h3a1 1 0 0 0 1-1v-8.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.products.*') ? 'bg-cream text-accent shadow-sm' : 'text-white/80 hover:bg-cream/10 hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20.5 12.5 12.5 20.5a1.5 1.5 0 0 1-2.12 0l-6.88-6.88a1.5 1.5 0 0 1 0-2.12L11.5 3.5a2 2 0 0 1 1.5-.6l5.9.3a1.5 1.5 0 0 1 1.4 1.4l.3 5.9a2 2 0 0 1-.1 2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                <circle cx="15.5" cy="8.5" r="1.25" fill="currentColor" />
                            </svg>
                            Products
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.orders.*') ? 'bg-cream text-accent shadow-sm' : 'text-white/80 hover:bg-cream/10 hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M6 3.5h12a.5.5 0 0 1 .5.5v16.2a.4.4 0 0 1-.62.33l-2.13-1.42-2.13 1.42a.4.4 0 0 1-.44 0l-2.13-1.42-2.13 1.42a.4.4 0 0 1-.44 0l-2.13-1.42L4.12 20.5A.4.4 0 0 1 3.5 20.2V4a.5.5 0 0 1 .5-.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                <path d="M7.5 8h9M7.5 11.5h9M7.5 15h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                            </svg>
                            Orders
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.categories.*') ? 'bg-cream text-accent shadow-sm' : 'text-white/80 hover:bg-cream/10 hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <rect x="3.5" y="3.5" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.8" />
                                <rect x="13.5" y="3.5" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.8" />
                                <rect x="3.5" y="13.5" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.8" />
                                <rect x="13.5" y="13.5" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.8" />
                            </svg>
                            Categories
                        </a>
                        <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.coupons.*') ? 'bg-cream text-accent shadow-sm' : 'text-white/80 hover:bg-cream/10 hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 10.5V6a1.5 1.5 0 0 1 1.5-1.5H10l10 10-6 6L4 10.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                <circle cx="8" cy="8" r="1.25" fill="currentColor" />
                            </svg>
                            Coupons
                        </a>
                        <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.customers.*') ? 'bg-cream text-accent shadow-sm' : 'text-white/80 hover:bg-cream/10 hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="1.8" />
                                <path d="M3.5 19c.6-3.4 3-5.3 5.5-5.3s4.9 1.9 5.5 5.3M16 8.5a2.5 2.5 0 1 0 0-5M18.5 19c-.4-2.3-1.6-3.9-3.3-4.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            Customers
                        </a>
                        <a href="{{ route('admin.chat-messages.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.chat-messages.*') ? 'bg-cream text-accent shadow-sm' : 'text-white/80 hover:bg-cream/10 hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v9a2.5 2.5 0 0 1-2.5 2.5H10l-4.5 4v-4H6.5A2.5 2.5 0 0 1 4 14.5v-9Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            </svg>
                            Messages
                            @if (($unreadMessageCount ?? 0) > 0)
                                <span class="ml-auto rounded-full bg-error px-2 py-0.5 text-xs font-medium text-cream">{{ $unreadMessageCount }}</span>
                            @endif
                        </a>
                    </nav>
                </div>

                <div class="mt-6 grid gap-3 border-t border-white/10 pt-5 lg:mt-10">
                    @auth
                        <div class="flex items-center gap-3 rounded-xl bg-cream/5 px-3 py-2.5">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-cream/10 text-sm font-black uppercase">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-bold">{{ auth()->user()->name }}</span>
                                <span class="block truncate text-xs text-white/50">{{ auth()->user()->email }}</span>
                            </span>
                        </div>
                    @endauth
                    <div class="flex items-center gap-4 px-1 text-xs font-bold uppercase tracking-wide text-white/60">
                        <a href="{{ route('products.index') }}" class="hover:text-white">View storefront</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="hover:text-white">Logout</button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex-1">
                @if (session('status'))
                    <div class="border-b border-success/30 bg-success-tint px-6 py-3 text-sm font-medium text-success">
                        {{ session('status') }}
                    </div>
                @endif

                <main class="mx-auto max-w-[100rem] px-5 py-7 sm:px-8 sm:py-9">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
