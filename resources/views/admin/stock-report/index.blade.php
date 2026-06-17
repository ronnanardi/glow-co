@extends('layouts.admin')
@use('Illuminate\Support\Facades\Storage')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(139,111,94,0.1);color:var(--secondary)">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Total Produk</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(212,160,160,0.15);color:var(--rose)">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="stat-value">{{ $lowStockCount }}</div>
                <div class="stat-label">Stok Menipis (≤10)</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(220,53,69,0.1);color:#dc3545">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-value">{{ $emptyStockCount }}</div>
                <div class="stat-label">Stok Habis</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(168,181,160,0.15);color:var(--sage)">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-value" style="font-size:1.1rem">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</div>
                <div class="stat-label">Nilai Total Stok</div>
            </div>
        </div>
    </div>

    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Daftar Stok Produk</h6>
        </div>
        <div class="card-body-custom">

            {{-- Filter --}}
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama produk..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="filter" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Stok</option>
                        <option value="low" {{ request('filter') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="empty" {{ request('filter') == 'empty' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-theme w-100">Cari</button>
                </div>
                @if(request('search') || request('filter'))
                    <div class="col-md-2">
                        <a href="{{ route('admin.stock-report.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                @endif
            </form>

            <table class="table-clean">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok Saat Ini</th>
                        <th>Status</th>
                        <th>Update Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image) }}"
                                         style="width:45px;height:45px;object-fit:cover;border-radius:8px">
                                @else
                                    <div style="width:45px;height:45px;border-radius:8px;background:var(--cream);display:flex;align-items:center;justify-content:center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="fw-bold {{ $product->stock == 0 ? 'text-danger' : ($product->stock <= 10 ? 'text-warning' : '') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                @if($product->stock == 0)
                                    <span class="status-badge status-cancelled">Habis</span>
                                @elseif($product->stock <= 10)
                                    <span class="status-badge status-pending">Menipis</span>
                                @else
                                    <span class="status-badge status-success">Aman</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.stock-report.update', $product) }}" class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="stock" value="{{ $product->stock }}" min="0"
                                           class="form-control form-control-sm" style="width:80px">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada produk ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">{{ $products->links() }}</div>
        </div>
    </div>

@endsection