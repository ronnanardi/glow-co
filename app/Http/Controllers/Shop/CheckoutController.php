<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Voucher;
use App\Services\RajaOngkirService;
use App\Services\MidtransService;



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

    public function store(Request $request, MidtransService $midtrans)
    {
        $request->validate([
            'address_id'      => 'required|exists:addresses,id',
            'courier'         => 'required|string',
            'courier_service' => 'required|string',
            'shipping_cost'   => 'required|numeric',
            'voucher_code'    => 'nullable|string',
        ]);

        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with('error',
                    "Stok {$item->product->name} tidak cukup (tersisa {$item->product->stock}).");
            }
        }

        $discount = 0;
        if ($request->voucher_code) {
            $voucher = \App\Models\Voucher::where('code', $request->voucher_code)->first();
            if ($voucher) {
                [$valid] = $voucher->isValid($cart->total);
                if ($valid) {
                    $discount = $voucher->calculateDiscount($cart->total);
                }
            }
        }

        /** @var Order $order */
        $order = DB::transaction(function () use ($request, $cart, $discount) {

            $total = $cart->total + $request->shipping_cost - $discount;

            $order = Order::create([
                'user_id'         => Auth::id(),
                'address_id'      => $request->address_id,
                'total_price'     => $total,
                'shipping_cost'   => $request->shipping_cost,
                'courier'         => $request->courier,
                'courier_service' => $request->courier_service,
                'voucher_code'    => $discount > 0 ? $request->voucher_code : null,
                'discount'        => $discount,
                'status'          => Order::STATUS_PENDING,
                'payment_method'  => 'midtrans',
            ]);

            foreach ($cart->items as $item) {
                $product = Product::where('id', $item->product_id)->lockForUpdate()->first();

                if ($item->quantity > $product->stock) {
                    throw new \Exception("Stok {$product->name} tidak cukup.");
                }

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $item->price * $item->quantity,
                ]);

                $product->decrement('stock', $item->quantity);
            }

            if ($discount > 0 && $request->voucher_code) {
                \App\Models\Voucher::where('code', $request->voucher_code)->increment('used_count');
            }

            $cart->items()->delete();

            return $order; // return dari closure, bukan pass by reference
        });

        $midtrans->createSnapToken($order);

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat! Silakan lanjutkan pembayaran.');
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', strtoupper($request->code))->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Kode voucher tidak ditemukan.']);
        }

        $cart     = Cart::with('items')->where('user_id', Auth::id())->first();
        $subtotal = $cart->total;

        [$valid, $message] = $voucher->isValid($subtotal);

        if (!$valid) {
            return response()->json(['success' => false, 'message' => $message]);
        }

        $discount = $voucher->calculateDiscount($subtotal);

        return response()->json([
            'success'  => true,
            'message'  => 'Voucher berhasil digunakan!',
            'code'     => $voucher->code,
            'discount' => $discount,
        ]);
    }
}