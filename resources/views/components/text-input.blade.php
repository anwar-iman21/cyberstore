@props(['disabled' => false])

<input @disabled($disabled) {!! $attributes->merge([
    'class' => 'w-full rounded-md bg-slate-800 border border-slate-700 text-slate-100 placeholder-slate-500
                focus:border-sky-500 focus:ring-sky-500 text-sm px-3 py-2'
]) !!}>