<x-layouts.storefront title="Login - Luma Lens" :cart-count="$cartCount">
    <section class="mx-auto grid min-h-[calc(100vh-16rem)] max-w-6xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
        <div class="motion-fade flex flex-col justify-between border border-zinc-950 bg-zinc-950 p-8 text-white">
            <div>
                <p class="text-sm font-black uppercase text-[#eef7fb]">Member access</p>
                <h1 class="mt-5 text-5xl font-black leading-none">Welcome back to the frame wall.</h1>
            </div>
            <p class="mt-10 max-w-md text-sm leading-6 text-zinc-300">Sign in to keep checkout faster and hold your preferred eyewear picks in one place.</p>
        </div>

        <div class="motion-fade-slow motion-glow border border-zinc-950/10 bg-white p-6 sm:p-8">
            <h2 class="text-2xl font-black">Login</h2>
            <form method="POST" action="{{ route('login') }}" class="mt-6 grid gap-5">
                @csrf
                <label class="grid gap-2 text-sm font-bold">
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" class="rounded-full border border-zinc-300 px-4 py-3 font-medium" required autofocus>
                </label>
                <label class="grid gap-2 text-sm font-bold">
                    Password
                    <input type="password" name="password" class="rounded-full border border-zinc-300 px-4 py-3 font-medium" required>
                </label>
                <label class="flex items-center gap-3 text-sm font-bold text-zinc-700">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-zinc-300">
                    Remember me
                </label>

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-3 text-sm font-medium text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button class="motion-press rounded-full bg-zinc-950 px-5 py-3 font-black text-white hover:bg-[#092b83]">Login</button>
            </form>

            <p class="mt-6 text-sm text-zinc-600">
                New here?
                <a href="{{ route('register') }}" class="font-black text-[#092b83] hover:text-zinc-950">Create an account</a>
            </p>
        </div>
    </section>
</x-layouts.storefront>
