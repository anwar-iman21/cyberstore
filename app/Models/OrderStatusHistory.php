<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    // Tabel ini cuma punya created_at (dicatat sekali, tidak pernah diubah)
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'status',
        'note',
        'changed_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $history) {
            $history->created_at ??= now();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
