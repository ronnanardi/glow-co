@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    <h4 class="fw-bold mb-4">Keranjang Belanja</h4>

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

    @if($cart && $cart->items->isNotEmpty())
        <div class="row g-4">

            {{-- List Item --}}
            <div class="col-lg-8">
                @foreach($cart->items as $item)
                    <div class="card border-0 shadow-sm mb-3 rounded-3">
                        <div class="card-body">
                            <div class="d-flex gap-3 align-items-center">

                                {{-- Gambar --}}
                                <img src="{{ $item->product->image && !str_starts_with($item->product->image, 'http') ? Storage::url($item->product->image) : $item->product->image }}"
                                     style="width:80px;height:80px;object-fit:cover;border-radius:10px">

                                {{-- Info --}}
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $item->product->name }}</div>
                                    <div class="text-muted" style="font-size:0.85rem">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                {{-- Quantity --}}
                                <form method="POST" action="{{ route('cart.update', $item) }}" class="d-flex align-items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity"
                                           value="{{ $item->quantity }}" min="1"
                                           class="form-control text-center"
                                           style="width:70px"
                                           onchange="this.form.submit()">
                                </form>

                                {{-- Subtotal --}}
                                <div class="fw-bold" style="min-width:100px;text-align:right;color:#9A7B67">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>

                                {{-- Hapus --}}
                                <form method="POST" action="{{ route('cart.destroy', $item) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger border-0">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Ringkasan Belanja</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Item</span>
                            <span>{{ $cart->count }} item</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold" style="color:#9A7B67;font-size:1.1rem">
                                Rp {{ number_format($cart->total, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-theme w-100">
                            Lanjut ke Checkout <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>

        </div>

    @else
        <div class="text-center py-5">
            <i class="bi bi-bag-x" style="font-size:4rem;color:#ddd"></i>
            <h5 class="mt-3 text-muted">Keranjang masih kosong</h5>
            <a href="{{ route('home') }}" class="btn btn-theme mt-3">Mulai Belanja</a>
        </div>
    @endif

</div>
@endsection