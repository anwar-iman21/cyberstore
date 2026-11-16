<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->orderBy('name')->get();

        $products = Product::query()
            ->active()
            ->with('category')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $request->string('category')));
            })
            ->orderByDesc('featured')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_unless($product->status, 404);

        $product->load('category');

        return view('products.show', compact('product'));
    }
}
