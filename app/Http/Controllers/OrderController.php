<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items', 'paymentConfirmation', 'statusHistories');

        return view('orders.show', compact('order'));
    }

    public function confirmViaWhatsapp(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        abort_unless(
            $order->status === Order::STATUS_WAITING_PAYMENT,
            422,
            'Order belum dalam status menunggu pembayaran.'
        );

        $confirmation = $order->paymentConfirmation()->firstOrCreate([], [
            'payment_status' => \App\Models\PaymentConfirmation::STATUS_UNPAID,
            'amount' => $order->total,
        ]);
        $confirmation->update([
            'whatsapp_clicked_at' => now(),
            'payment_status' => \App\Models\PaymentConfirmation::STATUS_WAITING_CONFIRMATION,
        ]);

        $adminWhatsapp = Setting::get('whatsapp_number', '');
        $message = "Halo Admin, saya ingin konfirmasi pembayaran untuk order {$order->order_number} "
            ."sebesar Rp".number_format((float) $order->total, 0, ',', '.');

        $waLink = 'https://wa.me/'.preg_replace('/\D/', '', (string) $adminWhatsapp).'?text='.urlencode($message);

        return redirect()->away($waLink);
    }
}