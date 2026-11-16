<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->cart()->with('items.product')->first();

        abort_if(! $cart || $cart->items->isEmpty(), 400, 'Keranjang kosong.');

        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $cart = $request->user()->cart()->with('items.product')->first();
        abort_if(! $cart || $cart->items->isEmpty(), 400, 'Keranjang kosong.');

        foreach ($cart->items as $item) {
            abort_unless(
                $item->product && $item->product->status,
                422,
                'Salah satu produk di keranjang sudah tidak tersedia. Silakan perbarui keranjang.'
            );
        }

        $order = DB::transaction(function () use ($cart, $request) {
            // Harga & total dihitung ULANG di sini dari data produk terkini,
            // tidak pernah mempercayai nilai apapun dari frontend.
            $total = $cart->items->sum(fn ($item) => $item->product->price * $item->quantity);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $request->user()->id,
                'status' => Order::STATUS_PENDING,
                'total' => $total,
            ]);

            // README bagian T: setiap perubahan status tercatat di histori
            $order->logStatus(Order::STATUS_PENDING, changedBy: $request->user()->id);

            // README bagian U: record payment terpisah dari order, dimulai UNPAID
            $order->paymentConfirmation()->create([
                'payment_method' => 'Transfer Manual',
                'amount' => $total,
                'payment_status' => \App\Models\PaymentConfirmation::STATUS_UNPAID,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Order berhasil dibuat. Menunggu persetujuan admin.');
    }
}
