<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Discount;
use App\Models\TierDiscount;
use App\Models\User;
use App\Models\VolumeDiscount;
use App\Models\Voucher;
use Illuminate\Support\Collection;

class DiscountEngine
{
    /**
     * Urutan perhitungan:
     * 1. Harga Awal
     * 2. Diskon Produk
     * 3. Diskon Kategori
     * 4. Diskon Kuantitas
     * 5. Diskon Member Tier
     * 6. Voucher
     */
    public function calculate(Collection $cartItems, User $user, ?string $voucherCode = null): array
    {
        $breakdown = [];
        $subtotalAfterItemDiscounts = 0;

        foreach ($cartItems as $item) {
            $product   = $item->product;
            $basePrice = $item->price;
            $quantity  = $item->quantity;
            $lineTotal = $basePrice * $quantity;

            $itemDiscounts = [];

            // 1. Diskon Produk
            $productDiscount = $this->getProductDiscount($product->id);
            if ($productDiscount) {
                $discountAmount = $this->calcDiscount($lineTotal, $productDiscount);
                $lineTotal     -= $discountAmount;
                $itemDiscounts[] = [
                    'type'   => 'product',
                    'label'  => $productDiscount->name,
                    'amount' => $discountAmount,
                ];
            }

            // 2. Diskon Kategori
            $categoryDiscount = $this->getCategoryDiscount($product->category_id);
            if ($categoryDiscount) {
                $discountAmount = $this->calcDiscount($lineTotal, $categoryDiscount);
                $lineTotal     -= $discountAmount;
                $itemDiscounts[] = [
                    'type'   => 'category',
                    'label'  => $categoryDiscount->name,
                    'amount' => $discountAmount,
                ];
            }

            // 3. Diskon Kuantitas
            $volumeDiscount = $this->getVolumeDiscount($product->id, $quantity);
            if ($volumeDiscount) {
                $discountAmount = $this->calcDiscount($lineTotal, $volumeDiscount);
                $lineTotal     -= $discountAmount;
                $itemDiscounts[] = [
                    'type'   => 'volume',
                    'label'  => "Beli {$quantity} pcs",
                    'amount' => $discountAmount,
                ];
            }

            $breakdown[] = [
                'product'        => $product->name,
                'original_price' => $basePrice * $quantity,
                'final_price'    => max(0, $lineTotal),
                'discounts'      => $itemDiscounts,
            ];

            $subtotalAfterItemDiscounts += max(0, $lineTotal);
        }

        // 4. Diskon Member Tier
        $tierDiscount       = 0;
        $tierDiscountLabel  = null;
        $tierConfig         = TierDiscount::where('tier', $user->tier)->first();

        if ($tierConfig && $user->tier !== 'regular') {
            $tierDiscount      = $subtotalAfterItemDiscounts * ($tierConfig->value / 100);
            $tierDiscountLabel = "Member " . ucfirst($user->tier) . " ({$tierConfig->value}%)";
        }

        $subtotalAfterTier = $subtotalAfterItemDiscounts - $tierDiscount;

        // 5. Voucher
        $voucherDiscount      = 0;
        $voucherError         = null;
        $voucherDiscountLabel = null;

        if ($voucherCode) {
            $voucher = Voucher::where('code', strtoupper($voucherCode))->first();

            if (!$voucher) {
                $voucherError = 'Kode voucher tidak ditemukan.';
            } else {
                // Cek apakah voucher bisa dipakai bersamaan dengan diskon item
                $totalItemDiscountPercent = $this->getTotalItemDiscountPercent($cartItems);
                if (!$this->isVoucherStackable($voucher, $totalItemDiscountPercent)) {
                    $voucherError = 'Voucher tidak berlaku untuk produk yang sudah didiskon lebih dari 50%.';
                } else {
                    [$valid, $message] = $voucher->isValid($subtotalAfterTier);
                    if ($valid) {
                        $voucherDiscount      = $voucher->calculateDiscount($subtotalAfterTier);
                        $voucherDiscountLabel = "Voucher {$voucher->code}";
                    } else {
                        $voucherError = $message;
                    }
                }
            }
        }

        $finalTotal = max(0, $subtotalAfterTier - $voucherDiscount);

        return [
            'breakdown'             => $breakdown,
            'subtotal_original'     => $cartItems->sum(fn($i) => $i->price * $i->quantity),
            'subtotal_after_items'  => $subtotalAfterItemDiscounts,
            'tier_discount'         => $tierDiscount,
            'tier_discount_label'   => $tierDiscountLabel,
            'subtotal_after_tier'   => $subtotalAfterTier,
            'voucher_discount'      => $voucherDiscount,
            'voucher_discount_label'=> $voucherDiscountLabel,
            'voucher_error'         => $voucherError,
            'final_total'           => $finalTotal,
        ];
    }

    protected function getProductDiscount(int $productId): ?Discount
    {
        return Discount::where('type', 'product')
            ->where('target_id', $productId)
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->first();
    }

    protected function getCategoryDiscount(int $categoryId): ?Discount
    {
        return Discount::where('type', 'category')
            ->where('target_id', $categoryId)
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->first();
    }

    protected function getVolumeDiscount(int $productId, int $quantity): ?VolumeDiscount
    {
        return VolumeDiscount::where('product_id', $productId)
            ->where('min_quantity', '<=', $quantity)
            ->where('is_active', true)
            ->orderByDesc('min_quantity') // ambil tier tertinggi yang memenuhi syarat
            ->first();
    }

    protected function calcDiscount(float $amount, $discount): float
    {
        if ($discount->value_type === 'percentage') {
            $result = $amount * ($discount->value / 100);
            if (isset($discount->max_discount) && $discount->max_discount) {
                $result = min($result, $discount->max_discount);
            }
            return $result;
        }

        return min($discount->value, $amount);
    }

    protected function getTotalItemDiscountPercent(Collection $cartItems): float
    {
        // Hitung rata-rata persentase diskon item keseluruhan
        $originalTotal  = $cartItems->sum(fn($i) => $i->price * $i->quantity);
        $discountedTotal = 0;

        foreach ($cartItems as $item) {
            $productDiscount  = $this->getProductDiscount($item->product_id);
            $categoryDiscount = $this->getCategoryDiscount($item->product->category_id);
            $lineTotal        = $item->price * $item->quantity;

            if ($productDiscount)  $lineTotal -= $this->calcDiscount($lineTotal, $productDiscount);
            if ($categoryDiscount) $lineTotal -= $this->calcDiscount($lineTotal, $categoryDiscount);

            $discountedTotal += $lineTotal;
        }

        if ($originalTotal == 0) return 0;

        return (($originalTotal - $discountedTotal) / $originalTotal) * 100;
    }

    protected function isVoucherStackable(Voucher $voucher, float $itemDiscountPercent): bool
    {
        // Voucher tidak berlaku kalau total diskon item sudah > 50%
        return $itemDiscountPercent <= 50;
    }
}