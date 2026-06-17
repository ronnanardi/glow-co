@extends('layouts.admin')

@section('title', 'Edit Voucher')
@section('page-title', 'Edit Voucher')

@section('content')

    <div class="card-panel" style="max-width:650px">
        <div class="card-header-custom">
            <h6>Form Edit Voucher</h6>
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

            <form method="POST" action="{{ route('admin.vouchers.update', $voucher) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Voucher</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $voucher->code) }}" style="text-transform:uppercase">
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tipe Diskon</label>
                        <select name="type" id="typeSelect" class="form-select" onchange="toggleMaxDiscount()">
                            <option value="percentage" {{ old('type', $voucher->type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed" {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nilai Diskon</label>
                        <input type="number" name="value" class="form-control"
                               value="{{ old('value', $voucher->value) }}" min="1">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Minimal Belanja (Rp)</label>
                        <input type="number" name="min_purchase" class="form-control"
                               value="{{ old('min_purchase', $voucher->min_purchase) }}" min="0">
                    </div>
                    <div class="col-md-6 mb-3" id="maxDiscountWrap">
                        <label class="form-label fw-semibold">Maks. Diskon (Rp)</label>
                        <input type="number" name="max_discount" class="form-control"
                               value="{{ old('max_discount', $voucher->max_discount) }}" min="0">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Batas Pemakaian</label>
                    <input type="number" name="usage_limit" class="form-control"
                           value="{{ old('usage_limit', $voucher->usage_limit) }}" min="1">
                    <div class="form-text">Sudah dipakai: {{ $voucher->used_count }} kali</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Mulai Berlaku</label>
                        <input type="date" name="starts_at" class="form-control"
                               value="{{ old('starts_at', $voucher->starts_at?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Berakhir</label>
                        <input type="date" name="expires_at" class="form-control"
                               value="{{ old('expires_at', $voucher->expires_at?->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive"
                           {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Voucher aktif</label>
                </div>

                <button type="submit" class="btn btn-theme w-100">
                    <i class="bi bi-check-lg me-1"></i> Update Voucher
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