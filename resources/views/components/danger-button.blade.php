<button {{ $attributes->merge([
    'class' => 'inline-flex justify-center items-center rounded-md bg-red-600 px-4 py-2
                text-sm font-semibold text-white hover:bg-red-500 transition disabled:opacity-50'
]) }}>
    {{ $slot }}
</button>