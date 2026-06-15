@extends('layouts.app')

@section('title', $product->name)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color:#9A7B67">Home</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none" style="color:#9A7B67">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">

        {{-- Gambar Produk --}}
        <div class="col-lg-5">
            <div class="position-relative">
                @if($product->badge)
                    <span class="product-badge badge-{{ $product->badge }}" style="top:12px;left:12px">
                        {{ ucfirst($product->badge) }}
                    </span>
                @endif
                <img src="{{ $product->image && !str_starts_with($product->image, 'http') ? Storage::url($product->image) : $product->image }}"
                     alt="{{ $product->name }}"
                     class="img-fluid rounded-4 w-100"
                     style="object-fit:cover;max-height:450px">
            </div>
        </div>

        {{-- Info Produk --}}
        <div class="col-lg-7">

            <span class="text-muted fw-semibold small text-uppercase">{{ $product->category->name }}</span>
            <h2 class="fw-bold mt-1 mb-2" style="font-family:'Playfair Display',serif">{{ $product->name }}</h2>

            <div class="mb-3">
                <span class="fw-bold" style="font-size:1.6rem;color:#9A7B67">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
            </div>

            <p class="text-muted mb-4" style="line-height:1.8">
                {{ $product->description ?? 'Produk skincare premium dengan bahan aktif terbaik. Cocok untuk semua jenis kulit. BPOM certified.' }}
            </p>

            {{-- Stok --}}
            <div class="mb-4 d-flex align-items-center gap-2">
                @if($product->stock > 0)
                    <span class="badge bg-success-subtle text-success">Tersedia</span>
                    <span class="text-muted" style="font-size:0.85rem">Stok: {{ $product->stock }}</span>
                @else
                    <span class="badge bg-danger-subtle text-danger">Stok Habis</span>
                @endif
            </div>

            {{-- Quantity + Tombol --}}
            @if($product->stock > 0)

                {{-- Quantity Selector --}}
                <div class="d-flex align-items-center gap-3 mb-4">
                    <label class="fw-semibold">Jumlah:</label>
                    <div class="d-flex align-items-center border rounded-3 overflow-hidden">
                        <button type="button" class="btn px-3 py-2 border-0" onclick="changeQty(-1)">−</button>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                               class="form-control border-0 text-center fw-bold"
                               style="width:60px;box-shadow:none">
                        <button type="button" class="btn px-3 py-2 border-0" onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <div class="d-flex gap-3 flex-wrap">

                    {{-- Tambah ke Keranjang --}}
                    @auth
                        <form method="POST" action="{{ route('cart.store') }}" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="cartQty" value="1">
                            <button type="submit" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-semibold">
                                <i class="bi bi-bag-plus me-2"></i> Keranjang
                            </button>
                        </form>

                        {{-- Buy Now --}}
                        <form method="POST" action="{{ route('cart.buy-now') }}" id="buyNowForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="buyQty" value="1">
                            <button type="submit" class="btn btn-theme px-4 py-2 rounded-3 fw-semibold">
                                <i class="bi bi-lightning-fill me-2"></i> Beli Sekarang
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-semibold">
                            <i class="bi bi-bag-plus me-2"></i> Keranjang
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-theme px-4 py-2 rounded-3 fw-semibold">
                            <i class="bi bi-lightning-fill me-2"></i> Beli Sekarang
                        </a>
                    @endauth

                </div>

            @else
                <button class="btn btn-secondary px-4 py-2 rounded-3" disabled>
                    Stok Habis
                </button>
            @endif

            {{-- Info tambahan --}}
            <div class="row g-3 mt-4 pt-4" style="border-top:1px solid #eee">
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2" style="font-size:0.85rem">
                        <i class="bi bi-shield-check" style="color:#9A7B67"></i>
                        <span>100% Original & BPOM</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2" style="font-size:0.85rem">
                        <i class="bi bi-truck" style="color:#9A7B67"></i>
                        <span>Free Ongkir</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2" style="font-size:0.85rem">
                        <i class="bi bi-arrow-counterclockwise" style="color:#9A7B67"></i>
                        <span>Garansi Uang Kembali</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2" style="font-size:0.85rem">
                        <i class="bi bi-leaf" style="color:#9A7B67"></i>
                        <span>Cruelty Free & Vegan</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Produk Terkait --}}
    @if($related->isNotEmpty())
        <div class="mt-5 pt-4" style="border-top:1px solid #eee">
            <h5 class="fw-bold mb-4">Produk Terkait</h5>
            <div class="row g-4">
                @foreach($related as $item)
                    <div class="col-6 col-lg-3">
                        <div class="product-card">
                            <div class="product-img-wrap">
                                <a href="{{ route('product.show', $item->slug) }}">
                                    <img src="{{ $item->image && !str_starts_with($item->image, 'http') ? Storage::url($item->image) : $item->image }}"
                                         alt="{{ $item->name }}">
                                </a>
                                @if($item->badge)
                                    <span class="product-badge badge-{{ $item->badge }}">{{ ucfirst($item->badge) }}</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <span class="product-brand">GLOW&CO</span>
                                <h3 class="product-name">{{ $item->name }}</h3>
                                <div class="product-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                <a href="{{ route('product.show', $item->slug) }}" class="btn-add-cart d-block text-center text-decoration-none">
                                    Lihat Produk
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@section('js')
<script>
    function changeQty(delta) {
        const input   = document.getElementById('quantity');
        const cartQty = document.getElementById('cartQty');
        const buyQty  = document.getElementById('buyQty');
        const max     = parseInt(input.max);

        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > max) val = max;

        input.value   = val;
        cartQty.value = val;
        buyQty.value  = val;
    }

    // Sync input manual
    document.getElementById('quantity').addEventListener('input', function() {
        const max = parseInt(this.max);
        let val   = parseInt(this.value);
        if (isNaN(val) || val < 1) val = 1;
        if (val > max) val = max;
        this.value = val;
        document.getElementById('cartQty').value = val;
        document.getElementById('buyQty').value  = val;
    });
</script>
@endsection