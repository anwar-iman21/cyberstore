@extends('layouts.admin')
@section('title', 'Produk')

@section('content')
<div class="flex flex-wrap justify-between items-center gap-3 mb-4">
    <form method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..."
               class="rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">
        <button class="rounded-md bg-slate-800 text-slate-100 px-4 py-2 text-sm hover:bg-slate-700">Cari</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="rounded-md bg-sky-500 text-slate-950 font-semibold px-4 py-2 text-sm hover:bg-sky-400">
        + Tambah Produk
    </a>
</div>

<div class="overflow-x-auto bg-slate-900 border border-slate-800 rounded-lg">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-slate-500 border-b border-slate-800">
                <th class="py-3 px-4">Produk</th>
                <th class="py-3 px-4">Kategori</th>
                <th class="py-3 px-4">Harga</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @foreach($products as $product)
                <tr>
                    <td class="py-3 px-4 text-slate-100 font-medium flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-800 rounded overflow-hidden shrink-0">
                            @if($product->main_image_url)
                                <img src="{{ $product->main_image_url }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        {{ $product->name }}
                        @if($product->featured) <span class="text-[10px] bg-sky-500/10 text-sky-400 px-1.5 py-0.5 rounded">Unggulan</span> @endif
                    </td>
                    <td class="py-3 px-4 text-slate-300">{{ $product->category->name }}</td>
                    <td class="py-3 px-4 text-sky-400">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        <span class="text-xs px-2 py-1 rounded {{ $product->status ? 'bg-emerald-900/40 text-emerald-300' : 'bg-slate-800 text-slate-400' }}">
                            {{ $product->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 flex gap-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-sky-400 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $products->links() }}</div>
@endsection
