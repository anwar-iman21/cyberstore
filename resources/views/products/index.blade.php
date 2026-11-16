@extends('layouts.shop')
@section('title', 'Produk')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-100">Produk Cyber & Security</h1>
    <p class="text-slate-400 text-sm mt-1">Produk digital untuk kebutuhan keamanan siber Anda.</p>
</div>

<form method="GET" class="mb-8 flex flex-col sm:flex-row gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..."
           class="flex-1 rounded-md bg-slate-900 border border-slate-700 px-4 py-2 text-sm text-slate-100 placeholder-slate-500 focus:border-sky-500 focus:ring-sky-500">
    <select name="category" class="rounded-md bg-slate-900 border border-slate-700 px-4 py-2 text-sm text-slate-100 focus:border-sky-500 focus:ring-sky-500">
        <option value="">Semua Kategori</option>
        @foreach($categories as $category)
            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
        @endforeach
    </select>
    <button class="rounded-md bg-sky-500 text-slate-950 font-semibold px-5 py-2 hover:bg-sky-400 transition">Cari</button>
</form>

@if($products->isEmpty())
    <p class="text-slate-400 text-sm">Belum ada produk yang cocok.</p>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        @foreach($products as $product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="group rounded-lg border border-slate-800 bg-slate-900 overflow-hidden hover:border-sky-600 transition">
                <div class="aspect-square bg-slate-800 flex items-center justify-center overflow-hidden">
                    @if($product->main_image_url)
                        <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                    @else
                        <span class="text-slate-600 text-xs">Tidak ada gambar</span>
                    @endif
                </div>
                <div class="p-3">
                    @if($product->featured)
                        <span class="inline-block text-[10px] uppercase tracking-wide bg-sky-500/10 text-sky-400 px-2 py-0.5 rounded mb-1">Unggulan</span>
                    @endif
                    <div class="text-xs text-slate-500 mb-0.5">{{ $product->category->name }}</div>
                    <div class="text-sm font-medium text-slate-100 line-clamp-2">{{ $product->name }}</div>
                    <div class="text-sky-400 font-semibold mt-1 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif
@endsection
