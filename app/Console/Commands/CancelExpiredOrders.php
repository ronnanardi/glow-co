<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Order;

#[Signature('app:cancel-expired-orders')]
#[Description('Command description')]
class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';
    protected $description = 'Batalkan order pending yang sudah lewat 24 jam dan kembalikan stok';

    public function handle()
    {
        $expiredOrders = Order::where('status', Order::STATUS_PENDING)
            ->where('created_at', '<=', now()->subHours(2))
            ->get();

        foreach ($expiredOrders as $order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => Order::STATUS_CANCELLED]);
        }

        $this->info("Berhasil membatalkan {$expiredOrders->count()} order yang expired.");
    }
}
