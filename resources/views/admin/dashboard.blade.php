@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
        $cards = [
            ['label' => 'Total Produk', 'value' => $stats['total_products']],
            ['label' => 'Total Pelanggan', 'value' => $stats['total_customers']],
            ['label' => 'Total Order', 'value' => $stats['total_orders']],
            ['label' => 'Total Revenue', 'value' => 'Rp '.number_format($stats['total_revenue'], 0, ',', '.')],
            ['label' => 'Order Pending', 'value' => $stats['pending_orders']],
            ['label' => 'Menunggu Pembayaran', 'value' => $stats['waiting_payment']],
            ['label' => 'Sedang Diproses', 'value' => $stats['processing_orders']],
            ['label' => 'Selesai', 'value' => $stats['completed_orders']],
        ];
    @endphp
    @foreach($cards as $card)
        <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
            <div class="text-xs text-slate-500">{{ $card['label'] }}</div>
            <div class="text-2xl font-bold text-sky-400 mt-1">{{ $card['value'] }}</div>
        </div>
    @endforeach
</div>

<h2 class="text-lg font-semibold text-slate-100 mb-3">Order Terbaru</h2>
<div class="overflow-x-auto bg-slate-900 border border-slate-800 rounded-lg">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-slate-500 border-b border-slate-800">
                <th class="py-3 px-4">No. Order</th>
                <th class="py-3 px-4">Customer</th>
                <th class="py-3 px-4">Total</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @foreach($recentOrders as $order)
                <tr>
                    <td class="py-3 px-4 text-slate-100 font-medium">{{ $order->order_number }}</td>
                    <td class="py-3 px-4 text-slate-300">{{ $order->user->name }}</td>
                    <td class="py-3 px-4 text-sky-400">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        <span class="text-xs px-2 py-1 rounded bg-slate-800 text-slate-300">{{ str_replace('_', ' ', $order->status) }}</span>
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.orders.show', $order->order_number) }}" class="text-sky-400 hover:underline">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
