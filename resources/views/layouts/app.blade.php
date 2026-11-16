<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::get('company_name', 'CyberStore') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        <div class="min-h-screen">
            <nav class="bg-slate-900 border-b border-slate-800">
                <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold text-sky-400">
                        @if($logo = \App\Models\Setting::get('company_logo'))
                            <img src="{{ asset('storage/'.$logo) }}" class="w-7 h-7 object-contain">
                        @endif
                        {{ \App\Models\Setting::get('company_name', 'CyberStore') }}
                    </a>
                    <div class="flex items-center gap-4 text-sm text-slate-300">
                        <a href="{{ route('home') }}" class="hover:text-sky-400">Beranda</a>
                        <a href="{{ route('profile.edit') }}" class="hover:text-sky-400">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="hover:text-sky-400">Logout</button>
                        </form>
                    </div>
                </div>
            </nav>

            @isset($header)
                <header class="bg-slate-900 border-b border-slate-800">
                    <div class="max-w-6xl mx-auto py-6 px-4">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="max-w-6xl mx-auto px-4 py-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>