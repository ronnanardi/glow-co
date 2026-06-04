<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Glow&Co</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="container py-5 min-vh-100 d-flex align-items-center">
    <div class="card auth-card w-100">
        <div class="row g-0">

            <!-- KIRI -->
            <div class="col-lg-6 left-panel d-flex flex-column justify-content-center">
                <a class="navbar-brand" href="#">
                    <div class="brand-logo">GLOW<span>&CO</span></div>
                </a>
                <h1 class="hero-title">
                    Raih Kulit <span>Glowing</span> Impianmu
                </h1>
                <p class="text-secondary mt-4">
                    Masuk untuk melihat promo eksklusif dan produk skincare premium terbaik.
                </p>
            </div>

            <!-- KANAN -->
            <div class="col-lg-6 bg-white p-5 d-flex align-items-center">
                <div class="w-100">

                    <h2 class="fw-bold">Selamat Datang</h2>
                    <p class="text-secondary mb-4">Login ke akun Anda</p>

                    {{-- Tampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Masukkan email"
                                value="{{ old('email') }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password">
                        </div>

                        <button class="btn btn-theme w-100">Login</button>

                    </form>

                    <div class="text-center mt-4">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:#9A7B67">
                            Daftar
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>