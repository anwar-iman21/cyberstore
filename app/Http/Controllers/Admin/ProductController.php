<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);
        if ($data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Kalau produk sudah pernah dipesan, JANGAN hard delete —
        // supaya histori order_items (snapshot) tidak rusak. Nonaktifkan saja.
        if ($product->orderItems()->exists()) {
            $product->update(['status' => false]);

            return back()->with('success', 'Produk memiliki histori order sehingga hanya dinonaktifkan, bukan dihapus permanen.');
        }

        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sku' => ['nullable', 'string', 'max:100'],
            'main_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['status'] = $request->boolean('status');
        $data['featured'] = $request->boolean('featured');

        return $data;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-".$i++;
        }

        return $slug;
    }
}
