@props(['class' => '', 'error' => false, 'rows' => 3])

@php
    $baseClasses = 'flex min-h-[80px] w-full rounded-md border bg-white px-3 py-2 text-sm ring-offset-background placeholder:text-slate-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:ring-offset-slate-950 dark:placeholder:text-slate-400 dark:focus-visible:ring-slate-300';
    $errorClasses = $error ? 'border-red-500 focus-visible:ring-red-500' : 'border-slate-200';
@endphp

<textarea
    rows="{{ $rows }}"
    {{ $attributes->merge(['class' => "$baseClasses $errorClasses $class"]) }}
>{{ $slot }}</textarea>
