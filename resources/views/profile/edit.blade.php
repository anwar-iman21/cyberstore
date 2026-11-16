@extends('layouts.shop')
@section('title', 'Profil Saya')

@section('content')
<h1 class="text-2xl font-bold text-slate-100 mb-6">Profil Saya</h1>

<div class="max-w-lg bg-slate-900 border border-slate-800 rounded-lg p-6 mb-6">
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf @method('PATCH')

        <div>
            <label class="block text-sm text-slate-300 mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 text-sm text-slate-100">
            @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 text-sm text-slate-100">
            @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1">Nomor WhatsApp</label>
            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                   placeholder="62xxxxxxxxxx"
                   class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 text-sm text-slate-100">
            @error('whatsapp_number') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <button class="rounded-md bg-sky-500 text-slate-950 font-semibold px-5 py-2 text-sm hover:bg-sky-400">
            Simpan
        </button>
    </form>
</div>

<div class="max-w-lg bg-slate-900 border border-slate-800 rounded-lg p-6 mb-6">
    <h2 class="text-sm font-semibold text-slate-100 mb-3">Ubah Password</h2>
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm text-slate-300 mb-1">Password Saat Ini</label>
            <input type="password" name="current_password" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 text-sm text-slate-100">
            @error('current_password', 'updatePassword') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm text-slate-300 mb-1">Password Baru</label>
            <input type="password" name="password" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 text-sm text-slate-100">
            @error('password', 'updatePassword') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm text-slate-300 mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 text-sm text-slate-100">
        </div>
        <button class="rounded-md bg-slate-700 text-slate-100 font-semibold px-5 py-2 text-sm hover:bg-slate-600">
            Ubah Password
        </button>
    </form>
</div>
@endsection
