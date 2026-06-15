@props(['name' => '', 'value' => '1', 'checked' => false, 'disabled' => false, 'class' => ''])

@php
    $baseClasses = 'h-4 w-4 rounded border border-slate-300 text-slate-900 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-slate-900 data-[state=checked]:text-slate-50 dark:border-slate-700 dark:ring-offset-slate-950 dark:focus-visible:ring-slate-300 dark:data-[state=checked]:bg-slate-50 dark:data-[state=checked]:text-slate-900';
@endphp

<input
    type="checkbox"
    @if($name) name="{{ $name }}" @endif
    value="{{ $value }}"
    @if($checked) checked @endif
    @if($disabled) disabled @endif
    data-state="{{ $checked ? 'checked' : 'unchecked' }}"
    {{ $attributes->merge(['class' => "$baseClasses $class"]) }}
>
