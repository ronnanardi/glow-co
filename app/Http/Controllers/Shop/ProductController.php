<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function show(string $slug)
        {
            $product = Product::with('category')
                            ->where('slug', $slug)
                            ->where('is_active', true)
                            ->firstOrFail();

            // Produk rekomendasi dari kategori yang sama
            $related = Product::where('category_id', $product->category_id)
                            ->where('id', '!=', $product->id)
                            ->where('is_active', true)
                            ->take(4)
                            ->get();

            return view('customer.shop.product-detail', compact('product', 'related'));
        }
}
