@extends('layouts.admin')

@section('title', 'Diskon Produk & Kategori')
@section('page-title', 'Diskon Produk & Kategori')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Daftar Diskon</h6>
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-sm btn-theme">
                <i class="bi bi-plus-lg me-1"></i> Buat Diskon
            </a>
        </div>
        <div class="card-body-custom">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Target</th>
                        <th>Diskon</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($discounts as $discount)
                        <tr>
                            <td class="fw-semibold">{{ $discount->name }}</td>
                            <td>
                                <span class="badge" style="background:{{ $discount->type === 'product' ? 'var(--secondary)' : 'var(--sage)' }}">
                                    {{ $discount->type === 'product' ? 'Produk' : 'Kategori' }}
                                </span>
                            </td>
                            <td style="font-size:0.85rem">ID: {{ $discount->target_id }}</td>
                            <td>
                                @if($discount->value_type === 'percentage')
                                    {{ $discount->value }}%
                                    @if($discount->max_discount)
                                        <span class="text-muted" style="font-size:0.78rem">(maks Rp {{ number_format($discount->max_discount, 0, ',', '.') }})</span>
                                    @endif
                                @else
                                    Rp {{ number_format($discount->value, 0, ',', '.') }}
                                @endif
                            </td>
                            <td style="font-size:0.82rem">
                                @if($discount->starts_at || $discount->expires_at)
                                    {{ $discount->starts_at?->format('d M Y') ?? '-' }} s/d
                                    {{ $discount->expires_at?->format('d M Y') ?? '-' }}
                                @else
                                    <span class="text-muted">Tanpa batas</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $isExpired = $discount->expires_at && now()->gt($discount->expires_at);
                                @endphp
                                @if(!$discount->is_active)
                                    <span class="status-badge status-cancelled">Nonaktif</span>
                                @elseif($isExpired)
                                    <span class="status-badge status-cancelled">Expired</span>
                                @else
                                    <span class="status-badge status-success">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.discounts.destroy', $discount) }}"
                                          onsubmit="return confirm('Hapus diskon ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada diskon.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $discounts->links() }}</div>
        </div>
    </div>

@endsection