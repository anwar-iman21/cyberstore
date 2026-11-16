@extends('layouts.admin')
@section('title', 'Kategori')

@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.categories.create') }}" class="rounded-md bg-sky-500 text-slate-950 font-semibold px-4 py-2 text-sm hover:bg-sky-400">
        + Tambah Kategori
    </a>
</div>

<div class="overflow-x-auto bg-slate-900 border border-slate-800 rounded-lg">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-slate-500 border-b border-slate-800">
                <th class="py-3 px-4">Nama</th>
                <th class="py-3 px-4">Jumlah Produk</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @foreach($categories as $category)
                <tr>
                    <td class="py-3 px-4 text-slate-100 font-medium">{{ $category->name }}</td>
                    <td class="py-3 px-4 text-slate-300">{{ $category->products_count }}</td>
                    <td class="py-3 px-4">
                        <span class="text-xs px-2 py-1 rounded {{ $category->status ? 'bg-emerald-900/40 text-emerald-300' : 'bg-slate-800 text-slate-400' }}">
                            {{ $category->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 flex gap-3">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-sky-400 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $categories->links() }}</div>
@endsection
