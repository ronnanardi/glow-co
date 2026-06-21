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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Alert Status --}}
    @if($order->status === 'shipped')
        <div class="alert alert-info d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <i class="bi bi-truck me-2"></i>
                <span class="fw-semibold">Pesanan sedang dalam pengiriman.</span>
                <div style="font-size:0.85rem" class="text-muted mt-1">
                    Jika barang sudah kamu terima dalam kondisi baik, konfirmasi di bawah ini.
                </div>
            </div>
            <form method="POST" action="{{ route('orders.complete', $order) }}"
                onsubmit="return confirm('Pastikan barang sudah diterima dengan baik. Konfirmasi pesanan selesai?')">
                @csrf
                <button type="submit" class="btn btn-theme">
                    <i class="bi bi-check-circle me-1"></i> Pesanan Diterima
                </button>
            </form>
        </div>
    @endif

    @if($order->status === 'completed')
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill"></i>
            <span>Pesanan ini telah selesai. Terima kasih telah berbelanja di GLOW&CO!</span>
        </div>
    @endif

    @if($order->status === 'cancelled')
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-x-circle-fill"></i>
            <span>Pesanan ini telah dibatalkan.</span>
        </div>
    @endif

    {{-- Row Utama --}}
    <div class="row g-4">

        {{-- Kiri --}}
        <div class="col-lg-8">

            {{-- Produk --}}
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
                                 @if($order->status === 'completed')
                                    @if($item->review)
                                        <span class="text-success" style="font-size:0.8rem">
                                            <i class="bi bi-check-circle"></i> Sudah direview
                                        </span>
                                    @else
                                        <a href="{{ route('reviews.create', $item) }}" class="btn btn-sm btn-outline-secondary mt-1">
                                            <i class="bi bi-star me-1"></i> Beri Review
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <div class="fw-bold" style="color:#9A7B67">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between mb-2" style="font-size:0.88rem">
                        <span class="text-muted">Subtotal Produk</span>
                        <span>Rp {{ number_format($order->total_price - $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size:0.88rem">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span style="color:#9A7B67">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Info Pengiriman --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Info Pengiriman</h6>
                    <div class="row g-2" style="font-size:0.88rem">
                        <div class="col-5 text-muted">Kurir</div>
                        <div class="col-7">{{ strtoupper($order->courier) }} - {{ $order->courier_service }}</div>

                        <div class="col-5 text-muted">Ongkos Kirim</div>
                        <div class="col-7">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</div>

                        <div class="col-5 text-muted">No. Resi</div>
                        <div class="col-7 fw-semibold">
                            {{ $order->shipment?->tracking_number ?? '-' }}
                        </div>

                        <div class="col-5 text-muted">Estimasi Tiba</div>
                        <div class="col-7">
                            {{ $order->shipment?->estimated_arrival?->format('d M Y') ?? '-' }}
                        </div>

                        <div class="col-5 text-muted">Status Pengiriman</div>
                        <div class="col-7">
                            {{ $order->shipment ? ucfirst($order->shipment->status) : 'Menunggu pengiriman' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info & Upload Pembayaran (hanya saat pending) --}}
            @if($order->status === 'pending')
                <div class="card border-0 shadow-sm rounded-3 mt-4">
                    <div class="card-body p-4 text-center">
                        <h6 class="fw-bold mb-3">Selesaikan Pembayaran</h6>
                        <p class="text-muted mb-4" style="font-size:0.88rem">
                            Klik tombol di bawah untuk memilih metode pembayaran (Transfer Bank, GoPay, QRIS, Kartu Kredit, dll).
                        </p>

                        <button id="pay-button" class="btn btn-theme px-5">
                            <i class="bi bi-credit-card me-1"></i> Bayar Sekarang
                        </button>
                    </div>
                </div>
            @elseif($order->status === 'paid' || $order->status === 'processed' || $order->status === 'shipped' || $order->status === 'completed')
                <div class="alert alert-success d-flex align-items-center gap-2 mt-4">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>
                        Pembayaran berhasil
                        @if($order->payment_type)
                            via {{ strtoupper(str_replace('_', ' ', $order->payment_type)) }}
                        @endif
                        @if($order->paid_at)
                            pada {{ $order->paid_at->format('d M Y, H:i') }}
                        @endif
                    </div>
                </div>
            @elseif($order->status === 'cancelled')
                <div class="alert alert-danger d-flex align-items-center gap-2 mt-4">
                    <i class="bi bi-x-circle-fill"></i>
                    <span>Pembayaran dibatalkan atau kedaluwarsa.</span>
                </div>
            @endif

        </div>

        {{-- Kanan --}}
        <div class="col-lg-4">

            {{-- Info Pesanan --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Info Pesanan</h6>
                    <div class="row g-2" style="font-size:0.88rem">
                        <div class="col-5 text-muted">No. Order</div>
                        <div class="col-7 fw-semibold">{{ $order->order_number }}</div>
                        <div class="col-5 text-muted">Status</div>
                        <div class="col-7">
                            @php
                                $statusColor = match($order->status) {
                                    'completed'                        => 'success',
                                    'paid', 'processed', 'shipped'    => 'success',
                                    'cancelled'                        => 'cancelled',
                                    default                            => 'pending',
                                };
                            @endphp
                            <span class="status-badge status-{{ $statusColor }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="col-5 text-muted">Pembayaran</div>
                        <div class="col-7">
                            {{ $order->payment_type ? strtoupper(str_replace('_', ' ', $order->payment_type)) : 'Menunggu pembayaran' }}
                        </div>
                        <div class="col-5 text-muted">Tanggal</div>
                        <div class="col-7">{{ $order->created_at->format('d M Y') }}</div>
                        @if($order->paid_at)
                            <div class="col-5 text-muted">Dikonfirmasi</div>
                            <div class="col-7">{{ $order->paid_at->format('d M Y, H:i') }}</div>
                        @endif
                        @if($order->completed_at)
                            <div class="col-5 text-muted">Selesai</div>
                            <div class="col-7">{{ $order->completed_at->format('d M Y, H:i') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Alamat --}}
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

    </div>{{-- end row --}}

</div>
@endsection

@section('js')
{{-- <script>
    function copyRek(number, btn) {
        navigator.clipboard.writeText(number).then(() => {
            const icon = btn.querySelector('i');
            icon.className = 'bi bi-check-lg text-success';
            setTimeout(() => {
                icon.className = 'bi bi-clipboard';
            }, 1500);
        });
    }
</script> --}}

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function () {
            snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result) {
                    window.location.reload();
                },
                onPending: function(result) {
                    window.location.reload();
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function() {
                    // User menutup popup tanpa menyelesaikan pembayaran
                }
            });
        });
    }
</script>
@endsection