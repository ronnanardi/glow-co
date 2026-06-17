<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter status stok
        if ($request->filled('filter')) {
            match ($request->filter) {
                'low'   => $query->where('stock', '>', 0)->where('stock', '<=', 10),
                'empty' => $query->where('stock', 0),
                default => null,
            };
        }

        // Search nama produk
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('stock', 'asc')->paginate(15)->withQueryString();

        // Ringkasan
        $totalProducts = Product::count();
        $lowStockCount = Product::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $emptyStockCount = Product::where('stock', 0)->count();
        $totalStockValue = Product::sum(DB::raw('price * stock'));

        return view('admin.stock-report.index', compact(
            'products', 'totalProducts', 'lowStockCount', 'emptyStockCount', 'totalStockValue'
        ));
    }

    // Update stok cepat dari halaman laporan
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => $request->stock]);

        return back()->with('success', "Stok {$product->name} berhasil diupdate.");
    }
}