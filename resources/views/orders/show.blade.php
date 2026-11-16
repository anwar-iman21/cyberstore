@extends('layouts.shop')
@section('title', 'Order '.$order->order_number)

@section('content')
<a href="{{ route('orders.index') }}" class="text-sm text-slate-400 hover:text-sky-400">&larr; Pesanan Saya</a>

<div class="mt-4 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-2xl font-bold text-slate-100">{{ $order->order_number }}</h1>
    <span class="text-xs px-3 py-1.5 rounded-full bg-sky-500/10 text-sky-400 font-medium uppercase tracking-wide">
        {{ str_replace('_', ' ', $order->status) }}
    </span>
</div>
<div class="text-slate-500 text-sm mt-1">Dibuat {{ $order->created_at->format('d M Y H:i') }}</div>

<div class="mt-6 grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-lg divide-y divide-slate-800">
            @foreach($order->items as $item)
                <div class="flex justify-between items-center p-4 text-sm">
                    <div>
                        <div class="text-slate-100 font-medium">{{ $item->product_name }}</div>
                        <div class="text-slate-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="text-slate-100 font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
            @endforeach
            <div class="flex justify-between items-center p-4">
                <div class="text-slate-300 font-medium">Total</div>
                <div class="text-xl font-bold text-sky-400">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-lg p-4 text-sm">
            <div class="text-xs text-slate-500 mb-1">Status Pembayaran</div>
            <span class="inline-block text-xs px-2 py-1 rounded bg-slate-800 text-slate-300">
                {{ str_replace('_', ' ', $order->paymentConfirmation->payment_status ?? 'unpaid') }}
            </span>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('orders.invoice', $order->order_number) }}"
               class="rounded-md bg-slate-800 text-slate-100 px-5 py-2.5 text-sm hover:bg-slate-700 transition">
                Lihat Invoice
            </a>

            @if($order->status === \App\Models\Order::STATUS_WAITING_PAYMENT)
                <form method="POST" action="{{ route('orders.whatsapp-confirmation', $order->order_number) }}">
                    @csrf
                    <button class="rounded-md bg-emerald-500 text-slate-950 font-semibold px-5 py-2.5 text-sm hover:bg-emerald-400 transition">
                        Konfirmasi Pembayaran via WhatsApp
                    </button>
                </form>
            @endif
        </div>

        @if($order->status === \App\Models\Order::STATUS_WAITING_PAYMENT)
            <div class="bg-slate-900 border border-sky-800 rounded-lg p-4 text-sm text-slate-300">
                <p class="font-medium text-slate-100 mb-2">Instruksi Pembayaran</p>
                <p class="whitespace-pre-line">{{ \App\Models\Setting::get('payment_information', 'Silakan hubungi admin untuk informasi pembayaran.') }}</p>
            </div>
        @endif
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
        <h3 class="text-sm font-semibold text-slate-100 mb-4">Status Pesanan</h3>
        @include('partials.order-timeline')
    </div>
</div>
@endsection
