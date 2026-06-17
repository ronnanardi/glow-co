@extends('layouts.admin')

@section('title', 'Review')
@section('page-title', 'Review')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Daftar Review</h6>
        </div>
        <div class="card-body-custom">

            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama produk..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="rating" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Rating</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-theme w-100">Cari</button>
                </div>
            </form>

            <table class="table-clean">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="fw-semibold">{{ $review->product->name ?? '(Produk dihapus)' }}</td>
                            <td>{{ $review->user->name }}</td>
                            <td style="color:#C9A87C">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </td>
                            <td style="max-width:250px;font-size:0.85rem" class="text-muted">
                                {{ $review->comment ?? '-' }}
                            </td>
                            <td style="font-size:0.82rem">{{ $review->created_at->format('d M Y') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                                      onsubmit="return confirm('Hapus review ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada review.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">{{ $reviews->links() }}</div>
        </div>
    </div>

@endsection