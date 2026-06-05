@extends('layouts.admin')
@use('Illuminate\Support\Facades\Storage')

@section('title', 'Produk')
@section('page-title', 'Produk')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Daftar Produk</h6>
            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-theme">
                <i class="bi bi-plus-lg me-1"></i> Tambah Produk
            </a>
        </div>
        <div class="card-body-custom">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Badge</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}"
                                         style="width:50px;height:50px;object-fit:cover;border-radius:8px">
                                @else
                                    <div style="width:50px;height:50px;border-radius:8px;background:var(--cream);display:flex;align-items:center;justify-content:center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight:600;max-width:200px">{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="{{ $product->stock <= 10 ? 'text-danger fw-bold' : '' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                @if($product->badge)
                                    <span class="product-badge badge-{{ $product->badge }}" style="position:static;font-size:0.72rem">
                                        {{ ucfirst($product->badge) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="status-badge status-success">Aktif</span>
                                @else
                                    <span class="status-badge status-cancelled">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                          onsubmit="return confirm('Hapus produk ini?')">
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
                            <td colspan="9" class="text-center text-muted py-4">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        // tambahkan use Facades Storage di blade
    </script>
@endsection