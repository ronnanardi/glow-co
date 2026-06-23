<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name', 'type', 'value_type', 'value',
        'max_discount', 'target_id', 'starts_at',
        'expires_at', 'is_active', 'stackable'
    ];

    protected $casts = [
        'value'        => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active'    => 'boolean',
        'stackable'    => 'boolean',
        'starts_at'    => 'datetime',
        'expires_at'   => 'datetime',
    ];
}