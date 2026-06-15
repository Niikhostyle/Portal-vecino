@props(['class' => ''])

@php
    $baseClasses = 'flex flex-col gap-4';
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
    {{ $slot }}
</div>
