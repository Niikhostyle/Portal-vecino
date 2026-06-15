@props(['class' => ''])

@php
    $baseClasses = 'flex flex-col space-y-1.5 text-center sm:text-left';
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
    {{ $slot }}
</div>
