@extends('layouts.admin')

@section('title', 'Pengiriman')
@section('page-title', 'Pengiriman')

@section('content')

    {{-- Ringkasan --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(212,160,160,0.15);color:var(--rose)">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div class="stat-value">{{ $needResiCount }}</div>
                <div class="stat-label">Menunggu Input Resi</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(168,181,160,0.15);color:var(--sage)">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="stat-value">{{ $onDeliveryCount }}</div>
                <div class="stat-label">Sedang Dikirim</div>
            </div>
        </div>
    </div>

    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Daftar Pengiriman</h6>
        </div>
        <div class="card-body-custom">

            {{-- Filter --}}
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari order / no. resi..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="belum_resi" {{ request('status') == 'belum_resi' ? 'selected' : '' }}>Belum Ada Resi</option>
                        <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="courier" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kurir</option>
                        <option value="jne" {{ request('courier') == 'jne' ? 'selected' : '' }}>JNE</option>
                        <option value="tiki" {{ request('courier') == 'tiki' ? 'selected' : '' }}>TIKI</option>
                        <option value="pos" {{ request('courier') == 'pos' ? 'selected' : '' }}>POS Indonesia</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-theme w-100">Cari</button>
                </div>
                @if(request()->anyFilled(['search', 'status', 'courier']))
                    <div class="col-md-2">
                        <a href="{{ route('admin.shipments.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                @endif
            </form>

            <table class="table-clean">
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Customer</th>
                        <th>Kurir</th>
                        <th>No. Resi</th>
                        <th>Estimasi Tiba</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->order_number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ strtoupper($order->courier) }} - {{ $order->courier_service }}</td>
                            <td>
                                @if($order->shipment?->tracking_number)
                                    <span class="fw-semibold">{{ $order->shipment->tracking_number }}</span>
                                @else
                                    <span class="text-muted">Belum diinput</span>
                                @endif
                            </td>
                            <td style="font-size:0.85rem">
                                {{ $order->shipment?->estimated_arrival?->format('d M Y') ?? '-' }}
                            </td>
                            <td>
                                @if($order->status === 'processed')
                                    <span class="status-badge status-pending">Belum Resi</span>
                                @else
                                    <span class="status-badge status-success">Dikirim</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i> {{ $order->status === 'processed' ? 'Input Resi' : 'Detail' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada pengiriman ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">{{ $shipments->links() }}</div>
        </div>
    </div>

@endsection