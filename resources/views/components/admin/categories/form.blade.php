@props(['category' => null])

@php
    $inputClass = 'rounded-xl border border-line px-4 py-3 font-medium outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20';
@endphp

<div class="grid gap-6">
    <label class="grid gap-2 text-sm font-bold text-ink-soft">
        Name
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="{{ $inputClass }}">
    </label>

    <label class="grid gap-2 text-sm font-bold text-ink-soft">
        Description
        <textarea name="description" rows="3" class="{{ $inputClass }}">{{ old('description', $category->description ?? '') }}</textarea>
    </label>
</div>

@if ($errors->any())
    <div class="mt-5 rounded-xl bg-error-tint p-4 text-sm font-medium text-error">
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
