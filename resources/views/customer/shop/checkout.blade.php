@extends('layouts.app')

@section('title', 'Checkout')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    <h4 class="fw-bold mb-4">Checkout</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="row g-4">

            {{-- Kiri: Alamat & Pembayaran --}}
            <div class="col-lg-8">

                {{-- Alamat --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Pilih Alamat Pengiriman</h6>

                        @forelse($addresses as $address)
                            <div class="form-check mb-3 p-3 rounded-3 border {{ $address->is_primary ? 'border-warning' : '' }}">
                                <input class="form-check-input" type="radio"
                                       name="address_id" value="{{ $address->id }}"
                                       {{ $address->is_primary ? 'checked' : '' }}>
                                <label class="form-check-label ms-2">
                                    <span class="fw-semibold">{{ $address->label }}</span>
                                    @if($address->is_primary)
                                        <span class="badge ms-1" style="background:#9A7B67;font-size:0.7rem">Utama</span>
                                    @endif
                                    <div class="text-muted" style="font-size:0.85rem">
                                        {{ $address->recipient_name }} · {{ $address->phone }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.85rem">
                                        {{ $address->address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                    </div>
                                </label>
                            </div>
                        @empty
                            <div class="alert alert-warning">
                                Belum ada alamat. <a href="#">Tambah alamat</a> terlebih dahulu.
                            </div>
                        @endforelse

                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Metode Pembayaran</h6>
                        <div class="row g-3">
                            @foreach(['Transfer Bank', 'GoPay', 'OVO', 'DANA', 'COD'] as $method)
                                <div class="col-6 col-md-4">
                                    <div class="form-check p-3 border rounded-3">
                                        <input class="form-check-input" type="radio"
                                               name="payment_method" value="{{ $method }}"
                                               {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2 fw-semibold" style="font-size:0.88rem">
                                            {{ $method }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- Kanan: Summary --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Ringkasan Order</h6>

                        @foreach($cart->items as $item)
                            <div class="d-flex justify-content-between mb-2" style="font-size:0.88rem">
                                <span class="text-muted">{{ $item->product->name }} x{{ $item->quantity }}</span>
                                <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach

                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold" style="color:#9A7B67;font-size:1.1rem">
                                Rp {{ number_format($cart->total, 0, ',', '.') }}
                            </span>
                        </div>

                        <button type="submit" class="btn btn-theme w-100">
                            <i class="bi bi-bag-check me-1"></i> Buat Pesanan
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection