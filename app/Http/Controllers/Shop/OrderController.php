<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
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
}