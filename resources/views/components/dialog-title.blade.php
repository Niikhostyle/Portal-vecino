@props(['class' => ''])

@php
    $baseClasses = 'text-lg font-semibold leading-none tracking-tight text-slate-900 dark:text-slate-50';
@endphp

<h2 {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
    {{ $slot }}
</h2>
