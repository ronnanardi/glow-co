<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - GLOW&CO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth-body">

<div class="container py-5 min-vh-100 d-flex align-items-center">
    <div class="card auth-card w-100">
        <div class="row g-0">

            <div class="col-lg-6 left-panel d-flex flex-column justify-content-center">
                <div class="navbar-brand">
                    <div class="brand-logo">GLOW<span>&CO</span></div>
                </div>
                <h1 class="hero-title">
                    Raih Kulit <span>Glowing</span> Impianmu
                </h1>
                <p class="mt-4">
                    Masuk untuk melihat promo eksklusif dan produk skincare premium terbaik.
                </p>
            </div>

            <div class="col-lg-6 bg-white p-5 d-flex align-items-center">
                <div class="w-100">

                    <h2 class="fw-bold">Buat Akun Baru</h2>
                    <p class="text-secondary mb-4">Daftar dan mulai perjalanan skincare-mu</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
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
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            <div class="form-text" style="font-size:0.78rem">Minimal 8 karakter</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms" style="font-size:0.85rem">
                                Saya setuju dengan <a href="#" class="forgot-link">Syarat & Ketentuan</a>
                            </label>
                        </div>

                        <button class="btn btn-theme w-100">Daftar</button>

                    </form>

                    <div class="auth-divider">
                        <hr><span>atau</span><hr>
                    </div>

                    <a href="{{ route('auth.google') }}" class="btn btn-google w-100">
                        <svg width="18" height="18" viewBox="0 0 18 18">
                            <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z"/>
                            <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.983 5.482 18 9 18z"/>
                            <path fill="#FBBC05" d="M3.964 10.71c-.18-.54-.282-1.117-.282-1.71s.102-1.17.282-1.71V4.958H.957C.348 6.173 0 7.548 0 9s.348 2.827.957 4.042l3.007-2.332z"/>
                            <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z"/>
                        </svg>
                        Daftar dengan Google
                    </a>

                    <div class="text-center mt-4">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>