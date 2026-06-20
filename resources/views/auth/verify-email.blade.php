<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - GLOW&CO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth-body">

<div class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card auth-card" style="max-width:500px">
        <div class="p-5 text-center">

            <div class="auth-icon-circle">
                <i class="bi bi-envelope-paper"></i>
            </div>

            <h3 class="fw-bold mt-3" style="font-family:'Playfair Display',serif">Verifikasi Email Kamu</h3>
            <p class="text-secondary mt-2">
                Terima kasih sudah mendaftar! Kami sudah mengirimkan link verifikasi ke email kamu.
                Silakan cek inbox (atau folder spam) untuk verifikasi akun.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success mt-3">
                    Link verifikasi baru telah dikirim ke email kamu.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
                @csrf
                <button class="btn btn-theme w-100">Kirim Ulang Email Verifikasi</button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button class="btn btn-google w-100">Logout</button>
            </form>

        </div>
    </div>
</div>

</body>
</html>