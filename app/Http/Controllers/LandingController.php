<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;


class LandingController extends Controller
{
    //
    public function index()
    {
        // view ini tampil untuk semua (guest & customer)
        $categories   = Category::withCount('products')->get();
        $products     = Product::with('category')->where('is_active', true)->get();

        $testimonials = Review::with('user', 'product')
            ->where('rating', '>=', 4)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->latest()
            ->take(6)
            ->get();

        return view('customer.landing', compact('categories', 'products', 'testimonials'));
    }

}
