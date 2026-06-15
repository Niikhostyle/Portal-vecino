@props(['variant' => 'default', 'class' => ''])

@php
    $baseClasses = 'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2';
    $variantClasses = match($variant) {
        'default' => 'border-transparent bg-primary text-primary-foreground hover:bg-primary/80',
        'secondary' => 'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'destructive' => 'border-transparent bg-destructive text-destructive-foreground hover:bg-destructive/80',
        'outline' => 'text-foreground border-border',
        'success' => 'border-transparent bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900/20 dark:text-green-400',
        'warning' => 'border-transparent bg-amber-100 text-amber-800 hover:bg-amber-200 dark:bg-amber-900/20 dark:text-amber-400',
        'info' => 'border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-900/20 dark:text-blue-400',
        'enviada' => 'border-transparent bg-amber-100 text-amber-800 hover:bg-amber-200',
        'en_revision' => 'border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200',
        'respondida' => 'border-transparent bg-emerald-100 text-emerald-800 hover:bg-emerald-200',
        'rechazada' => 'border-transparent bg-rose-100 text-rose-800 hover:bg-rose-200',
        default => 'border-transparent bg-muted text-muted-foreground',
    };
@endphp

<span {{ $attributes->merge(['class' => "$baseClasses $variantClasses $class"]) }}>
    {{ $slot }}
</span>
