<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_purchase', 'max_discount',
        'usage_limit', 'used_count', 'is_active', 'starts_at', 'expires_at'
    ];

    protected $casts = [
        'value'        => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active'    => 'boolean',
        'starts_at'    => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function isValid(float $subtotal): array
    {
        if (!$this->is_active) {
            return [false, 'Voucher tidak aktif.'];
        }

        if ($this->starts_at && now()->lt($this->starts_at)) {
            return [false, 'Voucher belum berlaku.'];
        }

        if ($this->expires_at && now()->gt($this->expires_at)) {
            return [false, 'Voucher sudah expired.'];
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return [false, 'Voucher sudah mencapai batas pemakaian.'];
        }

        if ($subtotal < $this->min_purchase) {
            return [false, 'Minimal belanja Rp ' . number_format($this->min_purchase, 0, ',', '.') . ' untuk voucher ini.'];
        }

        return [true, null];
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            return $discount;
        }

        return min($this->value, $subtotal);
    }
}