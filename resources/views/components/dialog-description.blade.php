@props(['class' => ''])

@php
    $baseClasses = 'text-sm text-slate-600 dark:text-slate-400';
@endphp

<p {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
    {{ $slot }}
</p>
