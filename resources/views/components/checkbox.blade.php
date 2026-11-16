@props(['disabled' => false])

<input type="checkbox" @disabled($disabled) {!! $attributes->merge([
    'class' => 'rounded bg-slate-800 border-slate-700 text-sky-500 focus:ring-sky-500'
]) !!}>