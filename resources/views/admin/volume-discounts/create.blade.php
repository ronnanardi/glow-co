@extends('layouts.admin')

@section('title', 'Tambah Diskon Volume')
@section('page-title', 'Tambah Diskon Volume')

@section('content')

    <div class="card-panel" style="max-width:600px">
        <div class="card-header-custom">
            <h6>Form Diskon Volume</h6>
            <a href="{{ route('admin.volume-discounts.index') }}" class="btn btn-sm btn-outline-secondary">
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

            <form method="POST" action="{{ route('admin.volume-discounts.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Produk</label>
                    <select name="product_id" class="form-select @error('product_id') is-invalid @enderror">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Minimal Quantity</label>
                    <input type="number" name="min_quantity" class="form-control @error('min_quantity') is-invalid @enderror"
                           value="{{ old('min_quantity') }}" min="1" placeholder="contoh: 3">
                    <div class="form-text">Diskon aktif kalau customer beli sejumlah ini atau lebih</div>
                    @error('min_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tipe Diskon</label>
                        <select name="value_type" class="form-select">
                            <option value="percentage" {{ old('value_type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed"      {{ old('value_type') == 'fixed'      ? 'selected' : '' }}>Nominal (Rp)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nilai Diskon</label>
                        <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                               value="{{ old('value') }}" min="0" placeholder="5 atau 10000">
                        @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Aktifkan diskon</label>
                </div>

                <button type="submit" class="btn btn-theme w-100">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>

            </form>
        </div>
    </div>

@endsection