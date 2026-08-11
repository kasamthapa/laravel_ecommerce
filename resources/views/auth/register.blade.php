<x-layouts.storefront title="Sign Up - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto flex min-h-[calc(100vh-16rem)] max-w-md items-center px-4 py-14 sm:px-8">
        <div class="motion-fade w-full">
            <p class="text-xs font-medium uppercase tracking-[0.14em] text-stone">Start your edit</p>
            <h1 class="mt-3 font-serif text-3xl text-ink">Create an account</h1>
            <p class="mt-2 text-sm text-stone">A smoother checkout and a cleaner path back to the frames you liked.</p>

            <form method="POST" action="{{ route('register') }}" data-loading-form class="mt-8 grid gap-5">
                @csrf
                <x-ui.input label="Name" name="name" required autofocus />
                <x-ui.input label="Email" name="email" type="email" required />
                <x-ui.input label="Password" name="password" type="password" required hint="At least 8 characters." />
                <x-ui.input label="Confirm password" name="password_confirmation" type="password" required />

                @if ($errors->any())
                    <p class="text-sm text-error">{{ $errors->first() }}</p>
                @endif

                <x-ui.button type="submit" data-loading-label="Creating account…" class="w-full">Create account</x-ui.button>
            </form>

            <p class="mt-6 text-sm text-stone">
                Already have an account?
                <a href="{{ route('login') }}" class="motion-press font-medium text-ink underline decoration-1 underline-offset-4">Sign in</a>
            </p>
        </div>
    </section>
</x-layouts.storefront>
