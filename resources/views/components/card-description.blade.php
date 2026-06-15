@props(['class' => ''])

<p {{ $attributes->merge(['class' => "text-sm text-slate-600 dark:text-slate-400 $class"]) }}>
    {{ $slot }}
</p>
