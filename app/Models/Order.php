<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_WAITING_PAYMENT = 'waiting_payment';
    public const STATUS_PAYMENT_CONFIRMED = 'payment_confirmed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Peta transisi status yang SAH. Ini satu-satunya sumber kebenaran
     * di backend — dropdown di frontend tidak menentukan apapun.
     */
    public const ALLOWED_TRANSITIONS = [
        self::STATUS_PENDING => [self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_CANCELLED],
        self::STATUS_APPROVED => [self::STATUS_WAITING_PAYMENT, self::STATUS_CANCELLED],
        self::STATUS_WAITING_PAYMENT => [self::STATUS_PAYMENT_CONFIRMED, self::STATUS_CANCELLED],
        self::STATUS_PAYMENT_CONFIRMED => [self::STATUS_PROCESSING],
        self::STATUS_PROCESSING => [self::STATUS_COMPLETED],
        self::STATUS_REJECTED => [],
        self::STATUS_COMPLETED => [],
        self::STATUS_CANCELLED => [],
    ];

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentConfirmation()
    {
        return $this->hasOne(PaymentConfirmation::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at');
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::ALLOWED_TRANSITIONS[$this->status] ?? [], true);
    }

    /**
     * Catat perubahan status ke order_status_histories (README bagian T).
     * $changedBy null berarti perubahan dilakukan sistem (misal saat order dibuat).
     */
    public function logStatus(string $status, ?string $note = null, ?int $changedBy = null): void
    {
        $this->statusHistories()->create([
            'status' => $status,
            'note' => $note,
            'changed_by' => $changedBy,
        ]);
    }

    /**
     * Urutan tetap untuk menggambar timeline visual, terlepas dari histori
     * aktual yang tersimpan (misalnya kalau ada status yang di-skip).
     */
    public const TIMELINE_STEPS = [
        self::STATUS_PENDING => 'Pesanan dibuat',
        self::STATUS_APPROVED => 'Pesanan disetujui',
        self::STATUS_WAITING_PAYMENT => 'Menunggu pembayaran',
        self::STATUS_PAYMENT_CONFIRMED => 'Pembayaran dikonfirmasi',
        self::STATUS_PROCESSING => 'Sedang diproses',
        self::STATUS_COMPLETED => 'Selesai',
    ];

    /**
     * Generate order_number unik: ORD-YYYYMMDD-00001
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-' . now()->format('Ymd') . '-';
        $lastToday = self::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('order_number')
            ->first();

        $nextSequence = $lastToday
            ? ((int) substr($lastToday->order_number, -5)) + 1
            : 1;

        return $prefix . str_pad((string) $nextSequence, 5, '0', STR_PAD_LEFT);
    }
}
