<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')
                       ->where('user_id', Auth::id())
                       ->latest()
                       ->paginate(10);

        return view('customer.shop.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Pastikan order milik user yang login
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load('items', 'address', 'shipment');

        return view('customer.shop.orders.show', compact('order'));
    }

    public function complete(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        // Hanya bisa complete jika status shipped
        if ($order->status !== Order::STATUS_SHIPPED) {
            return back()->with('error', 'Pesanan tidak bisa dikonfirmasi.');
        }

        $order->update([
            'status'       => Order::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        if ($order->shipment) {
            $order->shipment->update(['status' => Shipment::STATUS_DELIVERED]);
        }

        return back()->with('success', 'Terima kasih! Pesanan telah dikonfirmasi diterima.');

    }
}