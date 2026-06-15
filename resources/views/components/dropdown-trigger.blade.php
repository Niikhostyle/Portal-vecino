@props(['class' => ''])

@php
    $baseClasses = 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';
@endphp

<button
    type="button"
    @click="open = !open"
    {{ $attributes->merge(['class' => "$baseClasses $class"]) }}
>
    {{ $slot }}
</button>
