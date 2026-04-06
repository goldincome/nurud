@props(['active', 'href', 'icon'])

@php
    $classes = ($active ?? false)
        ? 'flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold bg-brand-blue text-white shadow-md shadow-brand-blue/20 transition-all duration-200'
        : 'flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition-all duration-200';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    <i class="{{ $icon }} w-5 text-center"></i>
    <span>{{ $slot }}</span>
</a>