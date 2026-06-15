@props(['class' => '', 'for' => null])

@php
    $baseClasses = 'text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-slate-700 dark:text-slate-300';
@endphp

<label
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => "$baseClasses $class"]) }}
>
    {{ $slot }}
</label>
