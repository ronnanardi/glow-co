@extends('layouts.admin')
@use('Illuminate\Support\Facades\Storage')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')

    <div class="card-panel" style="max-width:700px">
        <div class="card-header-custom">
            <h6>Form Tambah Produk</h6>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
        <div class="card-body-custom">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Produk</label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Harga Jual (Rp)</label>
                        <input type="number" name="price" class="form-control"
                            value="{{ old('price', $product->price ?? '') }}" min="0">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Harga Asli (Rp) <span class="text-muted fw-normal">opsional, untuk diskon</span></label>
                        <input type="number" name="original_price" class="form-control"
                            value="{{ old('original_price', $product->original_price ?? '') }}" min="0"
                            placeholder="Kosongkan jika tidak diskon">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Stok</label>
                        <input type="number" name="stock"
                               class="form-control @error('stock') is-invalid @enderror"
                               value="{{ old('stock', 0) }}" min="0">
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Badge</label>
                        <select name="badge" class="form-select">
                            <option value="">-- Tanpa Badge --</option>
                            <option value="best" {{ old('badge') == 'best' ? 'selected' : '' }}>Best Seller</option>
                            <option value="new"  {{ old('badge') == 'new'  ? 'selected' : '' }}>New</option>
                            <option value="sale" {{ old('badge') == 'sale' ? 'selected' : '' }}>Sale</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Gambar Produk</label>
                    <input type="file" name="image" id="imageInput"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/*">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-2">
                        <img id="imagePreview" src="#" alt="Preview"
                             style="display:none;width:120px;height:120px;object-fit:cover;border-radius:10px">
                    </div>
                </div>

                <button type="submit" class="btn btn-theme w-100">
                    <i class="bi bi-check-lg me-1"></i> Simpan Produk
                </button>

            </form>
        </div>
    </div>

@endsection

@section('js')
<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });
</script>
@endsection