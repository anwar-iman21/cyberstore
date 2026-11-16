@props(['messages'])

@if($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-400 mt-1 space-y-0.5']) }}>
        @foreach((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif