<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentConfirmation extends Model
{
    // README bagian U: payment_status DIPISAH dari order status
    public const STATUS_UNPAID = 'unpaid';
    public const STATUS_WAITING_CONFIRMATION = 'waiting_confirmation';
    public const STATUS_PAID = 'paid';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'payment_status',
        'paid_at',
        'whatsapp_clicked_at',
        'verified_at',
        'verified_by',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'whatsapp_clicked_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
