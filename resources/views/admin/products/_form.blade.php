@csrf
<div class="space-y-4 max-w-lg" enctype="multipart/form-data">
    <div>
        <label class="block text-sm text-slate-300 mb-1">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
               class="w-full rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">
        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1">Kategori</label>
        <select name="category_id" class="w-full rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1">Harga (Rp)</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}"
               class="w-full rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">
        @error('price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1">SKU (opsional)</label>
        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
               class="w-full rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1">Deskripsi</label>
        <textarea name="description" rows="4"
                  class="w-full rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">{{ old('description', $product->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1">Foto Produk</label>
        @if(!empty($product) && $product->main_image_url)
            <img src="{{ $product->main_image_url }}" class="w-20 h-20 object-cover rounded mb-2">
        @endif
        <input type="file" name="main_image" accept="image/*" class="text-sm text-slate-300">
        @error('main_image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <label class="flex items-center gap-2 text-sm text-slate-300">
        <input type="checkbox" name="status" value="1" @checked(old('status', $product->status ?? true))>
        Aktif
    </label>
    <label class="flex items-center gap-2 text-sm text-slate-300">
        <input type="checkbox" name="featured" value="1" @checked(old('featured', $product->featured ?? false))>
        Featured
    </label>
    <button class="rounded-md bg-sky-500 text-slate-950 font-semibold px-5 py-2 text-sm hover:bg-sky-400">Simpan</button>
</div>
