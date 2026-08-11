@props(['src', 'alt'])

<img
    src="{{ $src ?: asset('images/placeholder-frame.svg') }}"
    alt="{{ $alt }}"
    loading="lazy"
    onerror="this.onerror=null;this.src='{{ asset('images/placeholder-frame.svg') }}';this.classList.add('object-contain','p-8')"
    {{ $attributes }}
>
