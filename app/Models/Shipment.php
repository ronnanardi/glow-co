<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    //
     protected $fillable = [
        'order_id', 'courier', 'service', 'tracking_number',
        'shipping_cost', 'status', 'estimated_arrival'
    ];

    protected $casts = [
        'shipping_cost'    => 'decimal:2',
        'estimated_arrival' => 'datetime',
    ];

    const STATUS_PENDING    = 'pending';
    const STATUS_PICKED_UP  = 'picked_up';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_DELIVERED  = 'delivered';
    const STATUS_RETURNED   = 'returned';

    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    // Relasi
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
