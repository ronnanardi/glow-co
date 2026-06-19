@extends('layouts.admin')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h5 class="mb-0 fw-bold">{{ $customer->name }}</h5>
    </div>

    <div class="row g-4">

        {{-- Kiri: Riwayat Order --}}
        <div class="col-lg-8">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Riwayat Pesanan</h6>
                </div>
                <div class="card-body-custom">
                    <table class="table-clean">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="fw-semibold">{{ $order->order_number }}</td>
                                    <td style="font-size:0.85rem">{{ $order->created_at->format('d M Y, H:i') }}</td>
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
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">{{ $orders->links() }}</div>
                </div>
            </div>
        </div>

        {{-- Kanan: Info Customer --}}
        <div class="col-lg-4">

            <div class="card-panel mb-4">
                <div class="card-header-custom">
                    <h6>Info Pelanggan</h6>
                </div>
                <div class="card-body-custom">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="flex-shrink:0">
                            @if($customer->avatar)
                                <img src="{{ Storage::url($customer->avatar) }}"
                                    style="width:50px;height:50px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
                            @else
                                <div class="user-avatar" style="width:50px;height:50px;font-size:1.2rem">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="fw-bold">{{ $customer->name }}</div>
                            <div class="text-muted" style="font-size:0.82rem">
                                Bergabung {{ $customer->created_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="row g-2" style="font-size:0.88rem">
                        <div class="col-4 text-muted">Email</div>
                        <div class="col-8">{{ $customer->email }}</div>
                        <div class="col-4 text-muted">Telepon</div>
                        <div class="col-8">{{ $customer->phone ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="card-panel mb-4">
                <div class="card-header-custom">
                    <h6>Statistik Belanja</h6>
                </div>
                <div class="card-body-custom">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted" style="font-size:0.88rem">Total Order</span>
                        <span class="fw-bold">{{ $orders->total() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted" style="font-size:0.88rem">Total Belanja</span>
                        <span class="fw-bold" style="color:var(--secondary)">
                            Rp {{ number_format($totalSpent, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            @if($customer->addresses->isNotEmpty())
                <div class="card-panel">
                    <div class="card-header-custom">
                        <h6>Alamat Tersimpan</h6>
                    </div>
                    <div class="card-body-custom">
                        @foreach($customer->addresses as $address)
                            <div class="mb-3 pb-3" style="border-bottom:1px solid var(--border)">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-semibold" style="font-size:0.88rem">{{ $address->label }}</span>
                                    @if($address->is_primary)
                                        <span class="badge" style="background:var(--secondary);font-size:0.68rem">Utama</span>
                                    @endif
                                </div>
                                <div style="font-size:0.82rem;color:#999">
                                    {{ $address->recipient_name }} · {{ $address->phone }}<br>
                                    {{ $address->address }}, {{ $address->city }}, {{ $address->province }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>

@endsection