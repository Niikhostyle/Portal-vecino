@props(['class' => ''])

<h3 {{ $attributes->merge(['class' => "text-2xl font-semibold leading-none tracking-tight text-slate-900 dark:text-slate-50 $class"]) }}>
    {{ $slot }}
</h3>
