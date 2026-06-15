<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $totalOrders    = Order::count();
        $totalRevenue   = Order::whereIn('status', ['paid', 'processed', 'shipped', 'completed'])->sum('total_price');
        $totalProducts  = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $pendingCount   = Order::where('status', 'pending')->count();

        $recentOrders = Order::with(['user', 'items'])->latest()->take(5)->get();
        $topProducts = OrderItem::select('product_id', 'product_name')
            ->selectRaw('SUM(quantity) as total_sold')
            ->selectRaw('SUM(subtotal) as total_revenue')
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['paid', 'processed', 'shipped', 'completed']);
            })
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(4)
            ->get()
            ->map(function ($item) {
                // ambil gambar produk asli (kalau masih ada)
                $product = Product::find($item->product_id);
                $item->image = $product?->image;
                $item->price = $product?->price ?? ($item->total_revenue / $item->total_sold);
                return $item;
            });

        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'totalProducts', 'totalCustomers',
            'pendingCount', 'recentOrders', 'topProducts'
        ));
    }
}
