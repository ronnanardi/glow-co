<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Glow&Co</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth-body">

<div class="container py-5 min-vh-100 d-flex align-items-center">
    <div class="card auth-card w-100">
        <div class="row g-0">

            <!-- KIRI -->
            <div class="col-lg-6 left-panel d-flex flex-column justify-content-center">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <div class="brand-logo">GLOW<span>&CO</span></div>
                </a>
                <h1 class="hero-title">
                    Raih Kulit <span>Glowing</span> Impianmu
                </h1>
                <p class="mt-4">
                    Daftar sekarang dan dapatkan akses ke promo eksklusif serta produk skincare premium terbaik.
                </p>
            </div>

            <!-- KANAN -->
            <div class="col-lg-6 bg-white p-5 d-flex align-items-center">
                <div class="w-100">

                    <h2 class="fw-bold">Buat Akun Baru</h2>
                    <p class="text-secondary mb-4">Daftar dan mulai perjalanan skincare-mu</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama lengkap"
                                value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Masukkan email"
                                value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Konfirmasi Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password">
                        </div>

                        <button class="btn btn-theme w-100">Daftar</button>

                    </form>

                    <div class="text-center mt-4">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                            Login
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>