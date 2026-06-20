<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GLOW&CO</title>
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

                    <h2 class="fw-bold">Selamat Datang</h2>
                    <p class="text-secondary mb-4">Login ke akun Anda</p>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
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
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Masukkan email" value="{{ old('email') }}">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Masukkan password">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember" style="font-size:0.88rem">
                                    Ingat saya
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="forgot-link text-decoration-none" style="font-size:0.85rem">
                                Lupa password?
                            </a>
                        </div>

                        <button class="btn btn-theme w-100">Login</button>

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
                        Login dengan Google
                    </a>

                    <div class="text-center mt-4">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Daftar</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>