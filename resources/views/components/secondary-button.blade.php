<button {{ $attributes->merge([
    'class' => 'inline-flex justify-center items-center rounded-md bg-slate-800 px-4 py-2
                text-sm font-semibold text-slate-100 hover:bg-slate-700 transition disabled:opacity-50'
]) }}>
    {{ $slot }}
</button>