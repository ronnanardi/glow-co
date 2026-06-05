@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    <h4 class="fw-bold mb-4">Pesanan Saya</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse($orders as $order)
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <div class="fw-bold">{{ $order->order_number }}</div>
                        <div class="text-muted" style="font-size:0.82rem">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="status-badge status-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'cancelled' : 'pending') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <div class="fw-bold mt-1" style="color:#9A7B67">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted" style="font-size:0.85rem">
                        {{ $order->items->count() }} produk
                    </span>
                    <a href="{{ route('orders.show', $order) }}"
                       class="btn btn-sm btn-outline-secondary">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-bag-x" style="font-size:4rem;color:#ddd"></i>
            <h5 class="mt-3 text-muted">Belum ada pesanan</h5>
            <a href="{{ route('home') }}" class="btn btn-theme mt-3">Mulai Belanja</a>
        </div>
    @endforelse

    <div class="mt-3">{{ $orders->links() }}</div>

</div>
@endsection