@props(['name' => '', 'value' => '1', 'checked' => false, 'disabled' => false, 'class' => ''])

@php
    $baseClasses = 'peer inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 focus-visible:ring-offset-white disabled:cursor-not-allowed disabled:opacity-50 dark:focus-visible:ring-slate-300 dark:focus-visible:ring-offset-slate-950';
    $checkedClasses = $checked ? 'bg-slate-900 dark:bg-slate-50' : 'bg-slate-200 dark:bg-slate-800';
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
@endphp

<button
    type="button"
    role="switch"
    aria-checked="{{ $checked ? 'true' : 'false' }}"
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => "$baseClasses $checkedClasses $disabledClasses $class"]) }}
    @if($name)
        name="{{ $name }}"
        value="{{ $value }}"
    @endif
    onclick="this.setAttribute('aria-checked', this.getAttribute('aria-checked') === 'true' ? 'false' : 'true'); this.classList.toggle('bg-slate-900'); this.classList.toggle('bg-slate-200'); this.querySelector('span').classList.toggle('translate-x-5');"
>
    <span class="pointer-events-none block h-5 w-5 rounded-full bg-white shadow-lg ring-0 transition-transform {{ $checked ? 'translate-x-5' : 'translate-x-0' }} dark:bg-slate-950"></span>
</button>
