@props(['product' => null, 'categories'])

@php
    $sizesValue = $product ? implode(', ', $product->sizes ?? []) : '';
    $colorsValue = $product ? implode(', ', $product->colors ?? []) : '';
    $imagesValue = $product ? implode(', ', $product->images ?? []) : '';
    $inputClass = 'rounded-xl border border-zinc-300 px-4 py-3 font-medium outline-none transition focus:border-[#092b83] focus:ring-2 focus:ring-[#092b83]/20';
@endphp

<div class="grid gap-6">
    <div class="grid gap-5 sm:grid-cols-2">
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Name
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="{{ $inputClass }}">
        </label>
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Category
            <select name="category_id" required class="{{ $inputClass }}">
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <label class="grid gap-2 text-sm font-bold text-zinc-700">
        Description
        <textarea name="description" rows="3" required class="{{ $inputClass }}">{{ old('description', $product->description ?? '') }}</textarea>
    </label>

    <label class="grid gap-2 text-sm font-bold text-zinc-700">
        Primary image URL
        <input type="url" name="image_url" value="{{ old('image_url', $product->image_url ?? '') }}" required placeholder="https://images.unsplash.com/..." class="{{ $inputClass }}">
    </label>

    <label class="grid gap-2 text-sm font-bold text-zinc-700">
        Gallery images (comma separated URLs, optional)
        <textarea name="images" rows="2" placeholder="https://.../angle-1.jpg, https://.../angle-2.jpg" class="{{ $inputClass }}">{{ old('images', $imagesValue) }}</textarea>
        <span class="text-xs font-medium text-zinc-500">Falls back to the primary image when left empty.</span>
    </label>

    <div class="grid gap-5 sm:grid-cols-3">
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Price (Rs.)
            <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}" required class="{{ $inputClass }}">
        </label>
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Compare-at price
            <input type="number" name="compare_at_price" step="0.01" min="0" value="{{ old('compare_at_price', $product->compare_at_price ?? '') }}" class="{{ $inputClass }}">
        </label>
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Stock
            <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required class="{{ $inputClass }}">
        </label>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Sizes (comma separated)
            <input type="text" name="sizes" value="{{ old('sizes', $sizesValue) }}" required placeholder="Narrow, Medium, Wide" class="{{ $inputClass }}">
        </label>
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Colors (comma separated)
            <input type="text" name="colors" value="{{ old('colors', $colorsValue) }}" required placeholder="Ink Black, Smoke Grey" class="{{ $inputClass }}">
        </label>
    </div>

    <div class="flex flex-wrap gap-6 rounded-xl bg-zinc-50 px-4 py-3.5">
        <label class="flex items-center gap-2 text-sm font-bold text-zinc-700">
            <input type="checkbox" name="is_featured" value="1" class="h-4 w-4 rounded accent-[#092b83]" @checked(old('is_featured', $product->is_featured ?? false))>
            Featured
        </label>
        <label class="flex items-center gap-2 text-sm font-bold text-zinc-700">
            <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded accent-[#092b83]" @checked(old('is_active', $product->is_active ?? true))>
            Active (visible on storefront)
        </label>
    </div>
</div>

@if ($errors->any())
    <div class="mt-5 rounded-xl bg-red-50 p-4 text-sm font-medium text-red-700">
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
