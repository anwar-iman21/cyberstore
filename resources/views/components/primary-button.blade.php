<button {{ $attributes->merge([
    'class' => 'w-full inline-flex justify-center items-center rounded-md bg-sky-500 px-4 py-2
                text-sm font-semibold text-slate-950 hover:bg-sky-400 transition disabled:opacity-50'
]) }}>
    {{ $slot }}
</button>