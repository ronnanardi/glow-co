<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255|unique:products,name',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'original_price'    => 'nullable|numeric|gt:price',
            'stock'             => 'required|integer|min:0',
            'badge'             => 'nullable|in:best,new,sale',
            'is_active'         => 'boolean',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'category_id'       => $request->category_id,
            'name'              => $request->name,
            'slug'              => Str::slug($request->name),
            'description'       => $request->description,
            'price'             => $request->price,
            'original_price'    => $request->original_price,
            'stock'             => $request->stock,
            'badge'             => $request->badge,
            'is_active'         => $request->boolean('is_active', true),
            'image'             => $imagePath,
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  Product $product)
    {
        //
        $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255|unique:products,name,' . $product->id,
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'original_price'    => 'nullable|numeric|gt:price',
            'stock'             => 'required|integer|min:0',
            'badge'             => 'nullable|in:best,new,sale',
            'is_active'         => 'boolean',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // hapus gambar lama
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id'       => $request->category_id,
            'name'              => $request->name,
            'slug'              => Str::slug($request->name),
            'description'       => $request->description,
            'price'             => $request->price,
            'original_price'    => $request->original_price,
            'stock'             => $request->stock,
            'badge'             => $request->badge,
            'is_active'         => $request->boolean('is_active', true),
            'image'             => $imagePath,
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus.');
    }
}
