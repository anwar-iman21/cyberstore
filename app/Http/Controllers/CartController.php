<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->cart()->firstOrCreate([]);
        $cart->load('items.product.category');

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        abort_unless($product->status, 404, 'Produk tidak tersedia.');

        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);
        $quantity = $data['quantity'] ?? 1;

        $cart = $request->user()->cart()->firstOrCreate([]);

        $item = $cart->items()->where('product_id', $product->id)->first();
        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeItem($request, $cartItem);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cartItem->update(['quantity' => $data['quantity']]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        $this->authorizeItem($request, $cartItem);
        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    private function authorizeItem(Request $request, CartItem $cartItem): void
    {
        abort_unless($cartItem->cart->user_id === $request->user()->id, 403);
    }
}
