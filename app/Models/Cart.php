<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Total dihitung ulang dari harga produk TERKINI di database,
     * bukan dari nilai apapun yang tersimpan/dikirim frontend.
     */
    public function getTotalAttribute(): float
    {
        return $this->items->sum(fn (CartItem $item) => $item->subtotal);
    }
}
