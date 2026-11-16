<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'waiting_payment' => Order::where('status', Order::STATUS_WAITING_PAYMENT)->count(),
            'processing_orders' => Order::where('status', Order::STATUS_PROCESSING)->count(),
            'completed_orders' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            // Total Revenue dihitung dari order yang sudah COMPLETED saja
            // (pendapatan yang benar-benar terealisasi, bukan order yang masih berjalan).
            'total_revenue' => Order::where('status', Order::STATUS_COMPLETED)->sum('total'),
        ];

        $recentOrders = Order::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
