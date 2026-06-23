@extends('layouts.admin')

@section('title', 'Diskon Volume')
@section('page-title', 'Diskon Volume / Grosir')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Daftar Diskon Volume</h6>
            <a href="{{ route('admin.volume-discounts.create') }}" class="btn btn-sm btn-theme">
                <i class="bi bi-plus-lg me-1"></i> Tambah
            </a>
        </div>
        <div class="card-body-custom">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Min. Qty</th>
                        <th>Diskon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($volumeDiscounts as $vd)
                        <tr>
                            <td class="fw-semibold">{{ $vd->product->name }}</td>
                            <td>Beli ≥ {{ $vd->min_quantity }} pcs</td>
                            <td>
                                @if($vd->value_type === 'percentage')
                                    {{ $vd->value }}%
                                @else
                                    Rp {{ number_format($vd->value, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if($vd->is_active)
                                    <span class="status-badge status-success">Aktif</span>
                                @else
                                    <span class="status-badge status-cancelled">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.volume-discounts.edit', $vd) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.volume-discounts.destroy', $vd) }}"
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
                            <td colspan="5" class="text-center text-muted py-4">Belum ada diskon volume.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $volumeDiscounts->links() }}</div>
        </div>
    </div>

@endsection