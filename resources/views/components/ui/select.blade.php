@props(['label', 'name' => null, 'required' => false])

<label class="grid gap-2 text-sm font-medium text-bone">
    <span>{{ $label }}@if ($required) <span class="text-signal-bad" aria-hidden="true">*</span>@endif</span>
    <select
        @if ($name) name="{{ $name }}" id="{{ $attributes->get('id', $name) }}" @endif
        @if ($required) required aria-required="true" @endif
        {{ $attributes->except(['class', 'id'])->merge(['class' => 'w-full rounded-none border-0 border-b border-hairline bg-transparent bg-[right_center] bg-no-repeat px-0 py-2.5 text-base text-bone outline-none transition-colors duration-200 ease-out focus:border-volt']) }}
    >{{ $slot }}</select>
    @if ($name)
        @error($name)
            <span class="text-sm text-signal-bad">{{ $message }}</span>
        @enderror
    @endif
</label>
