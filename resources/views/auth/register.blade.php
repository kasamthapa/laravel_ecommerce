<x-layouts.storefront title="Sign Up - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto grid min-h-[calc(100vh-16rem)] max-w-6xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
        <div class="motion-fade flex flex-col justify-between border border-[#092b83] bg-[#eef7fb] p-8 text-zinc-950">
            <div>
                <p class="text-sm font-black uppercase">Start your edit</p>
                <h1 class="mt-5 text-5xl font-black leading-none">Make your eyewear shelf easier to revisit.</h1>
            </div>
            <p class="mt-10 max-w-md text-sm leading-6">Create an account for a smoother checkout and a cleaner path back to the frames you liked.</p>
        </div>

        <div class="motion-fade-slow motion-glow border border-zinc-950/10 bg-white p-6 sm:p-8">
            <h2 class="text-2xl font-black">Sign up</h2>
            <form method="POST" action="{{ route('register') }}" class="mt-6 grid gap-5">
                @csrf
                <label class="grid gap-2 text-sm font-bold">
                    Name
                    <input name="name" value="{{ old('name') }}" class="rounded-full border border-zinc-300 px-4 py-3 font-medium" required autofocus>
                </label>
                <label class="grid gap-2 text-sm font-bold">
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" class="rounded-full border border-zinc-300 px-4 py-3 font-medium" required>
                </label>
                <label class="grid gap-2 text-sm font-bold">
                    Password
                    <input type="password" name="password" class="rounded-full border border-zinc-300 px-4 py-3 font-medium" required>
                </label>
                <label class="grid gap-2 text-sm font-bold">
                    Confirm password
                    <input type="password" name="password_confirmation" class="rounded-full border border-zinc-300 px-4 py-3 font-medium" required>
                </label>

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-3 text-sm font-medium text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button class="motion-press rounded-full bg-zinc-950 px-5 py-3 font-black text-white hover:bg-[#092b83]">Create account</button>
            </form>

            <p class="mt-6 text-sm text-zinc-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-black text-[#092b83] hover:text-zinc-950">Login</a>
            </p>
        </div>
    </section>
</x-layouts.storefront>
