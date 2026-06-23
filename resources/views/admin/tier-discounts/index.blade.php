@extends('layouts.admin')

@section('title', 'Diskon Member Tier')
@section('page-title', 'Diskon Member Tier')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Pengaturan Diskon Tier --}}
        <div class="col-lg-6">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Pengaturan Diskon per Tier</h6>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="{{ route('admin.tier-discounts.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 p-3 rounded-3" style="background:var(--cream)">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge" style="background:#c0c0c0;font-size:0.8rem">SILVER</span>
                                <span class="fw-semibold">Member Silver</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" name="silver_value" class="form-control"
                                       style="width:100px"
                                       value="{{ old('silver_value', $tierDiscounts['silver']->value ?? 2) }}"
                                       min="0" max="100" step="0.5">
                                <span class="text-muted">% diskon dari subtotal</span>
                            </div>
                        </div>

                        <div class="mb-4 p-3 rounded-3" style="background:var(--cream)">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge" style="background:#C9A87C;font-size:0.8rem">GOLD</span>
                                <span class="fw-semibold">Member Gold</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" name="gold_value" class="form-control"
                                       style="width:100px"
                                       value="{{ old('gold_value', $tierDiscounts['gold']->value ?? 5) }}"
                                       min="0" max="100" step="0.5">
                                <span class="text-muted">% diskon dari subtotal</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-theme">
                            <i class="bi bi-check-lg me-1"></i> Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Statistik Member --}}
        <div class="col-lg-6">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Statistik Member</h6>
                </div>
                <div class="card-body-custom">
                    @foreach(['regular' => ['label' => 'Regular', 'color' => '#999'], 'silver' => ['label' => 'Silver', 'color' => '#c0c0c0'], 'gold' => ['label' => 'Gold', 'color' => '#C9A87C']] as $tier => $config)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom:1px solid var(--border)">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge" style="background:{{ $config['color'] }}">{{ $config['label'] }}</span>
                                <span style="font-size:0.88rem">Member {{ $config['label'] }}</span>
                            </div>
                            <span class="fw-bold">{{ $tierStats[$tier] }} customer</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- Volume Discounts --}}
    <div class="card-panel mt-4">
        <div class="card-header-custom">
            <h6>Diskon Volume/Grosir</h6>
            <a href="{{ route('admin.volume-discounts.create') }}" class="btn btn-sm btn-theme">
                <i class="bi bi-plus-lg me-1"></i> Tambah
            </a>
        </div>
        <div class="card-body-custom">
            <a href="{{ route('admin.volume-discounts.index') }}" class="btn btn-outline-secondary btn-sm">
                Kelola Diskon Volume <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

@endsection