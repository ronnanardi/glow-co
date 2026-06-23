@extends('layouts.admin')

@section('title', 'Edit Diskon')
@section('page-title', 'Edit Diskon')

@section('content')

    <div class="card-panel" style="max-width:650px">
        <div class="card-header-custom">
            <h6>Form Edit Diskon</h6>
            <a href="{{ route('admin.discounts.index') }}" class="btn btn-sm btn-outline-secondary">
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

            <form method="POST" action="{{ route('admin.discounts.update', $discount) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Diskon</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $discount->name) }}">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tipe Diskon</label>
                        <select name="type" id="discountType" class="form-select" onchange="toggleTarget()">
                            <option value="product"  {{ old('type', $discount->type) == 'product'  ? 'selected' : '' }}>Produk</option>
                            <option value="category" {{ old('type', $discount->type) == 'category' ? 'selected' : '' }}>Kategori</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Target</label>
                        <select name="target_id" id="targetProduct" class="form-select">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ old('target_id', $discount->type === 'product' ? $discount->target_id : '') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="target_id" id="targetCategory" class="form-select d-none">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('target_id', $discount->type === 'category' ? $discount->target_id : '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tipe Nilai</label>
                        <select name="value_type" id="valueType" class="form-select" onchange="toggleMaxDiscount()">
                            <option value="percentage" {{ old('value_type', $discount->value_type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed"      {{ old('value_type', $discount->value_type) == 'fixed'      ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nilai Diskon</label>
                        <input type="number" name="value" class="form-control"
                               value="{{ old('value', $discount->value) }}" min="0">
                    </div>
                </div>

                <div class="mb-3" id="maxDiscountWrap">
                    <label class="form-label fw-semibold">Maks. Diskon (Rp) <span class="text-muted fw-normal">khusus %</span></label>
                    <input type="number" name="max_discount" class="form-control"
                           value="{{ old('max_discount', $discount->max_discount) }}" min="0">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Mulai Berlaku</label>
                        <input type="date" name="starts_at" class="form-control"
                               value="{{ old('starts_at', $discount->starts_at?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Berakhir</label>
                        <input type="date" name="expires_at" class="form-control"
                               value="{{ old('expires_at', $discount->expires_at?->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive"
                                   {{ old('is_active', $discount->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Aktifkan diskon</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="stackable" value="1" class="form-check-input" id="stackable"
                                   {{ old('stackable', $discount->stackable) ? 'checked' : '' }}>
                            <label class="form-check-label" for="stackable">Bisa digabung diskon lain</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-theme w-100">
                    <i class="bi bi-check-lg me-1"></i> Update Diskon
                </button>

            </form>
        </div>
    </div>

@endsection

@section('js')
<script>
    function toggleTarget() {
        const type = document.getElementById('discountType').value;
        const prod = document.getElementById('targetProduct');
        const cat  = document.getElementById('targetCategory');

        prod.classList.toggle('d-none', type !== 'product');
        cat.classList.toggle('d-none', type !== 'category');
        prod.disabled = type !== 'product';
        cat.disabled  = type !== 'category';
    }

    function toggleMaxDiscount() {
        const type = document.getElementById('valueType').value;
        document.getElementById('maxDiscountWrap').style.display = type === 'percentage' ? 'block' : 'none';
    }

    toggleTarget();
    toggleMaxDiscount();
</script>
@endsection