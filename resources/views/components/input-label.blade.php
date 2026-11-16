@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm text-slate-300 mb-1']) }}>
    {{ $value ?? $slot }}
</label>