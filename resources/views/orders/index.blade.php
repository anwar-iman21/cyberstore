@extends('layouts.shop')
@section('title', 'Pesanan Saya')

@section('content')
<h1 class="text-2xl font-bold text-slate-100 mb-6">Pesanan Saya</h1>

@if($orders->isEmpty())
    <p class="text-slate-400 text-sm">Belum ada pesanan.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 border-b border-slate-800">
                    <th class="py-2 pr-4">No. Order</th>
                    <th class="py-2 pr-4">Tanggal</th>
                    <th class="py-2 pr-4">Total</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @foreach($orders as $order)
                    <tr>
                        <td class="py-3 pr-4 text-slate-100 font-medium">{{ $order->order_number }}</td>
                        <td class="py-3 pr-4 text-slate-400">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="py-3 pr-4 text-sky-400 font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="py-3 pr-4">
                            <span class="text-xs px-2 py-1 rounded bg-slate-800 text-slate-300">{{ str_replace('_', ' ', $order->status) }}</span>
                        </td>
                        <td class="py-3">
                            <a href="{{ route('orders.show', $order->order_number) }}" class="text-sky-400 hover:underline">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $orders->links() }}</div>
@endif
@endsection
