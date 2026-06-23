<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolumeDiscount extends Model
{
    protected $fillable = [
        'product_id', 'min_quantity', 'value_type', 'value', 'is_active'
    ];

    protected $casts = [
        'value'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}