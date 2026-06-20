<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('customer.shop.wishlist.index', compact('wishlists'));
    }

    // Toggle: tambah kalau belum ada, hapus kalau sudah ada
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = 'Dihapus dari wishlist';
        } else {
            Wishlist::create([
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
            ]);
            $status = 'added';
            $message = 'Ditambahkan ke wishlist ❤️';
        }

        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success'       => true,
                'status'        => $status,
                'message'       => $message,
                'wishlistCount' => $wishlistCount,
            ]);
        }

        return back()->with('success', $message);
    }

    public function destroy(Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== Auth::id(), 403);

        $wishlist->delete();

        return back()->with('success', 'Dihapus dari wishlist.');
    }
}