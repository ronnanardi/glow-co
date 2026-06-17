<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
                         ->withCount('orders')
                         ->withSum('orders', 'total_price')
                         ->latest()
                         ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        abort_if($customer->role !== 'customer', 404);

        $customer->load('addresses');

        $orders = $customer->orders()->with('items')->latest()->paginate(10);

        $totalSpent = $customer->orders()
                                ->whereIn('status', ['paid', 'processed', 'shipped', 'completed'])
                                ->sum('total_price');

        return view('admin.customers.show', compact('customer', 'orders', 'totalSpent'));
    }
}