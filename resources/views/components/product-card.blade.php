@props(['product'])

<article {{ $attributes->merge(['class' => 'group motion-lift overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm hover:shadow-xl']) }}>
    <a href="{{ route('products.show', $product) }}" class="block">
        <div class="relative aspect-[4/3] overflow-hidden bg-[#f3f3ef]">
            <img
                src="{{ $product->image_url }}"
                alt="{{ $product->name }}"
                class="motion-image h-full w-full object-cover mix-blend-multiply {{ $product->stock < 1 ? 'grayscale' : '' }}"
            >
            <span class="absolute left-3 top-3 rounded-full bg-white px-3 py-1 text-xs font-black text-[#092b83] shadow-sm">Try on</span>
            @if ($product->stock < 1)
                <span class="absolute bottom-3 left-3 rounded-full bg-zinc-950 px-3 py-1 text-xs font-black uppercase text-white">Sold out</span>
            @elseif ($product->compare_at_price)
                <span class="absolute bottom-3 left-3 rounded-full bg-[#e25822] px-3 py-1 text-xs font-black uppercase text-white">Sale</span>
            @elseif ($product->stock <= 5)
                <span class="absolute bottom-3 left-3 rounded-full bg-[#092b83] px-3 py-1 text-xs font-black uppercase text-white">Only {{ $product->stock }} left</span>
            @endif
            <span class="absolute right-3 top-3 grid h-9 w-9 place-items-center rounded-full bg-white text-lg shadow-sm">♡</span>
        </div>
        <div class="flex min-h-48 flex-col gap-4 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-black uppercase text-[#092b83]">{{ $product->category->name }}</p>
                    <h3 class="mt-1 text-xl font-black">{{ $product->name }}</h3>
                </div>
                @if ($product->is_featured)
                    <span class="rounded-full bg-[#d9f4fb] px-2 py-1 text-xs font-black text-[#092b83]">New</span>
                @endif
            </div>

            <div class="flex gap-2">
                @foreach (array_slice($product->colors ?? [], 0, 4) as $color)
                    <span title="{{ $color }}" class="h-4 w-4 rounded-full border border-zinc-300 {{ str_contains(strtolower($color), 'black') ? 'bg-zinc-950' : (str_contains(strtolower($color), 'gold') || str_contains(strtolower($color), 'honey') ? 'bg-amber-300' : (str_contains(strtolower($color), 'crystal') || str_contains(strtolower($color), 'clear') ? 'bg-white' : 'bg-zinc-400')) }}"></span>
                @endforeach
            </div>

            <div class="mt-auto flex items-end justify-between gap-3">
                <div>
                    @if ($product->compare_at_price)
                        <p class="text-xs font-medium text-zinc-400 line-through">Rs. {{ number_format((float) $product->compare_at_price) }}</p>
                    @endif
                    <p class="text-xl font-black">Rs. {{ number_format((float) $product->price) }}</p>
                    <p class="mt-1 text-xs font-bold uppercase text-zinc-400">Frame + lenses ready</p>
                </div>
                <span class="motion-press rounded-full border border-zinc-300 px-3 py-2 text-sm font-bold group-hover:border-[#092b83] group-hover:bg-[#092b83] group-hover:text-white">{{ $product->stock < 1 ? 'View' : 'Select' }}</span>
            </div>
        </div>
    </a>
</article>
