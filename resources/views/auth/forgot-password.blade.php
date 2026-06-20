<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - GLOW&CO</title>
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
                    Lupa <span>Password</span>?
                </h1>
                <p class="mt-4">
                    Tenang, kami akan kirimkan link untuk reset password ke email kamu.
                </p>
            </div>

            <div class="col-lg-6 bg-white p-5 d-flex align-items-center">
                <div class="w-100">

                    <h2 class="fw-bold">Reset Password</h2>
                    <p class="text-secondary mb-4">Masukkan email yang terdaftar</p>

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

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Masukkan email terdaftar" value="{{ old('email') }}">
                        </div>

                        <button class="btn btn-theme w-100">Kirim Link Reset Password</button>

                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="forgot-link text-decoration-none fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>