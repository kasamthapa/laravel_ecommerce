@props(['label', 'name' => null, 'value' => null, 'required' => false, 'rows' => 4])

<label class="grid gap-2 text-sm font-medium text-bone">
    <span>{{ $label }}@if ($required) <span class="text-signal-bad" aria-hidden="true">*</span>@endif</span>
    <textarea
        @if ($name) name="{{ $name }}" id="{{ $attributes->get('id', $name) }}" @endif
        rows="{{ $rows }}"
        @if ($required) required aria-required="true" @endif
        {{ $attributes->except(['class', 'id'])->merge(['class' => 'w-full resize-y rounded-none border border-hairline bg-transparent px-3 py-2.5 text-base text-bone outline-none transition-colors duration-200 ease-out placeholder:text-smoke-dim focus:border-volt']) }}
    >{{ $value ?? ($name ? old($name) : '') }}</textarea>
    @if ($name)
        @error($name)
            <span class="text-sm text-signal-bad">{{ $message }}</span>
        @enderror
    @endif
</label>
