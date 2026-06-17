@extends('layouts.admin')

@section('title', 'Pelanggan')
@section('page-title', 'Pelanggan')

@section('content')

    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Daftar Pelanggan</h6>
        </div>
        <div class="card-body-custom">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Total Order</th>
                        <th>Total Belanja</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td>{{ $customer->orders_count }} order</td>
                            <td style="color:var(--secondary);font-weight:600">
                                Rp {{ number_format($customer->orders_sum_total_price ?? 0, 0, ',', '.') }}
                            </td>
                            <td style="font-size:0.82rem">{{ $customer->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">{{ $customers->links() }}</div>
        </div>
    </div>

@endsection