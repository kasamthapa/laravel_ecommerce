@props(['coupon' => null])

@php
    $inputClass = 'rounded-xl border border-zinc-300 px-4 py-3 font-medium outline-none transition focus:border-[#092b83] focus:ring-2 focus:ring-[#092b83]/20';
@endphp

<div class="grid gap-6">
    <div class="grid gap-5 sm:grid-cols-2">
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Code
            <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" required maxlength="40" class="{{ $inputClass }} uppercase" placeholder="LUMA20">
        </label>
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Type
            <select name="type" class="{{ $inputClass }}">
                <option value="percent" @selected(old('type', $coupon->type ?? 'percent') === 'percent')>Percentage off</option>
                <option value="fixed" @selected(old('type', $coupon->type ?? '') === 'fixed')>Flat amount off (Rs.)</option>
            </select>
        </label>
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Value
            <input type="number" name="value" step="0.01" min="0" value="{{ old('value', $coupon->value ?? '') }}" required class="{{ $inputClass }}">
        </label>
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Max uses
            <input type="number" name="max_uses" min="1" value="{{ old('max_uses', $coupon->max_uses ?? '') }}" placeholder="Unlimited" class="{{ $inputClass }}">
        </label>
        <label class="grid gap-2 text-sm font-bold text-zinc-700">
            Expires
            <input type="date" name="expires_at" value="{{ old('expires_at', optional($coupon?->expires_at)->format('Y-m-d')) }}" class="{{ $inputClass }}">
        </label>
    </div>

    <label class="flex w-fit items-center gap-2 rounded-xl bg-zinc-50 px-4 py-3.5 text-sm font-bold text-zinc-700">
        <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded accent-[#092b83]" @checked(old('is_active', $coupon->is_active ?? true))>
        Active
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
