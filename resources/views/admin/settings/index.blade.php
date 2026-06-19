@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Info Toko --}}
        <div class="col-lg-7">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Informasi Toko</h6>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="{{ route('admin.settings.update-general') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Toko</label>
                            <input type="text" name="store_name" class="form-control"
                                   value="{{ old('store_name', $settings['store_name']) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tagline</label>
                            <textarea name="store_tagline" rows="2" class="form-control">{{ old('store_tagline', $settings['store_tagline']) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">No. WhatsApp</label>
                                <input type="text" name="store_whatsapp" class="form-control"
                                       value="{{ old('store_whatsapp', $settings['store_whatsapp']) }}"
                                       placeholder="6281234567890">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="store_email" class="form-control"
                                       value="{{ old('store_email', $settings['store_email']) }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Alamat Toko</label>
                            <textarea name="store_address" rows="2" class="form-control">{{ old('store_address', $settings['store_address']) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-theme">
                            <i class="bi bi-check-lg me-1"></i> Simpan Informasi Toko
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Rekening Bank --}}
        <div class="col-lg-5">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Rekening Bank</h6>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="{{ route('admin.settings.update-bank') }}" id="bankForm">
                        @csrf

                        <div id="bankList">
                            @foreach($settings['bank_accounts'] as $i => $bank)
                                <div class="border rounded-3 p-3 mb-3 bank-item">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-semibold small">Rekening {{ $i + 1 }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 py-0" onclick="this.closest('.bank-item').remove()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="banks[{{ $i }}][bank]" class="form-control form-control-sm mb-2"
                                           placeholder="Nama Bank (BCA, Mandiri, dll)" value="{{ $bank['bank'] }}">
                                    <input type="text" name="banks[{{ $i }}][number]" class="form-control form-control-sm mb-2"
                                           placeholder="Nomor Rekening" value="{{ $bank['number'] }}">
                                    <input type="text" name="banks[{{ $i }}][name]" class="form-control form-control-sm"
                                           placeholder="Atas Nama" value="{{ $bank['name'] }}">
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-secondary w-100 mb-3" onclick="addBank()">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Rekening
                        </button>

                        <button type="submit" class="btn btn-theme w-100">
                            <i class="bi bi-check-lg me-1"></i> Simpan Rekening
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('js')
<script>
    let bankIndex = {{ count($settings['bank_accounts']) }};

    function addBank() {
        const div = document.createElement('div');
        div.className = 'border rounded-3 p-3 mb-3 bank-item';
        div.innerHTML = `
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-semibold small">Rekening Baru</span>
                <button type="button" class="btn btn-sm btn-outline-danger border-0 py-0" onclick="this.closest('.bank-item').remove()">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <input type="text" name="banks[${bankIndex}][bank]" class="form-control form-control-sm mb-2" placeholder="Nama Bank">
            <input type="text" name="banks[${bankIndex}][number]" class="form-control form-control-sm mb-2" placeholder="Nomor Rekening">
            <input type="text" name="banks[${bankIndex}][name]" class="form-control form-control-sm" placeholder="Atas Nama">
        `;
        document.getElementById('bankList').appendChild(div);
        bankIndex++;
    }
</script>
@endsection