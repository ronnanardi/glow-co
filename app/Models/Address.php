<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'label', 'recipient_name', 'phone',
        'address', 'city', 'province', 'postal_code', 'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Saat set primary, nonaktifkan yang lain
    protected static function booted()
    {
        static::saving(function ($address) {
            if ($address->is_primary) {
                static::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_primary' => false]);
            }
        });
    }

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
