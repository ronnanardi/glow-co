<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'user_id', 'address_id', 'order_number', 'total_price',
        'shipping_cost', 'courier', 'courier_service',
        'status', 'payment_method', 'payment_proof', 'paid_at',
        'completed_at',
    ];

    protected $casts = [
        'total_price'   => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'paid_at'       => 'datetime',
        'completed_at'  => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING   = 'pending';
    const STATUS_PAID      = 'paid';
    const STATUS_PROCESSED = 'processed';
    const STATUS_SHIPPED   = 'shipped';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Generate order number otomatis saat dibuat
    protected static function booted()
    {
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . now()->format('Ymd') . '-' . str_pad(
                static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT
            );
        });
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PAID]);
    }

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
}
