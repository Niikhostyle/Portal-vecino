@props(['open' => false, 'class' => ''])

@php
    $baseClasses = 'relative inline-block text-left';
@endphp

<div
    x-data="{ open: @js($open) }"
    class="{{ $baseClasses }} {{ $class }}"
    @click.away="open = false"
>
    {{ $slot }}
</div>
