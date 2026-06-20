@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')

@section('title', 'Wishlist Saya')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    <h4 class="fw-bold mb-4">Wishlist Saya</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($wishlists->isNotEmpty())
        <div class="row g-4">
            @foreach($wishlists as $wishlist)
                @php $product = $wishlist->product; @endphp
                @if($product)
                    <div class="col-6 col-lg-3">
                        <div class="product-card">
                            <div class="product-img-wrap">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->image && !str_starts_with($product->image, 'http') ? Storage::url($product->image) : $product->image }}"
                                         alt="{{ $product->name }}">
                                </a>
                                <form method="POST" action="{{ route('wishlist.destroy', $wishlist) }}" class="position-absolute" style="top:12px;right:12px">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-action btn-wishlist active" type="submit" title="Hapus dari wishlist">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="product-info">
                                <span class="product-brand">GLOW&CO</span>
                                <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                    <h3 class="product-name">{{ $product->name }}</h3>
                                </a>
                                <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

                                @if($product->stock > 0)
                                    <form method="POST" action="{{ route('cart.store') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-add-cart">Tambah ke Keranjang</button>
                                    </form>
                                @else
                                    <button class="btn-add-cart" disabled style="opacity:0.5">Stok Habis</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mt-4">{{ $wishlists->links() }}</div>

    @else
        <div class="text-center py-5">
            <i class="bi bi-heart" style="font-size:4rem;color:#ddd"></i>
            <h5 class="mt-3 text-muted">Wishlist masih kosong</h5>
            <a href="{{ route('home') }}" class="btn btn-theme mt-3">Mulai Belanja</a>
        </div>
    @endif

</div>
@endsection