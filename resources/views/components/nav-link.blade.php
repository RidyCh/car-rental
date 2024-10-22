@props(['href', 'active' => false])

@php
$classes = $active
    ? 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-primary-500'
    : 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-slate-200 hover:text-primary-500';
@endphp

<a wire:navigate {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
    {{ $slot }}
</a>
