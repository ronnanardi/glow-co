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
use App\Services\RajaOngkirService;



class CheckoutController extends Controller
{
    public function checkOngkir(Request $request, RajaOngkirService $rajaOngkir)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'courier'    => 'required|in:jne,tiki,pos',
        ]);

        $address = Address::findOrFail($request->address_id);

        // Hitung total berat (asumsi tiap produk 200 gram)
        $cart    = Cart::with('items')->where('user_id', Auth::id())->first();
        $weight  = $cart->items->sum(fn($item) => $item->quantity * 200);

        $costs = $rajaOngkir->checkOngkir($address->city_id, $weight, $request->courier);

        return response()->json($costs);
    }

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
        'address_id'      => 'required|exists:addresses,id',
        'payment_method'  => 'required|string',
        'courier'         => 'required|string',
        'courier_service' => 'required|string',
        'shipping_cost'   => 'required|numeric',
    ]);

    $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

    if (!$cart || $cart->items->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
    }

    DB::transaction(function () use ($request, $cart) {

        $order = Order::create([
            'user_id'         => Auth::id(),
            'address_id'      => $request->address_id,
            'total_price'     => $cart->total + $request->shipping_cost,
            'shipping_cost'   => $request->shipping_cost,
            'courier'         => $request->courier,
            'courier_service' => $request->courier_service,
            'status'          => Order::STATUS_PENDING,
            'payment_method'  => $request->payment_method,
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item->product_id,
                'product_name' => $item->product->name,
                'price'        => $item->price,
                'quantity'     => $item->quantity,
                'subtotal'     => $item->price * $item->quantity,
            ]);

            $item->product->decrement('stock', $item->quantity);
        }

        $cart->items()->delete();
    });

    return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}