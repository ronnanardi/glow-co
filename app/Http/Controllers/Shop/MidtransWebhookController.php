<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans webhook received', $payload);

        // Pastikan field wajib ada sebelum diproses
        if (!isset($payload['order_id'], $payload['status_code'], $payload['gross_amount'], $payload['signature_key'], $payload['transaction_status'])) {
            Log::warning('Midtrans webhook: incomplete payload', $payload);
            return response()->json(['message' => 'Incomplete payload'], 400);
        }

        $serverKey = config('services.midtrans.server_key');
        $expectedSignature = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );

        if ($expectedSignature !== $payload['signature_key']) {
            Log::warning('Midtrans webhook: invalid signature', [
                'order_id' => $payload['order_id'],
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderNumber       = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus       = $payload['fraud_status'] ?? null;
        $paymentType       = $payload['payment_type'] ?? null;
        $transactionId     = $payload['transaction_id'] ?? null;

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            Log::warning('Midtrans webhook: order not found', ['order_id' => $orderNumber]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->update([
            'payment_type'             => $paymentType,
            'midtrans_transaction_id'  => $transactionId,
        ]);

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $this->markAsPaid($order);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->markAsPaid($order);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $this->markAsCancelled($order);
        }

        return response()->json(['message' => 'OK']);
    }

    protected function markAsPaid(Order $order): void
    {
        if ($order->status !== Order::STATUS_PENDING) {
            return;
        }

        $order->update([
            'status'  => Order::STATUS_PAID,
            'paid_at' => now(),
        ]);

        Log::info('Order marked as paid', ['order_number' => $order->order_number]);
    }

    protected function markAsCancelled(Order $order): void
    {
        if ($order->status !== Order::STATUS_PENDING) {
            return;
        }

        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        Log::info('Order marked as cancelled', ['order_number' => $order->order_number]);
    }
}