<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'stock', 'image', 'is_active',
        'avg_rating', 'reviews_count',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Scope produk aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Cek stok tersedia
    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    // Relasi
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function updateRatingCache(): void
    {
        $this->update([
            'avg_rating'    => $this->reviews()->avg('rating') ?? 0,
            'reviews_count' => $this->reviews()->count(),
        ]);
    }
}
