<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    //
    public function upload(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->status !== Order::STATUS_PENDING, 403);

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->update([
            'payment_proof' => $path,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
    }
}
