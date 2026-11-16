@extends('layouts.admin')
@section('title', 'Detail Pesanan')

@section('content')
<a href="{{ route('admin.orders.index') }}" class="text-sm text-slate-400 hover:text-sky-400">&larr; Daftar Pesanan</a>

<div class="mt-4 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold text-slate-100">{{ $order->order_number }}</h2>
        <div class="text-slate-500 text-sm">Tanggal Order: {{ $order->created_at->format('d M Y H:i') }}</div>
    </div>
    <span class="text-xs px-3 py-1.5 rounded-full bg-sky-500/10 text-sky-400 font-medium uppercase tracking-wide">
        {{ str_replace('_', ' ', $order->status) }}
    </span>
</div>

<div class="mt-6 grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">

        <!-- INFO CUSTOMER -->
        <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
            <h3 class="text-sm font-semibold text-slate-100 mb-3">Info Customer</h3>
            <dl class="text-sm grid grid-cols-3 gap-y-1">
                <dt class="text-slate-500">Nama</dt><dd class="col-span-2 text-slate-200">{{ $order->user->name }}</dd>
                <dt class="text-slate-500">Email</dt><dd class="col-span-2 text-slate-200">{{ $order->user->email }}</dd>
                <dt class="text-slate-500">WhatsApp</dt><dd class="col-span-2 text-slate-200">{{ $order->user->whatsapp_number ?? '-' }}</dd>
            </dl>
        </div>

        <!-- INFO PRODUK -->
        <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
            <h3 class="text-sm font-semibold text-slate-100 mb-3">Info Produk</h3>
            <div class="divide-y divide-slate-800">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4 py-3 text-sm">
                        <div class="w-12 h-12 bg-slate-800 rounded overflow-hidden shrink-0">
                            @if($item->product?->main_image_url)
                                <img src="{{ $item->product->main_image_url }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-100 font-medium">{{ $item->product_name }}</div>
                            <div class="text-slate-500">Qty: {{ $item->quantity }} &times; Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-slate-100 font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- PAYMENT -->
        <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
            <h3 class="text-sm font-semibold text-slate-100 mb-3">Pembayaran</h3>
            <dl class="text-sm grid grid-cols-3 gap-y-1 mb-4">
                <dt class="text-slate-500">Metode</dt>
                <dd class="col-span-2 text-slate-200">{{ $order->paymentConfirmation->payment_method ?? '-' }}</dd>
                <dt class="text-slate-500">Total Pembayaran</dt>
                <dd class="col-span-2 text-slate-200">Rp {{ number_format($order->paymentConfirmation->amount ?? $order->total, 0, ',', '.') }}</dd>
                <dt class="text-slate-500">Status Pembayaran</dt>
                <dd class="col-span-2">
                    <span class="text-xs px-2 py-1 rounded bg-slate-800 text-slate-300">
                        {{ str_replace('_', ' ', $order->paymentConfirmation->payment_status ?? 'unpaid') }}
                    </span>
                </dd>
                @if($order->paymentConfirmation?->whatsapp_clicked_at)
                    <dt class="text-slate-500">Konfirmasi WA</dt>
                    <dd class="col-span-2 text-slate-200">{{ $order->paymentConfirmation->whatsapp_clicked_at->format('d M Y H:i') }}</dd>
                @endif
            </dl>

            {{-- Payment status DIPISAH dari order status (README bagian U) --}}
            <form method="POST" action="{{ route('admin.orders.update-payment-status', $order->order_number) }}" class="flex flex-wrap gap-2 items-end">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-xs text-slate-500 mb-1">Ubah Status Pembayaran</label>
                    <select name="payment_status" class="rounded-md bg-slate-800 border border-slate-700 px-3 py-2 text-sm text-slate-100">
                        @foreach(['unpaid','waiting_confirmation','paid','rejected'] as $ps)
                            <option value="{{ $ps }}" @selected(($order->paymentConfirmation->payment_status ?? 'unpaid') === $ps)>{{ str_replace('_', ' ', $ps) }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="rounded-md bg-slate-700 text-slate-100 px-4 py-2 text-sm hover:bg-slate-600">Simpan</button>
            </form>
        </div>

        <!-- INFORMASI ORDER -->
        <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
            <h3 class="text-sm font-semibold text-slate-100 mb-3">Informasi Order</h3>
            <dl class="text-sm grid grid-cols-3 gap-y-1">
                <dt class="text-slate-500">Subtotal</dt><dd class="col-span-2 text-slate-200">Rp {{ number_format($order->total, 0, ',', '.') }}</dd>
                <dt class="text-slate-500">Diskon</dt><dd class="col-span-2 text-slate-200">-</dd>
                <dt class="text-slate-500 font-medium">Total</dt><dd class="col-span-2 text-sky-400 font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</dd>
            </dl>
        </div>
    </div>

    <div class="space-y-6">
        <!-- UPDATE STATUS -->
        <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
            <h3 class="text-sm font-semibold text-slate-100 mb-3">Update Status Order</h3>
            <div class="text-xs text-slate-500 mb-3">Status saat ini:
                <span class="text-slate-200">{{ str_replace('_', ' ', $order->status) }}</span>
            </div>

            @php $nextOptions = \App\Models\Order::ALLOWED_TRANSITIONS[$order->status] ?? []; @endphp

            @if(count($nextOptions))
                <form method="POST" action="{{ route('admin.orders.update-status', $order->order_number) }}" class="space-y-3" x-data="{ status: '{{ $nextOptions[0] }}' }">
                    @csrf @method('PATCH')
                    <select name="status" x-model="status" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 text-sm text-slate-100">
                        @foreach($nextOptions as $option)
                            <option value="{{ $option }}">{{ str_replace('_', ' ', $option) }}</option>
                        @endforeach
                    </select>
                    <div x-show="status === 'rejected' || status === 'cancelled'" x-cloak>
                        <label class="block text-xs text-slate-500 mb-1">Alasan (opsional tapi disarankan)</label>
                        <textarea name="note" rows="2" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 text-sm text-slate-100"></textarea>
                    </div>
                    <button class="w-full rounded-md bg-sky-500 text-slate-950 font-semibold px-4 py-2 text-sm hover:bg-sky-400">
                        Simpan Status
                    </button>
                </form>
            @else
                <p class="text-slate-500 text-sm">Order sudah pada status akhir.</p>
            @endif
        </div>

        <!-- TIMELINE -->
        <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
            <h3 class="text-sm font-semibold text-slate-100 mb-4">Timeline Status</h3>
            @include('partials.order-timeline')
        </div>

        @if($order->statusHistories->isNotEmpty())
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <h3 class="text-sm font-semibold text-slate-100 mb-3">Riwayat Perubahan</h3>
                <div class="space-y-3 text-xs">
                    @foreach($order->statusHistories as $history)
                        <div class="border-l-2 border-slate-700 pl-3">
                            <div class="text-slate-200">{{ str_replace('_', ' ', $history->status) }}</div>
                            <div class="text-slate-500">{{ $history->created_at->format('d M Y H:i') }} @if($history->changedBy) &middot; {{ $history->changedBy->name }} @endif</div>
                            @if($history->note)
                                <div class="text-slate-400 mt-1">{{ $history->note }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
