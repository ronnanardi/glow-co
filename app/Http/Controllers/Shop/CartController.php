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
            return back()->with('error', 'Produk tidak tersedia.');
        }

        // Ambil atau buat keranjang user
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Cek apakah produk sudah ada di keranjang
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
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