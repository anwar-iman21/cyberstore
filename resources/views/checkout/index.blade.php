@extends('layouts.shop')
@section('title', 'Checkout')

@section('content')
<h1 class="text-2xl font-bold text-slate-100 mb-6">Checkout</h1>

<div class="bg-slate-900 border border-slate-800 rounded-lg divide-y divide-slate-800">
    @foreach($cart->items as $item)
        <div class="flex justify-between items-center p-4 text-sm">
            <div>
                <div class="text-slate-100 font-medium">{{ $item->product->name }}</div>
                <div class="text-slate-500">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
            </div>
            <div class="text-slate-100 font-semibold">
                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
            </div>
        </div>
    @endforeach
    <div class="flex justify-between items-center p-4">
        <div class="text-slate-300 font-medium">Total</div>
        <div class="text-xl font-bold text-sky-400">
            Rp {{ number_format($cart->items->sum(fn($i) => $i->product->price * $i->quantity), 0, ',', '.') }}
        </div>
    </div>
</div>

<div class="mt-4 text-sm text-slate-400 bg-slate-900/60 border border-slate-800 rounded-lg p-4">
    Harga dan total di atas dihitung dari data terkini di server. Setelah order dibuat, admin akan meninjau
    dan menyetujui order kamu, lalu kamu akan diminta melakukan pembayaran manual dan konfirmasi via WhatsApp.
</div>

<form method="POST" action="{{ route('checkout.store') }}" class="mt-6">
    @csrf
    <button class="rounded-md bg-sky-500 text-slate-950 font-semibold px-6 py-2.5 hover:bg-sky-400 transition">
        Buat Order
    </button>
</form>
@endsection
