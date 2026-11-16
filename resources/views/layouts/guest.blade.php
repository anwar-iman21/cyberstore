<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::get('company_name', 'CyberStore') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 antialiased min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="flex justify-center mb-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold text-sky-400">
                @if($logo = \App\Models\Setting::get('company_logo'))
                    <img src="{{ asset('storage/'.$logo) }}" class="w-8 h-8 object-contain">
                @endif
                {{ \App\Models\Setting::get('company_name', 'CyberStore') }}
            </a>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-xl p-6">
            {{ $slot }}
        </div>
    </div>
</body>
</html>