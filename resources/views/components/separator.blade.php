@props(['orientation' => 'horizontal', 'class' => ''])

@php
    $baseClasses = 'shrink-0 bg-slate-200 dark:bg-slate-800';
    $orientationClasses = match($orientation) {
        'horizontal' => 'h-[1px] w-full',
        'vertical' => 'h-full w-[1px]',
        default => 'h-[1px] w-full',
    };
@endphp

<div
    role="separator"
    aria-orientation="{{ $orientation }}"
    {{ $attributes->merge(['class' => "$baseClasses $orientationClasses $class"]) }}
></div>
