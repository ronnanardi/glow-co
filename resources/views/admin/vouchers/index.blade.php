@extends('layouts.admin')

@section('title', 'Promo & Voucher')
@section('page-title', 'Promo & Voucher')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Daftar Voucher</h6>
            <a href="{{ route('admin.vouchers.create') }}" class="btn btn-sm btn-theme">
                <i class="bi bi-plus-lg me-1"></i> Buat Voucher
            </a>
        </div>
        <div class="card-body-custom">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tipe Diskon</th>
                        <th>Min. Belanja</th>
                        <th>Pemakaian</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $voucher)
                        <tr>
                            <td class="fw-bold" style="color:var(--secondary)">{{ $voucher->code }}</td>
                            <td>
                                @if($voucher->type === 'percentage')
                                    {{ $voucher->value }}%
                                    @if($voucher->max_discount)
                                        <span class="text-muted" style="font-size:0.78rem">(maks Rp {{ number_format($voucher->max_discount, 0, ',', '.') }})</span>
                                    @endif
                                @else
                                    Rp {{ number_format($voucher->value, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</td>
                            <td>
                                {{ $voucher->used_count }} / {{ $voucher->usage_limit ?? '∞' }}
                            </td>
                            <td style="font-size:0.82rem">
                                @if($voucher->starts_at || $voucher->expires_at)
                                    {{ $voucher->starts_at?->format('d M Y') ?? '-' }} s/d
                                    {{ $voucher->expires_at?->format('d M Y') ?? '-' }}
                                @else
                                    <span class="text-muted">Tanpa batas</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $isExpired = $voucher->expires_at && now()->gt($voucher->expires_at);
                                    $isLimitReached = $voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit;
                                @endphp

                                @if(!$voucher->is_active)
                                    <span class="status-badge status-cancelled">Nonaktif</span>
                                @elseif($isExpired)
                                    <span class="status-badge status-cancelled">Expired</span>
                                @elseif($isLimitReached)
                                    <span class="status-badge status-pending">Habis</span>
                                @else
                                    <span class="status-badge status-success">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}"
                                          onsubmit="return confirm('Hapus voucher ini?')">
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
                            <td colspan="7" class="text-center text-muted py-4">Belum ada voucher.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">{{ $vouchers->links() }}</div>
        </div>
    </div>

@endsection