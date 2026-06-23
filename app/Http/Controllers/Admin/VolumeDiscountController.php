<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\VolumeDiscount;
use Illuminate\Http\Request;

class VolumeDiscountController extends Controller
{
    public function index()
    {
        $volumeDiscounts = VolumeDiscount::with('product')->latest()->paginate(10);
        return view('admin.volume-discounts.index', compact('volumeDiscounts'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.volume-discounts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'min_quantity' => 'required|integer|min:1',
            'value_type'   => 'required|in:percentage,fixed',
            'value'        => 'required|numeric|min:0',
        ]);

        VolumeDiscount::create([
            'product_id'   => $request->product_id,
            'min_quantity' => $request->min_quantity,
            'value_type'   => $request->value_type,
            'value'        => $request->value,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.volume-discounts.index')->with('success', 'Diskon volume berhasil dibuat.');
    }

    public function edit(VolumeDiscount $volumeDiscount)
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.volume-discounts.edit', compact('volumeDiscount', 'products'));
    }

    public function update(Request $request, VolumeDiscount $volumeDiscount)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'min_quantity' => 'required|integer|min:1',
            'value_type'   => 'required|in:percentage,fixed',
            'value'        => 'required|numeric|min:0',
        ]);

        $volumeDiscount->update([
            'product_id'   => $request->product_id,
            'min_quantity' => $request->min_quantity,
            'value_type'   => $request->value_type,
            'value'        => $request->value,
            'is_active'    => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.volume-discounts.index')->with('success', 'Diskon volume berhasil diupdate.');
    }

    public function destroy(VolumeDiscount $volumeDiscount)
    {
        $volumeDiscount->delete();
        return redirect()->route('admin.volume-discounts.index')->with('success', 'Diskon volume berhasil dihapus.');
    }
}