<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'         => 'required|string|max:50|unique:vouchers,code',
            'type'         => 'required|in:percentage,fixed',
            'value'        => 'required|numeric|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit'  => 'nullable|integer|min:1',
            'starts_at'    => 'nullable|date',
            'expires_at'   => 'nullable|date|after_or_equal:starts_at',
        ]);

        Voucher::create([
            'code'         => strtoupper($request->code),
            'type'         => $request->type,
            'value'        => $request->value,
            'min_purchase' => $request->min_purchase ?? 0,
            'max_discount' => $request->type === 'percentage' ? $request->max_discount : null,
            'usage_limit'  => $request->usage_limit,
            'is_active'    => $request->boolean('is_active', true),
            'starts_at'    => $request->starts_at,
            'expires_at'   => $request->expires_at,
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil dibuat.');
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code'         => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'type'         => 'required|in:percentage,fixed',
            'value'        => 'required|numeric|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit'  => 'nullable|integer|min:1',
            'starts_at'    => 'nullable|date',
            'expires_at'   => 'nullable|date|after_or_equal:starts_at',
        ]);

        $voucher->update([
            'code'         => strtoupper($request->code),
            'type'         => $request->type,
            'value'        => $request->value,
            'min_purchase' => $request->min_purchase ?? 0,
            'max_discount' => $request->type === 'percentage' ? $request->max_discount : null,
            'usage_limit'  => $request->usage_limit,
            'is_active'    => $request->boolean('is_active'),
            'starts_at'    => $request->starts_at,
            'expires_at'   => $request->expires_at,
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil diupdate.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }
}