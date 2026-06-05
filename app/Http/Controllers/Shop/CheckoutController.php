<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $addresses = Address::where('user_id', Auth::id())->get();

        return view('customer.shop.checkout', compact('cart', 'addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id'     => 'required|exists:addresses,id',
            'payment_method' => 'required|string',
        ]);

        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        DB::transaction(function () use ($request, $cart) {

            // Buat order
            $order = Order::create([
                'user_id'        => Auth::id(),
                'address_id'     => $request->address_id,
                'total_price'    => $cart->total,
                'status'         => Order::STATUS_PENDING,
                'payment_method' => $request->payment_method,
            ]);

            // Buat order items (snapshot)
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $item->price * $item->quantity,
                ]);

                // Kurangi stok
                $item->product->decrement('stock', $item->quantity);
            }

            // Kosongkan keranjang
            $cart->items()->delete();
        });

        return redirect()->route('customer.orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}