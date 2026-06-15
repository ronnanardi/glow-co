@extends('layouts.app')

@section('title', 'Tambah Alamat')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('addresses.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Tambah Alamat</h4>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3" style="max-width:700px">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('addresses.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Label Alamat</label>
                    <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                           value="{{ old('label') }}" placeholder="contoh: Rumah, Kantor">
                    @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Penerima</label>
                        <input type="text" name="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror"
                               value="{{ old('recipient_name') }}">
                        @error('recipient_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">No. Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror"
                              placeholder="Nama jalan, no rumah, RT/RW, kecamatan">{{ old('address') }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Cari Kecamatan/Kota</label>
                    <input type="text" id="citySearch" class="form-control"
                        placeholder="Ketik minimal 3 huruf, misal: Sidoarjo"
                        value="{{ old('city') }}" autocomplete="off">

                    <div id="cityResults" class="list-group mt-1" style="position:absolute;z-index:1000;width:calc(100% - 2px)"></div>

                    <input type="hidden" name="city_id" id="cityId" value="{{ old('city_id', $address->city_id ?? '') }}">
                    <input type="hidden" name="city" id="cityName" value="{{ old('city', $address->city ?? '') }}">
                    <input type="hidden" name="province" id="provinceName" value="{{ old('province', $address->province ?? '') }}">

                    @error('city_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Kode Pos</label>
                    <input type="text" name="postal_code" id="postalCode"
                           class="form-control @error('postal_code') is-invalid @enderror"
                           value="{{ old('postal_code') }}">
                    @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="is_primary" value="1" class="form-check-input" id="isPrimary"
                           {{ old('is_primary') ? 'checked' : '' }}>
                    <label class="form-check-label" for="isPrimary">Jadikan alamat utama</label>
                </div>

                <button type="submit" class="btn btn-theme w-100">
                    <i class="bi bi-check-lg me-1"></i> Simpan Alamat
                </button>

            </form>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
    const citySearch = document.getElementById('citySearch');
    const cityResults = document.getElementById('cityResults');
    let debounce;

    citySearch.addEventListener('input', function() {
        clearTimeout(debounce);
        const keyword = this.value;

        if (keyword.length < 3) {
            cityResults.innerHTML = '';
            return;
        }

        debounce = setTimeout(() => {
            fetch(`{{ route('destinations.search') }}?q=${keyword}`)
                .then(res => res.json())
                .then(data => {
                    cityResults.innerHTML = '';
                    data.forEach(item => {
                        const div = document.createElement('button');
                        div.type = 'button';
                        div.className = 'list-group-item list-group-item-action';
                        div.style.fontSize = '0.85rem';
                        div.textContent = `${item.subdistrict_name}, ${item.city_name}, ${item.province_name} ${item.zip_code}`;

                        div.addEventListener('click', () => {
                            citySearch.value = `${item.subdistrict_name}, ${item.city_name}`;
                            document.getElementById('cityId').value = item.id;
                            document.getElementById('cityName').value = item.city_name;
                            document.getElementById('provinceName').value = item.province_name;
                            document.getElementById('postalCode').value = item.zip_code;
                            cityResults.innerHTML = '';
                        });

                        cityResults.appendChild(div);
                    });
                });
        }, 400);
    });

    document.addEventListener('click', function(e) {
        if (!citySearch.contains(e.target)) cityResults.innerHTML = '';
    });
</script>
@endsection