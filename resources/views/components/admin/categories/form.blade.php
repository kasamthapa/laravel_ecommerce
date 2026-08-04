@props(['category' => null])

@php
    $inputClass = 'rounded-xl border border-zinc-300 px-4 py-3 font-medium outline-none transition focus:border-[#092b83] focus:ring-2 focus:ring-[#092b83]/20';
@endphp

<div class="grid gap-6">
    <label class="grid gap-2 text-sm font-bold text-zinc-700">
        Name
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="{{ $inputClass }}">
    </label>

    <label class="grid gap-2 text-sm font-bold text-zinc-700">
        Description
        <textarea name="description" rows="3" class="{{ $inputClass }}">{{ old('description', $category->description ?? '') }}</textarea>
    </label>
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
