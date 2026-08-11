@props(['label', 'name' => null, 'value' => null, 'required' => false, 'rows' => 4])

<label class="grid gap-2 text-sm font-medium text-ink">
    <span>{{ $label }}@if ($required) <span class="text-error" aria-hidden="true">*</span>@endif</span>
    <textarea
        @if ($name) name="{{ $name }}" id="{{ $attributes->get('id', $name) }}" @endif
        rows="{{ $rows }}"
        @if ($required) required aria-required="true" @endif
        {{ $attributes->except(['class', 'id'])->merge(['class' => 'w-full resize-y rounded-none border border-line bg-transparent px-3 py-2.5 text-base text-ink outline-none transition-colors duration-200 ease-out placeholder:text-stone-light focus:border-accent']) }}
    >{{ $value ?? ($name ? old($name) : '') }}</textarea>
    @if ($name)
        @error($name)
            <span class="text-sm text-error">{{ $message }}</span>
        @enderror
    @endif
</label>
