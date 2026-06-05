@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Kategori')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Daftar Kategori</h6>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-theme">
                <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
            </a>
        </div>
        <div class="card-body-custom">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Jumlah Produk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($category->image)
                                    <img src="{{ $category->image }}"
                                         style="width:50px;height:50px;object-fit:cover;border-radius:8px">
                                @else
                                    <div style="width:50px;height:50px;border-radius:8px;background:var(--cream);display:flex;align-items:center;justify-content:center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight:600">{{ $category->name }}</td>
                            <td><span class="text-muted" style="font-size:0.82rem">{{ $category->slug }}</span></td>
                            <td>{{ $category->products_count }} produk</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                          onsubmit="return confirm('Hapus kategori ini?')">
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
                            <td colspan="6" class="text-center text-muted py-4">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

@endsection