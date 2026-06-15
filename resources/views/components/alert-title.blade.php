@props(['class' => ''])

@php
    $baseClasses = 'mb-1 font-medium leading-none tracking-tight';
@endphp

<h5 {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
    {{ $slot }}
</h5>
