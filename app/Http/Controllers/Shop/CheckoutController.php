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
use App\Services\DiscountEngine;




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

    public function index(DiscountEngine $engine)
    {
        $cart = Cart::with('items.product.category')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $addresses = Address::where('user_id', Auth::id())->get();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $discountResult = $engine->calculate($cart->items, $user);

        return view('customer.shop.checkout', compact('cart', 'addresses', 'discountResult'));
    }

    public function store(Request $request, MidtransService $midtrans, DiscountEngine $engine)
    {
        $request->validate([
            'address_id'      => 'required|exists:addresses,id',
            'courier'         => 'required|string',
            'courier_service' => 'required|string',
            'shipping_cost'   => 'required|numeric',
            'voucher_code'    => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $cart = Cart::with('items.product.category')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        // Hitung semua diskon via engine
        $result = $engine->calculate($cart->items, $user, $request->voucher_code);

        if ($result['voucher_error']) {
            return back()->with('error', $result['voucher_error'])->withInput();
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with('error',
                    "Stok {$item->product->name} tidak cukup.");
            }
        }

        $order = DB::transaction(function () use ($request, $cart, $result, $user) {

            $total = $result['final_total'] + $request->shipping_cost;

            $order = Order::create([
                'user_id'         => $user->id,
                'address_id'      => $request->address_id,
                'total_price'     => $total,
                'shipping_cost'   => $request->shipping_cost,
                'courier'         => $request->courier,
                'courier_service' => $request->courier_service,
                'voucher_code'    => $request->voucher_code,
                'discount'        => $result['subtotal_original'] - $result['final_total'],
                'status'          => Order::STATUS_PENDING,
                'payment_method'  => 'midtrans',
            ]);

            foreach ($cart->items as $item) {
                $product = Product::where('id', $item->product_id)->lockForUpdate()->first();

                if ($item->quantity > $product->stock) {
                    throw new \Exception("Stok {$product->name} tidak cukup.");
                }

                // Cari harga final per item dari breakdown
                $itemBreakdown = collect($result['breakdown'])->firstWhere('product', $product->name);
                $finalItemPrice = $itemBreakdown
                    ? $itemBreakdown['final_price'] / $item->quantity
                    : $item->price;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $product->name,
                    'price'        => $finalItemPrice,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $finalItemPrice * $item->quantity,
                ]);

                $product->decrement('stock', $item->quantity);
            }

            if ($request->voucher_code && $result['voucher_discount'] > 0) {
                Voucher::where('code', strtoupper($request->voucher_code))->increment('used_count');
            }

            $cart->items()->delete();

            return $order;
        });

        $midtrans->createSnapToken($order);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lanjutkan pembayaran.');
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