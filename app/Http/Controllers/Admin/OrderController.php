<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentConfirmation;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user', 'paymentConfirmation')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user', 'paymentConfirmation.verifiedBy', 'statusHistories.changedBy');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mengubah ORDER STATUS. Transisi ditentukan backend (Order::ALLOWED_TRANSITIONS),
     * bukan mengandalkan dropdown frontend (README bagian AI).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'string'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        abort_unless($order->canTransitionTo($data['status']), 422, 'Transisi status tidak diizinkan.');

        // README bagian J: REJECTED hanya boleh dari PENDING.
        if ($data['status'] === Order::STATUS_REJECTED) {
            abort_unless($order->status === Order::STATUS_PENDING, 422, 'Order hanya bisa ditolak saat masih pending.');
        }

        $order->update(['status' => $data['status']]);
        $order->logStatus($data['status'], $data['note'] ?? null, $request->user()->id);

        // Saat pembayaran dikonfirmasi admin, payment_status ikut disinkronkan
        // ke PAID (tapi tetap tabel/field yang terpisah dari order status).
        if ($data['status'] === Order::STATUS_PAYMENT_CONFIRMED) {
            $order->paymentConfirmation()->updateOrCreate([], [
                'payment_status' => PaymentConfirmation::STATUS_PAID,
                'paid_at' => now(),
                'verified_at' => now(),
                'verified_by' => $request->user()->id,
            ]);
        }

        return back()->with('success', 'Status order diperbarui menjadi "'.$data['status'].'".');
    }

    /**
     * Mengubah PAYMENT STATUS secara independen dari order status
     * (README bagian U — jangan mencampur keduanya ke satu field).
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'payment_status' => ['required', 'in:unpaid,waiting_confirmation,paid,rejected'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $confirmation = $order->paymentConfirmation()->firstOrCreate([
            'amount' => $order->total,
            'payment_method' => 'Transfer Manual',
        ]);

        $confirmation->update([
            'payment_status' => $data['payment_status'],
            'note' => $data['note'] ?? $confirmation->note,
            'paid_at' => $data['payment_status'] === PaymentConfirmation::STATUS_PAID ? now() : $confirmation->paid_at,
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Status pembayaran diperbarui.');
    }
}
