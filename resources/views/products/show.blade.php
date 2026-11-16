@extends('layouts.shop')
@section('title', $product->name)

@section('content')
<a href="{{ route('home') }}" class="text-sm text-slate-400 hover:text-sky-400">&larr; Kembali ke katalog</a>

<div class="mt-4 grid md:grid-cols-2 gap-8">
    <div class="rounded-lg overflow-hidden bg-slate-900 border border-slate-800 aspect-square flex items-center justify-center">
        @if($product->main_image_url)
            <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            <span class="text-slate-600 text-sm">Tidak ada gambar</span>
        @endif
    </div>

    <div>
        <div class="text-xs text-sky-400 uppercase tracking-wide mb-2">{{ $product->category->name }}</div>
        <h1 class="text-2xl font-bold text-slate-100">{{ $product->name }}</h1>
        <div class="text-2xl font-bold text-sky-400 mt-3">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

        <div class="prose prose-invert prose-sm max-w-none mt-5 text-slate-300">
            {!! nl2br(e($product->description)) !!}
        </div>

        @auth
            <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-6 flex items-center gap-3">
                @csrf
                <input type="number" name="quantity" value="1" min="1" max="99"
                       class="w-20 rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">
                <button class="rounded-md bg-sky-500 text-slate-950 font-semibold px-6 py-2 hover:bg-sky-400 transition">
                    Tambah ke Keranjang
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="inline-block mt-6 rounded-md bg-sky-500 text-slate-950 font-semibold px-6 py-2 hover:bg-sky-400 transition">
                Login untuk membeli
            </a>
        @endauth
    </div>
</div>
@endsection
