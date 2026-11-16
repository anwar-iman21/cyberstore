<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CyberStore')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased">
    <header class="bg-slate-900/80 backdrop-blur border-b border-slate-800 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between" x-data="{ open: false }">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold tracking-tight text-sky-400">
                @if($logo = \App\Models\Setting::get('company_logo'))
                    <img src="{{ asset('storage/'.$logo) }}" class="w-7 h-7 object-contain">
                @endif
                {{ \App\Models\Setting::get('company_name', 'CyberStore') }}
            </a>

            <button class="md:hidden text-slate-300" @click="open = !open">☰</button>

            <nav class="hidden md:flex items-center gap-6 text-sm text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Produk</a>
                @auth
                    <a href="{{ route('cart.index') }}" class="hover:text-sky-400 transition">Keranjang</a>
                    <a href="{{ route('orders.index') }}" class="hover:text-sky-400 transition">Pesanan Saya</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sky-300 hover:text-sky-200 transition">Admin Panel</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="hover:text-sky-400 transition">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="hover:text-sky-400 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-sky-400 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-sky-500 text-slate-950 px-3 py-1.5 rounded-md font-semibold hover:bg-sky-400 transition">Daftar</a>
                @endauth
            </nav>

            <div x-show="open" x-cloak class="md:hidden absolute top-full left-0 right-0 bg-slate-900 border-b border-slate-800 flex flex-col gap-3 px-4 py-4 text-sm text-slate-300">
                <a href="{{ route('home') }}">Produk</a>
                @auth
                    <a href="{{ route('cart.index') }}">Keranjang</a>
                    <a href="{{ route('orders.index') }}">Pesanan Saya</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sky-300">Admin Panel</a>
                    @endif
                    <a href="{{ route('profile.edit') }}">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button>Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
        @if(session('success'))
            <div class="mb-6 rounded-lg border border-emerald-700 bg-emerald-900/40 text-emerald-200 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-lg border border-red-700 bg-red-900/40 text-red-200 px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-slate-800 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} {{ \App\Models\Setting::get('company_name', 'CyberStore') }}. All rights reserved.
    </footer>
</body>
</html>
