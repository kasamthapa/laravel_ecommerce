<x-layouts.storefront title="Login - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto flex min-h-[calc(100vh-16rem)] max-w-md items-center px-4 py-14 sm:px-8">
        <div class="motion-fade w-full">
            <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Member access</p>
            <h1 class="mt-3 font-serif text-3xl text-ink">Welcome back</h1>
            <p class="mt-2 text-sm text-stone">Sign in to keep checkout faster and hold your preferred eyewear picks in one place.</p>

            <form method="POST" action="{{ route('login') }}" data-loading-form class="mt-8 grid gap-5">
                @csrf
                <x-ui.input label="Email" name="email" type="email" required autofocus />
                <x-ui.input label="Password" name="password" type="password" required />
                <label class="flex items-center gap-2 text-sm text-ink">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 border-line accent-accent">
                    Remember me
                </label>

                @if ($errors->any())
                    <p class="text-sm text-error">{{ $errors->first() }}</p>
                @endif

                <x-ui.button type="submit" data-loading-label="Signing in…" class="w-full">Sign in</x-ui.button>
            </form>

            <p class="mt-6 text-sm text-stone">
                New here?
                <a href="{{ route('register') }}" class="motion-press font-medium text-ink underline decoration-1 underline-offset-4">Create an account</a>
            </p>
        </div>
    </section>
</x-layouts.storefront>
