@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')

@section('title', 'Profil Saya')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    <h4 class="fw-bold mb-4">Profil Saya</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Edit Data Diri --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="user-avatar" style="width:50px;height:50px;font-size:1.2rem;overflow:hidden">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                            @else
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size:1.1rem">{{ $user->name }}</div>
                            <div class="text-muted" style="font-size:0.85rem">Bergabung {{ $user->created_at->format('d M Y') }}</div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3">Data Diri</h6>

                    @if($errors->has('name') || $errors->has('email') || $errors->has('phone'))
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Avatar --}}
                        <div class="mb-4 text-center">
                            <div class="avatar-wrapper position-relative d-inline-block" id="avatarWrapper">

                                {{-- Avatar display --}}
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}"
                                        id="avatarPreview"
                                        class="rounded-circle"
                                        style="width:90px;height:90px;object-fit:cover;border:3px solid #9A7B67">
                                @else
                                    <div id="avatarInitial"
                                        class="user-avatar rounded-circle"
                                        style="width:90px;height:90px;font-size:2rem;border:3px solid #9A7B67">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <img id="avatarPreview" src="#"
                                        class="rounded-circle d-none"
                                        style="width:90px;height:90px;object-fit:cover;border:3px solid #9A7B67">
                                @endif

                                {{-- Overlay hover (edit) --}}
                                <div class="avatar-overlay rounded-circle" id="avatarOverlay">
                                    <label for="avatarInput" style="cursor:pointer">
                                        <i class="bi bi-pencil-fill" style="font-size:1.2rem;color:white"></i>
                                    </label>
                                </div>

                                {{-- Tombol hapus (pojok bawah kanan) --}}
                                @if($user->avatar)
                                    <button type="button" id="btnRemoveAvatar"
                                            class="position-absolute rounded-circle d-flex align-items-center justify-content-center border-0"
                                            style="width:24px;height:24px;background:#e94560;bottom:2px;right:2px;cursor:pointer"
                                            onclick="removeAvatar()">
                                        <i class="bi bi-x" style="font-size:0.8rem;color:white"></i>
                                    </button>
                                @else
                                    <button type="button" id="btnRemoveAvatar"
                                            class="position-absolute rounded-circle d-flex align-items-center justify-content-center border-0 d-none"
                                            style="width:24px;height:24px;background:#e94560;bottom:2px;right:2px;cursor:pointer"
                                            onclick="removeAvatar()">
                                        <i class="bi bi-x" style="font-size:0.8rem;color:white"></i>
                                    </button>
                                @endif

                                <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">
                                <input type="hidden" name="remove_avatar" id="removeAvatarInput" value="0">
                            </div>
                            <div class="text-muted mt-2" style="font-size:0.78rem">Hover untuk edit foto</div>
                            @error('avatar')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">No. Telepon</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                        </div>

                        <button type="submit" class="btn btn-theme">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Ganti Password --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Ganti Password</h6>

                    @if($errors->has('current_password') || $errors->has('password'))
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Lama</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-theme w-100">
                            <i class="bi bi-shield-lock me-1"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>

            {{-- Shortcut --}}
            <div class="card border-0 shadow-sm rounded-3 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Akses Cepat</h6>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-bag me-2"></i> Pesanan Saya
                        </a>
                        <a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-geo-alt me-2"></i> Alamat Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('js')
    <script>
        // Preview saat pilih gambar baru
        document.getElementById('avatarInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const preview = document.getElementById('avatarPreview');
            const initial = document.getElementById('avatarInitial');
            const removeBtn = document.getElementById('btnRemoveAvatar');

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
            if (initial) initial.classList.add('d-none');
            if (removeBtn) removeBtn.classList.remove('d-none');

            // Reset remove flag
            document.getElementById('removeAvatarInput').value = '0';
        });

        // Hapus avatar
        function removeAvatar() {
            const preview = document.getElementById('avatarPreview');
            const initial = document.getElementById('avatarInitial');
            const removeBtn = document.getElementById('btnRemoveAvatar');
            const input = document.getElementById('avatarInput');

            // Reset ke inisial
            preview.src = '#';
            preview.classList.add('d-none');
            if (initial) initial.classList.remove('d-none');
            removeBtn.classList.add('d-none');

            // Kosongkan file input
            input.value = '';

            // Tandai untuk dihapus di server
            document.getElementById('removeAvatarInput').value = '1';
        }
    </script>
@endsection