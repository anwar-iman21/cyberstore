<?php

namespace App\Http\Controllers;

use App\Models\Order;

class InvoiceController extends Controller
{
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items', 'user', 'paymentConfirmation');

        return view('invoices.show', compact('order'));
    }
}
