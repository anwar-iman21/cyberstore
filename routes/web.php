<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute publik
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Rute auth bawaan Breeze (login, register, forgot-password, dst)
|--------------------------------------------------------------------------
| Dicek dulu file_exists supaya web.php ini tetap aman dipakai walaupun
| Breeze belum/gagal di-install (misalnya saat composer require breeze
| menjalankan artisan package:discover sebelum breeze:install selesai).
*/
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}

/*
|--------------------------------------------------------------------------
| Rute customer (butuh login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order:order_number}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order:order_number}/whatsapp-confirmation', [OrderController::class, 'confirmViaWhatsapp'])
        ->name('orders.whatsapp-confirmation');
    Route::get('/orders/{order:order_number}/invoice', [InvoiceController::class, 'show'])->name('orders.invoice');
});

/*
|--------------------------------------------------------------------------
| Rute admin (butuh login + role admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('products', AdminProductController::class)->except('show');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order:order_number}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order:order_number}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('orders.update-status');
    Route::patch('/orders/{order:order_number}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])
        ->name('orders.update-payment-status');

    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');

    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});