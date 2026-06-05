@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')

    <div class="card-panel" style="max-width:600px">
        <div class="card-header-custom">
            <h6>Form Edit Kategori</h6>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary">
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

            <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $category->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">URL Gambar <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="url"
                           name="image"
                           class="form-control @error('image') is-invalid @enderror"
                           value="{{ old('image', $category->image) }}">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Preview gambar --}}
                @if($category->image)
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Preview</label><br>
                        <img src="{{ $category->image }}"
                             style="width:120px;height:120px;object-fit:cover;border-radius:10px">
                    </div>
                @endif

                <button type="submit" class="btn btn-theme w-100">
                    <i class="bi bi-check-lg me-1"></i> Update Kategori
                </button>

            </form>
        </div>
    </div>

@endsection