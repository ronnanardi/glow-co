<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(OrderItem $orderItem)
    {
        // Pastikan order item ini milik user yang login & order sudah completed
        abort_if($orderItem->order->user_id !== Auth::id(), 403);
        abort_if($orderItem->order->status !== 'completed', 403);
        abort_if($orderItem->review, 403, 'Produk ini sudah direview.');

        return view('customer.shop.reviews.create', compact('orderItem'));
    }

    public function store(Request $request, OrderItem $orderItem)
    {
        abort_if($orderItem->order->user_id !== Auth::id(), 403);
        abort_if($orderItem->order->status !== 'completed', 403);
        abort_if($orderItem->review, 403, 'Produk ini sudah direview.');

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id'       => Auth::id(),
            'product_id'    => $orderItem->product_id,
            'order_item_id' => $orderItem->id,
            'rating'        => $request->rating,
            'comment'       => $request->comment,
        ]);

        return redirect()->route('orders.show', $orderItem->order_id)
                         ->with('success', 'Terima kasih atas review-nya!');
    }
}