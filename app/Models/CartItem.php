<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * PENTING: harga SELALU diambil dari relasi product (live dari database),
     * tidak pernah disimpan/dipercaya dari input frontend.
     */
    public function getPriceAttribute(): float
    {
        return (float) $this->product->price;
    }

    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}
