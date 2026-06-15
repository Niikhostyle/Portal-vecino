@props(['class' => '', 'href' => null])

@php
    $baseClasses = 'relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors focus:bg-slate-100 focus:text-slate-900 data-[disabled]:pointer-events-none data-[disabled]:opacity-50 dark:focus:bg-slate-800 dark:focus:text-slate-50';
    $isLink = $href !== null;
@endphp

@if($isLink)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
        {{ $slot }}
    </a>
@else
    <div {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
        {{ $slot }}
    </div>
@endif
