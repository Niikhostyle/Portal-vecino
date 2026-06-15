@props(['variant' => 'default', 'size' => 'default', 'class' => ''])

@php
    $baseClasses = 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 dark:focus-visible:ring-slate-300 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';
    
    $variantClasses = match($variant) {
        'default' => 'bg-slate-900 text-white hover:bg-slate-800 dark:bg-slate-50 dark:text-slate-900 dark:hover:bg-slate-200',
        'destructive' => 'bg-red-500 text-white hover:bg-red-600 dark:bg-red-900 dark:hover:bg-red-800',
        'outline' => 'border border-slate-200 bg-white hover:bg-slate-100 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-800',
        'secondary' => 'bg-slate-100 text-slate-900 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-50 dark:hover:bg-slate-700',
        'ghost' => 'hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-slate-800 dark:hover:text-slate-50',
        'link' => 'text-slate-900 underline-offset-4 hover:underline dark:text-slate-50',
        default => 'bg-slate-900 text-white hover:bg-slate-800 dark:bg-slate-50 dark:text-slate-900',
    };
    
    $sizeClasses = match($size) {
        'default' => 'h-10 px-4 py-2',
        'sm' => 'h-9 rounded-md px-3 text-xs',
        'lg' => 'h-11 rounded-md px-8',
        'icon' => 'h-10 w-10',
        default => 'h-10 px-4 py-2',
    };
@endphp

@if(isset($attributes['href']))
    <a {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses $class"]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses $class"]) }}>
        {{ $slot }}
    </button>
@endif
