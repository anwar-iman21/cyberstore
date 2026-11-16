@extends('layouts.admin')
@section('title', 'Pelanggan')

@section('content')
<form method="GET" class="mb-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/email..."
           class="rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">
</form>

<div class="overflow-x-auto bg-slate-900 border border-slate-800 rounded-lg">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-slate-500 border-b border-slate-800">
                <th class="py-3 px-4">Nama</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">WhatsApp</th>
                <th class="py-3 px-4">Jumlah Order</th>
                <th class="py-3 px-4">Bergabung</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @foreach($customers as $customer)
                <tr>
                    <td class="py-3 px-4 text-slate-100 font-medium">{{ $customer->name }}</td>
                    <td class="py-3 px-4 text-slate-300">{{ $customer->email }}</td>
                    <td class="py-3 px-4 text-slate-300">{{ $customer->whatsapp_number }}</td>
                    <td class="py-3 px-4 text-slate-300">{{ $customer->orders_count }}</td>
                    <td class="py-3 px-4 text-slate-500">{{ $customer->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $customers->links() }}</div>
@endsection
