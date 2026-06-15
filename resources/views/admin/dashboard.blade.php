@extends('layouts.admin')
@use('Illuminate\Support\Facades\Storage')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Stats --}}
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(139,111,94,0.1);color:var(--secondary)">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalOrders, 0, ',', '.') }}</div>
                <div class="stat-label">Total Pesanan</div>
                @if($pendingCount > 0)
                    <div class="stat-change" style="color:#d4a3a3">
                        <i class="bi bi-exclamation-circle"></i> {{ $pendingCount }} menunggu konfirmasi
                    </div>
                @endif
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(168,181,160,0.15);color:var(--sage)">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="stat-label">Pendapatan</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(201,168,124,0.15);color:var(--accent)">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
                <div class="stat-label">Total Produk</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(212,160,160,0.15);color:var(--rose)">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalCustomers, 0, ',', '.') }}</div>
                <div class="stat-label">Pelanggan</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Recent Orders --}}
        <div class="col-lg-8">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Pesanan Terbaru</h6>
                    <a href="{{ route('admin.orders.index') }}" style="font-size:0.82rem;color:var(--secondary);text-decoration:none;font-weight:600">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body-custom">
                    <table class="table-clean">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Produk</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-semibold" style="color:var(--secondary)">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $order->items->first()?->product_name }}
                                        @if($order->items->count() > 1)
                                            <span class="text-muted" style="font-size:0.78rem">
                                                +{{ $order->items->count() - 1 }} lainnya
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusColor = match($order->status) {
                                                'pending'   => 'pending',
                                                'paid', 'processed', 'shipped', 'completed' => 'success',
                                                'cancelled' => 'cancelled',
                                                default     => 'pending',
                                            };
                                        @endphp
                                        <span class="status-badge status-{{ $statusColor }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="col-lg-4">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Produk Terlaris</h6>
                </div>
                <div class="card-body-custom">
                    @forelse($topProducts as $product)
                        <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--border)">
                            @if($product->image)
                                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image) }}"
                                    style="width:42px;height:42px;border-radius:10px;object-fit:cover">
                            @else
                                <div style="width:42px;height:42px;border-radius:10px;background:var(--cream);display:flex;align-items:center;justify-content:center">
                                    <i class="bi bi-droplet-fill" style="color:var(--secondary)"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div style="font-weight:600;font-size:0.88rem">{{ $product->product_name }}</div>
                                <div style="font-size:0.75rem;color:#999">{{ $product->total_sold }} terjual</div>
                            </div>
                            <div style="font-weight:700;font-size:0.88rem;color:var(--secondary)">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">Belum ada penjualan.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection