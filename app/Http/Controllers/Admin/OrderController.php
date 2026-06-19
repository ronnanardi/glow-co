<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items', 'address', 'shipment');
        return view('admin.orders.show', compact('order'));
    }

    // Konfirmasi pembayaran
    public function confirmPayment(Order $order)
    {
        abort_if($order->status !== Order::STATUS_PENDING, 403);

        $order->update([
            'status'  => Order::STATUS_PAID,
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Pembayaran dikonfirmasi.');
    }

    // Update status order
    public function updateStatus(Request $request, Order $order)
    {
         $request->validate([
            'status' => 'required|in:processed,shipped,cancelled',
        ]);

        $allowedTransitions = [
            'paid'      => ['processed', 'cancelled'],
            'processed' => ['shipped', 'cancelled'],
            'shipped'   => [],
        ];

        if (!in_array($request->status, $allowedTransitions[$order->status] ?? [])) {
            return back()->with('error', 'Perubahan status tidak valid.');
        }

        // Kembalikan stok kalau dibatalkan
        if ($request->status === 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status order diupdate.');
    }

    // Input resi pengiriman
    public function addShipment(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number'   => 'required|string',
            'estimated_arrival' => 'nullable|date',
        ]);

        Shipment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'courier'           => $order->courier,
                'service'           => $order->courier_service,
                'tracking_number'   => $request->tracking_number,
                'shipping_cost'     => $order->shipping_cost,
                'status'            => Shipment::STATUS_PICKED_UP,
                'estimated_arrival' => $request->estimated_arrival,
            ]
        );

        $order->update(['status' => Order::STATUS_SHIPPED]);

        return back()->with('success', 'Resi pengiriman berhasil ditambahkan.');
    }
}