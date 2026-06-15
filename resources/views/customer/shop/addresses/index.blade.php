@extends('layouts.app')

@section('title', 'Alamat Saya')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Alamat Saya</h4>
        <a href="{{ route('addresses.create') }}" class="btn btn-theme">
            <i class="bi bi-plus-lg me-1"></i> Tambah Alamat
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse($addresses as $address)
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold">{{ $address->label }}</span>
                            @if($address->is_primary)
                                <span class="badge" style="background:#9A7B67;font-size:0.7rem">Alamat Utama</span>
                            @endif
                        </div>
                        <div class="fw-semibold">{{ $address->recipient_name }} · {{ $address->phone }}</div>
                        <div class="text-muted mt-1" style="font-size:0.88rem">
                            {{ $address->address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('addresses.destroy', $address) }}"
                              onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-geo-alt" style="font-size:4rem;color:#ddd"></i>
            <h5 class="mt-3 text-muted">Belum ada alamat tersimpan</h5>
            <a href="{{ route('addresses.create') }}" class="btn btn-theme mt-3">Tambah Alamat</a>
        </div>
    @endforelse

</div>
@endsection