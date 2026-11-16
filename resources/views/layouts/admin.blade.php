<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — CyberStore</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar desktop -->
        <aside class="hidden md:flex md:flex-col w-64 bg-slate-900 border-r border-slate-800 shrink-0">
            @include('admin.partials.sidebar-content')
        </aside>

        <!-- Sidebar mobile drawer -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 md:hidden">
            <div class="absolute inset-0 bg-black/60" @click="sidebarOpen = false"></div>
            <aside class="relative w-64 h-full bg-slate-900 border-r border-slate-800 flex flex-col">
                @include('admin.partials.sidebar-content')
            </aside>
        </div>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-slate-900 border-b border-slate-800 px-4 py-3 flex items-center justify-between md:justify-end">
                <button class="md:hidden text-slate-300" @click="sidebarOpen = true">☰</button>
                <div class="flex items-center gap-4 text-sm text-slate-300">
                    <span>{{ auth()->user()->name }}</span>
                    <a href="{{ route('home') }}" class="hover:text-sky-400">Lihat Toko</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="hover:text-sky-400">Logout</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-4 md:p-8 overflow-x-auto">
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

                <h1 class="text-2xl font-bold text-slate-100 mb-6">@yield('title')</h1>

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
