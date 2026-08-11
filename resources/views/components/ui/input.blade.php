@props(['label', 'name' => null, 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null])

<label class="grid gap-2 text-sm font-medium text-ink">
    <span>{{ $label }}@if ($required) <span class="text-error" aria-hidden="true">*</span>@endif</span>
    <input
        type="{{ $type }}"
        @if ($name) name="{{ $name }}" id="{{ $attributes->get('id', $name) }}" @endif
        @if ($name && $value === null) value="{{ old($name) }}" @elseif ($value !== null) value="{{ $value }}" @endif
        @if ($required) required aria-required="true" @endif
        @if ($name) @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror @endif
        {{ $attributes->except(['class', 'id'])->merge(['class' => 'w-full rounded-none border-0 border-b border-line bg-transparent px-0 py-2.5 text-base text-ink outline-none transition-colors duration-200 ease-out placeholder:text-stone-light focus:border-accent']) }}
    >
    @if ($hint)
        <span class="text-xs text-stone">{{ $hint }}</span>
    @endif
    @if ($name)
        @error($name)
            <span id="{{ $name }}-error" class="text-sm text-error">{{ $message }}</span>
        @enderror
    @endif
</label>
