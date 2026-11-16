@extends('layouts.shop')
@section('title', 'Keranjang')

@section('content')
<h1 class="text-2xl font-bold text-slate-100 mb-6">Keranjang Belanja</h1>

@if($cart->items->isEmpty())
    <p class="text-slate-400 text-sm">Keranjang kamu masih kosong. <a href="{{ route('home') }}" class="text-sky-400 hover:underline">Belanja sekarang</a>.</p>
@else
    <div class="space-y-3">
        @foreach($cart->items as $item)
            <div class="flex items-center gap-4 bg-slate-900 border border-slate-800 rounded-lg p-4">
                <div class="w-16 h-16 bg-slate-800 rounded-md flex items-center justify-center overflow-hidden shrink-0">
                    @if($item->product?->main_image_url)
                        <img src="{{ $item->product->main_image_url }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-slate-100 truncate">{{ $item->product?->name ?? 'Produk tidak tersedia' }}</div>
                    <div class="text-sky-400 text-sm">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                </div>
                <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                    @csrf @method('PATCH')
                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99"
                           class="w-16 rounded-md bg-slate-800 border border-slate-700 px-2 py-1 text-sm text-slate-100">
                    <button class="text-xs text-sky-400 hover:underline">Update</button>
                </form>
                <div class="text-sm font-semibold text-slate-100 w-28 text-right">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </div>
                <form method="POST" action="{{ route('cart.destroy', $item) }}">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-400 hover:underline">Hapus</button>
                </form>
            </div>
        @endforeach
    </div>

    <div class="mt-6 flex justify-between items-center bg-slate-900 border border-slate-800 rounded-lg p-4">
        <div class="text-slate-300">Total</div>
        <div class="text-xl font-bold text-sky-400">Rp {{ number_format($cart->total, 0, ',', '.') }}</div>
    </div>

    <a href="{{ route('checkout.index') }}"
       class="mt-6 inline-block rounded-md bg-sky-500 text-slate-950 font-semibold px-6 py-2.5 hover:bg-sky-400 transition">
        Lanjut ke Checkout
    </a>
@endif
@endsection
