@props(['variant' => 'default', 'class' => ''])

@php
    $baseClasses = 'relative w-full rounded-lg border p-4';
    $variantClasses = match($variant) {
        'default' => 'bg-white text-slate-950 border-slate-200 dark:bg-slate-950 dark:text-slate-50 dark:border-slate-800',
        'destructive' => 'border-red-500/50 text-red-900 dark:border-red-500 dark:text-red-50 [&>svg]:text-red-600 dark:[&>svg]:text-red-400',
        'success' => 'border-emerald-500/50 text-emerald-900 dark:border-emerald-500 dark:text-emerald-50 [&>svg]:text-emerald-600 dark:[&>svg]:text-emerald-400',
        'warning' => 'border-amber-500/50 text-amber-900 dark:border-amber-500 dark:text-amber-50 [&>svg]:text-amber-600 dark:[&>svg]:text-amber-400',
        'info' => 'border-blue-500/50 text-blue-900 dark:border-blue-500 dark:text-blue-50 [&>svg]:text-blue-600 dark:[&>svg]:text-blue-400',
        default => 'bg-white text-slate-950 border-slate-200 dark:bg-slate-950 dark:text-slate-50 dark:border-slate-800',
    };
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $variantClasses $class"]) }} role="alert">
    {{ $slot }}
</div>
