<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TierDiscount;
use App\Models\User;
use Illuminate\Http\Request;

class TierDiscountController extends Controller
{
    public function index()
    {
        $tierDiscounts = TierDiscount::all()->keyBy('tier');
        $tierStats = [
            'regular' => User::where('tier', 'regular')->where('role', 'customer')->count(),
            'silver'  => User::where('tier', 'silver')->where('role', 'customer')->count(),
            'gold'    => User::where('tier', 'gold')->where('role', 'customer')->count(),
        ];
        return view('admin.tier-discounts.index', compact('tierDiscounts', 'tierStats'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'silver_value' => 'required|numeric|min:0|max:100',
            'gold_value'   => 'required|numeric|min:0|max:100',
        ]);

        TierDiscount::updateOrCreate(['tier' => 'silver'], ['value' => $request->silver_value]);
        TierDiscount::updateOrCreate(['tier' => 'gold'],   ['value' => $request->gold_value]);

        return back()->with('success', 'Diskon member tier berhasil diupdate.');
    }

    // Update tier customer manual dari admin
    public function updateCustomerTier(Request $request, User $customer)
    {
        abort_if($customer->role !== 'customer', 404);

        $request->validate([
            'tier' => 'required|in:regular,silver,gold',
        ]);

        $customer->update(['tier' => $request->tier]);

        return back()->with('success', "Tier {$customer->name} berhasil diubah ke " . ucfirst($request->tier));
    }
}