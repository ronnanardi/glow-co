@extends('layouts.admin')

@section('title', 'Buat Voucher')
@section('page-title', 'Buat Voucher')

@section('content')

    <div class="card-panel" style="max-width:650px">
        <div class="card-header-custom">
            <h6>Form Voucher Baru</h6>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-sm btn-outline-secondary">
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

            <form method="POST" action="{{ route('admin.vouchers.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Voucher</label>
                    <input type="text" name="code" class="form-control text-uppercase @error('code') is-invalid @enderror"
                           value="{{ old('code') }}" placeholder="contoh: GLOWNEW10" style="text-transform:uppercase">
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tipe Diskon</label>
                        <select name="type" id="typeSelect" class="form-select @error('type') is-invalid @enderror" onchange="toggleMaxDiscount()">
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nilai Diskon</label>
                        <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                               value="{{ old('value') }}" placeholder="10 atau 20000" min="1">
                        @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Minimal Belanja (Rp)</label>
                        <input type="number" name="min_purchase" class="form-control"
                               value="{{ old('min_purchase', 0) }}" min="0">
                    </div>
                    <div class="col-md-6 mb-3" id="maxDiscountWrap">
                        <label class="form-label fw-semibold">Maks. Diskon (Rp) <span class="text-muted fw-normal">khusus %</span></label>
                        <input type="number" name="max_discount" class="form-control"
                               value="{{ old('max_discount') }}" min="0" placeholder="Opsional">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Batas Pemakaian <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="number" name="usage_limit" class="form-control"
                           value="{{ old('usage_limit') }}" min="1" placeholder="Kosongkan untuk tanpa batas">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Mulai Berlaku</label>
                        <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Berakhir</label>
                        <input type="date" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror"
                               value="{{ old('expires_at') }}">
                        @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Aktifkan voucher sekarang</label>
                </div>

                <button type="submit" class="btn btn-theme w-100">
                    <i class="bi bi-check-lg me-1"></i> Simpan Voucher
                </button>

            </form>
        </div>
    </div>

@endsection

@section('js')
<script>
    function toggleMaxDiscount() {
        const type = document.getElementById('typeSelect').value;
        document.getElementById('maxDiscountWrap').style.display = type === 'percentage' ? 'block' : 'none';
    }
    toggleMaxDiscount();
</script>
@endsection