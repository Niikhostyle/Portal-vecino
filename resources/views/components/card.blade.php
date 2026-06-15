@props(['class' => '', 'variant' => 'default'])

@php
    $baseClasses = 'rounded-lg border bg-white dark:bg-slate-900 text-slate-950 dark:text-slate-50 shadow-sm';
    $variantClasses = match($variant) {
        'outlined' => 'border-slate-200 dark:border-slate-800',
        'elevated' => 'shadow-md border-slate-200 dark:border-slate-800',
        default => 'border-slate-200 dark:border-slate-800',
    };
    $isLink = isset($attributes['href']);
@endphp

@if($isLink)
    <a {{ $attributes->merge(['class' => "$baseClasses $variantClasses $class block"]) }}>
        {{ $slot }}
    </a>
@else
    <div {{ $attributes->merge(['class' => "$baseClasses $variantClasses $class"]) }}>
        {{ $slot }}
    </div>
@endif
