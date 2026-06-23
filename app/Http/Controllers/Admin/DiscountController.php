<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::latest()->paginate(10);
        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        $products   = Product::where('is_active', true)->get();
        $categories = Category::all();
        return view('admin.discounts.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:product,category',
            'target_id'   => 'required|integer',
            'value_type'  => 'required|in:percentage,fixed',
            'value'       => 'required|numeric|min:0',
            'max_discount'=> 'nullable|numeric|min:0',
            'starts_at'   => 'nullable|date',
            'expires_at'  => 'nullable|date|after_or_equal:starts_at',
            'stackable'   => 'boolean',
        ]);

        Discount::create([
            'name'         => $request->name,
            'type'         => $request->type,
            'target_id'    => $request->target_id,
            'value_type'   => $request->value_type,
            'value'        => $request->value,
            'max_discount' => $request->value_type === 'percentage' ? $request->max_discount : null,
            'starts_at'    => $request->starts_at,
            'expires_at'   => $request->expires_at,
            'is_active'    => $request->boolean('is_active', true),
            'stackable'    => $request->boolean('stackable', true),
        ]);

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil dibuat.');
    }

    public function edit(Discount $discount)
    {
        $products   = Product::where('is_active', true)->get();
        $categories = Category::all();
        return view('admin.discounts.edit', compact('discount', 'products', 'categories'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:product,category',
            'target_id'   => 'required|integer',
            'value_type'  => 'required|in:percentage,fixed',
            'value'       => 'required|numeric|min:0',
            'max_discount'=> 'nullable|numeric|min:0',
            'starts_at'   => 'nullable|date',
            'expires_at'  => 'nullable|date|after_or_equal:starts_at',
        ]);

        $discount->update([
            'name'         => $request->name,
            'type'         => $request->type,
            'target_id'    => $request->target_id,
            'value_type'   => $request->value_type,
            'value'        => $request->value,
            'max_discount' => $request->value_type === 'percentage' ? $request->max_discount : null,
            'starts_at'    => $request->starts_at,
            'expires_at'   => $request->expires_at,
            'is_active'    => $request->boolean('is_active'),
            'stackable'    => $request->boolean('stackable'),
        ]);

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil diupdate.');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil dihapus.');
    }
}