@extends('layouts.admin')

@section('title', 'Buat Diskon')
@section('page-title', 'Buat Diskon')

@section('content')

    <div class="card-panel" style="max-width:650px">
        <div class="card-header-custom">
            <h6>Form Diskon Baru</h6>
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

            <form method="POST" action="{{ route('admin.discounts.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Diskon</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}" placeholder="contoh: Diskon Lebaran Serum">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tipe Diskon</label>
                        <select name="type" id="discountType" class="form-select" onchange="toggleTarget()">
                            <option value="product" {{ old('type') == 'product' ? 'selected' : '' }}>Produk</option>
                            <option value="category" {{ old('type') == 'category' ? 'selected' : '' }}>Kategori</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Target</label>
                        <select name="target_id" id="targetProduct" class="form-select">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('target_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="target_id" id="targetCategory" class="form-select d-none">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('target_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="percentage" {{ old('value_type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed" {{ old('value_type') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nilai Diskon</label>
                        <input type="number" name="value" class="form-control"
                               value="{{ old('value') }}" min="0" placeholder="10 atau 20000">
                    </div>
                </div>

                <div class="mb-3" id="maxDiscountWrap">
                    <label class="form-label fw-semibold">Maks. Diskon (Rp) <span class="text-muted fw-normal">khusus %</span></label>
                    <input type="number" name="max_discount" class="form-control"
                           value="{{ old('max_discount') }}" min="0" placeholder="Opsional">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Mulai Berlaku</label>
                        <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Berakhir</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Aktifkan diskon</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="stackable" value="1" class="form-check-input" id="stackable"
                                   {{ old('stackable', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="stackable">Bisa digabung dengan diskon lain</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-theme w-100">
                    <i class="bi bi-check-lg me-1"></i> Simpan Diskon
                </button>

            </form>
        </div>
    </div>

@endsection

@section('js')
<script>
    function toggleTarget() {
        const type = document.getElementById('discountType').value;
        document.getElementById('targetProduct').classList.toggle('d-none', type !== 'product');
        document.getElementById('targetCategory').classList.toggle('d-none', type !== 'category');

        // Disable yang tersembunyi supaya tidak ikut tersubmit
        document.getElementById('targetProduct').disabled  = type !== 'product';
        document.getElementById('targetCategory').disabled = type !== 'category';
    }

    function toggleMaxDiscount() {
        const type = document.getElementById('valueType').value;
        document.getElementById('maxDiscountWrap').style.display = type === 'percentage' ? 'block' : 'none';
    }

    // Init saat halaman load
    toggleTarget();
    toggleMaxDiscount();
</script>
@endsection