@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')

@section('title', $category->name)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color:#9A7B67">Home</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="section-tag">Kategori</span>
            <h2 class="section-title mb-0">{{ $category->name }}</h2>
        </div>
        <span class="text-muted">{{ $products->total() }} produk</span>
    </div>

    @if($products->isNotEmpty())
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-6 col-lg-3">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ $product->image && !str_starts_with($product->image, 'http') ? Storage::url($product->image) : $product->image }}"
                                     alt="{{ $product->name }}">
                            </a>
                            @if($product->badge)
                                <span class="product-badge badge-{{ $product->badge }}">{{ ucfirst($product->badge) }}</span>
                            @endif
                        </div>
                        <div class="product-info">
                            <span class="product-brand">GLOW&CO</span>
                            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                <h3 class="product-name">{{ $product->name }}</h3>
                            </a>
                            <div class="product-rating mb-2">
                                @if($product->reviews_count > 0)
                                    @php $rating = round($product->avg_rating); @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $rating ? '-fill' : '' }}"></i>
                                    @endfor
                                    <span>({{ $product->reviews_count }})</span>
                                @endif
                            </div>
                            <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

                            @auth
                                <button class="btn-add-cart"
                                        data-product-id="{{ $product->id }}"
                                        onclick="addToCart({{ $product->id }}, this)">
                                    Tambah ke Keranjang
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn-add-cart d-block text-center text-decoration-none">
                                    Tambah ke Keranjang
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $products->links() }}</div>

    @else
        <div class="text-center py-5">
            <i class="bi bi-box-seam" style="font-size:4rem;color:#ddd"></i>
            <h5 class="mt-3 text-muted">Belum ada produk di kategori ini</h5>
            <a href="{{ route('home') }}" class="btn btn-theme mt-3">Kembali ke Beranda</a>
        </div>
    @endif

</div>
@endsection