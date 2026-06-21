@extends('layouts.admin')
@use('Illuminate\Support\Facades\Storage')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h5 class="mb-0 fw-bold">{{ $order->order_number }}</h5>
        @php
            $statusColor = match($order->status) {
                'pending'   => 'pending',
                'paid', 'processed', 'shipped', 'completed' => 'success',
                'cancelled' => 'cancelled',
                default     => 'pending',
            };
        @endphp
        <span class="status-badge status-{{ $statusColor }}">{{ ucfirst($order->status) }}</span>
    </div>

    <div class="row g-4">

        {{-- Kiri --}}
        <div class="col-lg-8">

            {{-- Produk --}}
            <div class="card-panel mb-4">
                <div class="card-header-custom">
                    <h6>Produk Dipesan</h6>
                </div>
                <div class="card-body-custom">
                    <table class="table-clean">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end gap-5 mt-3 pt-3" style="border-top:1px solid var(--border)">
                        <div class="text-end">
                            <div class="text-muted" style="font-size:0.85rem">Subtotal</div>
                            <div class="text-muted" style="font-size:0.85rem">Ongkir ({{ $order->courier }} - {{ $order->courier_service }})</div>
                            <div class="fw-bold mt-1">Total</div>
                        </div>
                        <div class="text-end">
                            <div style="font-size:0.85rem">Rp {{ number_format($order->total_price - $order->shipping_cost, 0, ',', '.') }}</div>
                            <div style="font-size:0.85rem">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</div>
                            <div class="fw-bold mt-1" style="color:var(--secondary)">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bukti Pembayaran --}}
            <div class="card-panel mb-4">
                <div class="card-header-custom">
                    <h6>Info Pembayaran</h6>
                </div>
                <div class="card-body-custom">
                    @if($order->status === 'pending')
                        <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
                            <i class="bi bi-hourglass-split"></i>
                            <span>Menunggu pembayaran dari customer via Midtrans.</span>
                        </div>
                    @else
                        <div class="row g-2" style="font-size:0.88rem">
                            <div class="col-5 text-muted">Metode</div>
                            <div class="col-7 fw-semibold">
                                {{ $order->payment_type ? strtoupper(str_replace('_', ' ', $order->payment_type)) : '-' }}
                            </div>
                            <div class="col-5 text-muted">Transaction ID</div>
                            <div class="col-7" style="font-size:0.8rem">{{ $order->midtrans_transaction_id ?? '-' }}</div>
                            <div class="col-5 text-muted">Dibayar pada</div>
                            <div class="col-7">{{ $order->paid_at?->format('d M Y, H:i') ?? '-' }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Update Status & Resi --}}
            @if(in_array($order->status, ['paid', 'processed', 'shipped']))
                <div class="card-panel">
                    <div class="card-header-custom">
                        <h6>Update Status & Pengiriman</h6>
                    </div>
                    <div class="card-body-custom">

                        {{-- Status: paid → processed atau cancelled --}}
                        @if($order->status === 'paid')
                            <p class="text-muted mb-3" style="font-size:0.88rem">
                                Pembayaran sudah dikonfirmasi. Silakan proses pesanan.
                            </p>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="processed">
                                    <button type="submit" class="btn btn-theme">
                                        <i class="bi bi-box-seam me-1"></i> Proses & Kemas
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}"
                                    onsubmit="return confirm('Batalkan pesanan ini?')">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-x-circle me-1"></i> Batalkan
                                    </button>
                                </form>
                            </div>
                        @endif

                        {{-- Status: processed → input resi (otomatis shipped) atau cancelled --}}
                        @if($order->status === 'processed')
                            <p class="text-muted mb-3" style="font-size:0.88rem">
                                Pesanan sedang dikemas. Input nomor resi untuk mengubah status jadi "Dikirim".
                            </p>
                            <form method="POST" action="{{ route('admin.orders.add-shipment', $order) }}" class="mb-3">
                                @csrf
                                <label class="form-label fw-semibold">Nomor Resi</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="tracking_number" class="form-control"
                                            placeholder="Masukkan nomor resi" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" name="estimated_arrival" class="form-control">
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-theme">
                                            <i class="bi bi-truck me-1"></i> Simpan Resi & Kirim
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}"
                                onsubmit="return confirm('Batalkan pesanan ini?')">
                                @csrf
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-x-circle me-1"></i> Batalkan Pesanan
                                </button>
                            </form>
                        @endif

                        {{-- Status: shipped → hanya update resi, tidak bisa diubah admin --}}
                        @if($order->status === 'shipped')
                            <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-truck"></i>
                                <span style="font-size:0.88rem">Pesanan sedang dikirim. Menunggu konfirmasi penerimaan dari customer.</span>
                            </div>
                            <form method="POST" action="{{ route('admin.orders.add-shipment', $order) }}">
                                @csrf
                                <label class="form-label fw-semibold">Update Nomor Resi</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="tracking_number" class="form-control"
                                            value="{{ $order->shipment?->tracking_number }}"
                                            placeholder="Nomor resi" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" name="estimated_arrival" class="form-control"
                                            value="{{ $order->shipment?->estimated_arrival?->format('Y-m-d') }}">
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil me-1"></i> Update Resi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
            @endif

        </div>

        {{-- Kanan --}}
        <div class="col-lg-4">

            {{-- Info Customer --}}
            <div class="card-panel mb-4">
                <div class="card-header-custom">
                    <h6>Info Customer</h6>
                </div>
                <div class="card-body-custom">
                    <div style="font-size:0.88rem">
                        <div class="fw-semibold">{{ $order->user->name }}</div>
                        <div class="text-muted">{{ $order->user->email }}</div>
                        <div class="text-muted">{{ $order->user->phone }}</div>
                    </div>
                </div>
            </div>

            {{-- Alamat --}}
            @if($order->address)
                <div class="card-panel mb-4">
                    <div class="card-header-custom">
                        <h6>Alamat Pengiriman</h6>
                    </div>
                    <div class="card-body-custom">
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

            {{-- Info Pesanan --}}
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Info Pesanan</h6>
                </div>
                @if($order->status === 'completed')
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <strong>Pesanan selesai</strong> — customer telah konfirmasi diterima
                            @if($order->completed_at)
                                pada {{ $order->completed_at->format('d M Y, H:i') }}
                            @endif
                        </div>
                    </div>
                @elseif($order->status === 'shipped')
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-truck"></i>
                        <div>Pesanan sedang dikirim, menunggu konfirmasi penerimaan dari customer.</div>
                    </div>
                @endif
                <div class="card-body-custom">
                    <div class="row g-2" style="font-size:0.88rem">
                        <div class="col-5 text-muted">No. Order</div>
                        <div class="col-7 fw-semibold">{{ $order->order_number }}</div>
                        <div class="col-5 text-muted">Tanggal</div>
                        <div class="col-7">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        <div class="col-5 text-muted">Pembayaran</div>
                        <div class="col-7">{{ $order->payment_method }}</div>
                        <div class="col-5 text-muted">Kurir</div>
                        <div class="col-7">{{ strtoupper($order->courier) }} - {{ $order->courier_service }}</div>
                        @if($order->shipment?->tracking_number)
                            <div class="col-5 text-muted">No. Resi</div>
                            <div class="col-7 fw-semibold">{{ $order->shipment->tracking_number }}</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection