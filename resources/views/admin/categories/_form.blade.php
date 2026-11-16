@csrf
<div class="space-y-4 max-w-lg">
    <div>
        <label class="block text-sm text-slate-300 mb-1">Nama Kategori</label>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}"
               class="w-full rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">
        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1">Deskripsi</label>
        <textarea name="description" rows="3"
                  class="w-full rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
    <label class="flex items-center gap-2 text-sm text-slate-300">
        <input type="checkbox" name="status" value="1" @checked(old('status', $category->status ?? true))>
        Aktif
    </label>
    <button class="rounded-md bg-sky-500 text-slate-950 font-semibold px-5 py-2 text-sm hover:bg-sky-400">Simpan</button>
</div>
