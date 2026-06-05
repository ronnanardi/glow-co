@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Detail Pesanan {{ $order->order_number }}</h4>
    </div>

    <div class="row g-4">

        {{-- Items --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Produk Dipesan</h6>
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <div class="fw-semibold">{{ $item->product_name }}</div>
                                <div class="text-muted" style="font-size:0.85rem">
                                    Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}
                                </div>
                            </div>
                            <div class="fw-bold" style="color:#9A7B67">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span style="color:#9A7B67">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Pengiriman --}}
            @if($order->shipment)
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Info Pengiriman</h6>
                        <div class="row g-2" style="font-size:0.88rem">
                            <div class="col-5 text-muted">Kurir</div>
                            <div class="col-7">{{ $order->shipment->courier }} - {{ $order->shipment->service }}</div>
                            <div class="col-5 text-muted">No. Resi</div>
                            <div class="col-7 fw-semibold">{{ $order->shipment->tracking_number ?? '-' }}</div>
                            <div class="col-5 text-muted">Status</div>
                            <div class="col-7">{{ ucfirst($order->shipment->status) }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Info Order --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Info Pesanan</h6>
                    <div class="row g-2" style="font-size:0.88rem">
                        <div class="col-5 text-muted">Status</div>
                        <div class="col-7">
                            <span class="status-badge status-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'cancelled' : 'pending') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="col-5 text-muted">Pembayaran</div>
                        <div class="col-7">{{ $order->payment_method }}</div>
                        <div class="col-5 text-muted">Tanggal</div>
                        <div class="col-7">{{ $order->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            @if($order->address)
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Alamat Pengiriman</h6>
                        <div style="font-size:0.88rem">
                            <div class="fw-semibold">{{ $order->address->recipient_name }}</div>
                            <div class="text-muted">{{ $order->address->phone }}</div>
                            <div class="text-muted mt-1">
                                {{ $order->address->address }}, {{ $order->address->city }},
                                {{ $order->address->province }} {{ $order->address->postal_code }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection