<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TierDiscount extends Model
{
    protected $fillable = ['tier', 'value'];

    protected $casts = ['value' => 'decimal:2'];
}