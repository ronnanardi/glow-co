@extends('layouts.admin')

@section('title', 'Pesanan')
@section('page-title', 'Pesanan')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Daftar Pesanan</h6>
        </div>
        <div class="card-body-custom">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->order_number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                @if($order->payment_proof)
                                    <span class="status-badge status-success">Sudah Upload</span>
                                @else
                                    <span class="status-badge status-cancelled">Belum Upload</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColor = match($order->status) {
                                        'pending'   => 'pending',
                                        'paid'      => 'success',
                                        'processed' => 'success',
                                        'shipped'   => 'success',
                                        'completed' => 'success',
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
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

@endsection