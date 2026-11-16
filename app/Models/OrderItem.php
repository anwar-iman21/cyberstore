<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Bisa null kalau produk asli sudah dihapus — histori tetap aman
    // karena product_name & price di sini adalah snapshot.
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
