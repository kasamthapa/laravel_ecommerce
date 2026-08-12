<x-layouts.admin title="Add coupon - Luma Lens Admin">
    <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-accent hover:underline">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        Back to coupons
    </a>
    <h1 class="mt-3 text-2xl font-black tracking-tight text-ink">Add a coupon</h1>

    <form method="POST" action="{{ route('admin.coupons.store') }}" class="mt-6 max-w-2xl rounded-2xl border border-line bg-cream p-6 shadow-sm sm:p-8">
        @csrf
        <x-admin.coupons.form />
        <button class="motion-press mt-6 rounded-full bg-accent px-6 py-3 font-black text-white hover:bg-ink">Create coupon</button>
    </form>
</x-layouts.admin>
