@extends('layouts.admin')
@use('Illuminate\Support\Facades\Storage')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Edit Data Diri --}}
        <div class="col-lg-7">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Data Diri</h6>
                </div>
                <div class="card-body-custom">

                    {{-- Avatar --}}
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 text-center">
                            <div class="avatar-wrapper position-relative d-inline-block" id="avatarWrapper">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}"
                                         id="avatarPreview"
                                         class="rounded-circle"
                                         style="width:90px;height:90px;object-fit:cover;border:3px solid #9A7B67">
                                @else
                                    <div id="avatarInitial"
                                         class="user-avatar rounded-circle d-flex align-items-center justify-content-center"
                                         style="width:90px;height:90px;font-size:2rem;border:3px solid #9A7B67">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <img id="avatarPreview" src="#" class="rounded-circle d-none"
                                         style="width:90px;height:90px;object-fit:cover;border:3px solid #9A7B67">
                                @endif

                                <div class="avatar-overlay rounded-circle" id="avatarOverlay">
                                    <label for="avatarInput" style="cursor:pointer">
                                        <i class="bi bi-pencil-fill" style="font-size:1.2rem;color:white"></i>
                                    </label>
                                </div>

                                <button type="button" id="btnRemoveAvatar"
                                        class="position-absolute rounded-circle d-flex align-items-center justify-content-center border-0 {{ $user->avatar ? '' : 'd-none' }}"
                                        style="width:24px;height:24px;background:#e94560;bottom:2px;right:2px;cursor:pointer"
                                        onclick="removeAvatar()">
                                    <i class="bi bi-x" style="font-size:0.8rem;color:white"></i>
                                </button>

                                <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">
                                <input type="hidden" name="remove_avatar" id="removeAvatarInput" value="0">
                            </div>
                            <div class="text-muted mt-2" style="font-size:0.78rem">Hover untuk edit foto</div>
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
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Ganti Password</h6>
                </div>
                <div class="card-body-custom">
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
        </div>

    </div>

@endsection

@section('css')
<style>
    .avatar-wrapper { cursor: pointer; }
    .avatar-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 90px; height: 90px;
        background: rgba(0,0,0,0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        border-radius: 50%;
        transition: opacity 0.2s ease;
    }
    .avatar-wrapper:hover .avatar-overlay { opacity: 1; }
</style>
@endsection

@section('js')
<script>
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

        document.getElementById('removeAvatarInput').value = '0';
    });

    function removeAvatar() {
        const preview = document.getElementById('avatarPreview');
        const initial = document.getElementById('avatarInitial');
        const removeBtn = document.getElementById('btnRemoveAvatar');
        const input = document.getElementById('avatarInput');

        preview.src = '#';
        preview.classList.add('d-none');
        if (initial) initial.classList.remove('d-none');
        removeBtn.classList.add('d-none');
        input.value = '';

        document.getElementById('removeAvatarInput').value = '1';
    }
</script>
@endsection