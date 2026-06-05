<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;


class LandingController extends Controller
{
    //
    public function index()
    {
        // view ini tampil untuk semua (guest & customer)
        $categories = Category::withCount('products')->get();
        $products   = Product::with('category')->where('is_active', true)->get();

        return view('customer.landing', compact('categories', 'products'));
    }
}
