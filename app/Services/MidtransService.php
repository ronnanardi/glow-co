<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createSnapToken(Order $order): string
    {
        $order->load('user', 'items');

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email'      => $order->user->email,
                'phone'      => $order->user->phone ?? '',
            ],
            'item_details' => $this->buildItemDetails($order),
            'callbacks' => [
                'finish' => route('orders.show', $order),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $order->update(['snap_token' => $snapToken]);

        return $snapToken;
    }

    protected function buildItemDetails(Order $order): array
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id'       => 'PROD-' . $item->product_id,
                'price'    => (int) $item->price,
                'quantity' => $item->quantity,
                'name'     => substr($item->product_name, 0, 50),
            ];
        }

        // Tambahkan ongkir sebagai item terpisah
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        // Kurangi diskon (Midtrans butuh item dengan harga negatif untuk diskon)
        if ($order->discount > 0) {
            $items[] = [
                'id'       => 'DISCOUNT',
                'price'    => -(int) $order->discount,
                'quantity' => 1,
                'name'     => 'Diskon Voucher',
            ];
        }

        return $items;
    }
}