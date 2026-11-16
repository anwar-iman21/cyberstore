@extends('layouts.shop')
@section('title', 'Invoice '.$order->order_number)

@section('content')
<div class="bg-white text-slate-900 rounded-lg p-8 max-w-2xl mx-auto print:shadow-none">
    <div class="flex justify-between items-start border-b pb-4 mb-4">
        <div class="flex items-center gap-3">
            @if($logo = \App\Models\Setting::get('company_logo'))
                <img src="{{ asset('storage/'.$logo) }}" class="w-10 h-10 object-contain">
            @endif
            <div>
                <div class="text-xl font-bold">{{ \App\Models\Setting::get('company_name', 'CyberStore') }}</div>
                <div class="text-sm text-slate-500">{{ \App\Models\Setting::get('company_description') }}</div>
            </div>
        </div>
        <div class="text-right">
            <div class="font-semibold">INVOICE</div>
            <div class="text-sm text-slate-500">{{ $order->order_number }}</div>
            <div class="text-sm text-slate-500">{{ $order->created_at->format('d M Y') }}</div>
        </div>
    </div>

    <div class="mb-4 text-sm">
        <div class="font-medium">Ditagihkan kepada:</div>
        <div>{{ $order->user->name }}</div>
        <div>{{ $order->user->email }}</div>
    </div>

    <table class="w-full text-sm mb-4">
        <thead>
            <tr class="border-b text-left">
                <th class="py-2">Produk</th>
                <th class="py-2 text-right">Qty</th>
                <th class="py-2 text-right">Harga</th>
                <th class="py-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr class="border-b">
                    <td class="py-2">{{ $item->product_name }}</td>
                    <td class="py-2 text-right">{{ $item->quantity }}</td>
                    <td class="py-2 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="py-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex justify-end">
        <div class="w-56 flex justify-between font-bold text-lg">
            <span>Total</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="mt-4 flex justify-end gap-4 text-xs text-slate-600">
        <span>Status Order: <strong>{{ str_replace('_', ' ', $order->status) }}</strong></span>
        <span>Status Pembayaran: <strong>{{ str_replace('_', ' ', $order->paymentConfirmation->payment_status ?? 'unpaid') }}</strong></span>
    </div>

    <div class="mt-6 text-xs text-slate-500 border-t pt-4">
        {{ \App\Models\Setting::get('invoice_information', 'Pembayaran dilakukan secara manual, konfirmasi via WhatsApp.') }}
    </div>
</div>

<div class="max-w-2xl mx-auto mt-4 text-center print:hidden">
    <button onclick="window.print()" class="rounded-md bg-slate-800 text-slate-100 px-5 py-2 text-sm hover:bg-slate-700">
        Cetak / Simpan PDF
    </button>
</div>
@endsection
