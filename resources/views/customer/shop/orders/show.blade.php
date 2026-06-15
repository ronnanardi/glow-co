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

                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Informasi Pembayaran</h6>

                        <div class="alert alert-warning d-flex align-items-start gap-2 mb-3">
                            <i class="bi bi-exclamation-circle mt-1"></i>
                            <div style="font-size:0.88rem">
                                Silakan transfer sesuai total pesanan ke salah satu rekening di bawah,
                                lalu upload bukti transfer.
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            @foreach(config('payment.bank_accounts') as $bank)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-muted" style="font-size:0.78rem">Bank {{ $bank['bank'] }}</div>
                                            <div class="fw-bold" style="font-size:1.05rem">{{ $bank['number'] }}</div>
                                            <div style="font-size:0.85rem">a.n. {{ $bank['name'] }}</div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="copyRek('{{ $bank['number'] }}', this)">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background:var(--cream)">
                            <span class="fw-semibold">Total yang harus dibayar</span>
                            <span class="fw-bold" style="color:#9A7B67;font-size:1.1rem">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Upload Bukti Pembayaran</h6>

                        @if($order->payment_proof)
                            <div class="mb-3">
                                <img src="{{ Storage::url($order->payment_proof) }}"
                                    class="img-fluid rounded-3" style="max-width:300px">
                                <div class="text-success mt-2">
                                    <i class="bi bi-check-circle"></i> Bukti sudah diupload, menunggu konfirmasi admin.
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('orders.payment.upload', $order) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    {{ $order->payment_proof ? 'Ganti Bukti' : 'Pilih Foto Bukti Transfer' }}
                                </label>
                                <input type="file" name="payment_proof"
                                    class="form-control @error('payment_proof') is-invalid @enderror"
                                    accept="image/*">
                                @error('payment_proof')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-theme">
                                <i class="bi bi-upload me-1"></i> Upload Bukti
                            </button>
                        </form>
                    </div>
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
                        <div class="col-7">{{ $order->payment_method }}</div>
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
<script>
    function copyRek(number, btn) {
        navigator.clipboard.writeText(number).then(() => {
            const icon = btn.querySelector('i');
            icon.className = 'bi bi-check-lg text-success';
            setTimeout(() => {
                icon.className = 'bi bi-clipboard';
            }, 1500);
        });
    }
</script>
@endsection