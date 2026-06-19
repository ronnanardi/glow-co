<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Tampilkan keranjang
    public function index()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        return view('customer.shop.cart', compact('cart'));
    }

    // Tambah produk ke keranjang
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->isAvailable()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Produk tidak tersedia.']);
            }
            return back()->with('error', 'Produk tidak tersedia.');
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->first();

        $existingQty  = $cartItem ? $cartItem->quantity : 0;
        $requestedQty = $existingQty + $request->quantity;

        if ($requestedQty > $product->stock) {
            $sisa = $product->stock - $existingQty;
            $msg  = $sisa <= 0
                ? "Stok {$product->name} sudah maksimal di keranjang."
                : "Maksimal bisa tambah {$sisa} lagi (stok: {$product->stock}).";

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg]);
            }
            return back()->with('error', $msg);
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $requestedQty]);
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
            ]);
        }

        $cart->load('items');
        $cartCount = $cart->items->sum('quantity');

        if ($request->expectsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Produk berhasil ditambahkan ke keranjang.',
                'cartCount' => $cartCount,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    // Update quantity
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($request->quantity > $cartItem->product->stock) {
            return back()->with('error',
                "Stok {$cartItem->product->name} hanya tersisa {$cartItem->product->stock}.");
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang berhasil diupdate.');
    }

    // Hapus item dari keranjang
    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->isAvailable()) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        if ($request->quantity > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $request->quantity]);
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
            ]);
        }

        // Langsung ke checkout
        return redirect()->route('checkout.index');
    }
}