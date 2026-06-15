@props(['class' => ''])

@php
    $baseClasses = 'text-sm [&_p]:leading-relaxed';
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
    {{ $slot }}
</div>
