@extends('layouts.admin')
@section('title', 'Pesanan')

@section('content')
<form method="GET" class="mb-4 flex gap-2 flex-wrap">
    <select name="status" onchange="this.form.submit()" class="rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">
        <option value="">Semua Status</option>
        @foreach(['pending','approved','waiting_payment','payment_confirmed','processing','completed','rejected','cancelled'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ str_replace('_', ' ', $status) }}</option>
        @endforeach
    </select>
</form>

<div class="overflow-x-auto bg-slate-900 border border-slate-800 rounded-lg">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-slate-500 border-b border-slate-800">
                <th class="py-3 px-4">No. Order</th>
                <th class="py-3 px-4">Customer</th>
                <th class="py-3 px-4">Total</th>
                <th class="py-3 px-4">Payment Status</th>
                <th class="py-3 px-4">Order Status</th>
                <th class="py-3 px-4">Tanggal</th>
                <th class="py-3 px-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @foreach($orders as $order)
                <tr>
                    <td class="py-3 px-4 text-slate-100 font-medium">{{ $order->order_number }}</td>
                    <td class="py-3 px-4 text-slate-300">{{ $order->user->name }}</td>
                    <td class="py-3 px-4 text-sky-400">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        <span class="text-xs px-2 py-1 rounded bg-slate-800 text-slate-400">
                            {{ str_replace('_', ' ', $order->paymentConfirmation->payment_status ?? 'unpaid') }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="text-xs px-2 py-1 rounded bg-slate-800 text-slate-300">{{ str_replace('_', ' ', $order->status) }}</span>
                    </td>
                    <td class="py-3 px-4 text-slate-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.orders.show', $order->order_number) }}" class="text-sky-400 hover:underline">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $orders->links() }}</div>
@endsection
