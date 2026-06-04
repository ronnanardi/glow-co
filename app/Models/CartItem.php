<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //
    protected $fillable = [
        'cart_id', 'product_id', 'quantity', 'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Subtotal per item
    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    // Relasi
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
