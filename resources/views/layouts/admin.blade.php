<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | GLOW&CO</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    @yield('css')
</head>
<body>

    @include('partials.admin.sidebar')

    <div class="main-content">

         <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="dropdown">
                <button class="d-flex align-items-center gap-2 border-0 bg-transparent p-0"
            data-bs-toggle="dropdown" aria-expanded="false">
        <span style="font-size:0.85rem;color:#999">
            Halo, {{ auth()->user()->name }}
        </span>
        <div class="user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <i class="bi bi-chevron-down" style="font-size:0.75rem;color:#999"></i>
    </button>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li>
                        <div class="px-3 py-2 border-bottom">
                            <div class="fw-semibold" style="font-size:0.9rem">{{ auth()->user()->name }}</div>
                            <div class="text-muted" style="font-size:0.78rem">{{ auth()->user()->email }}</div>
                            <span class="badge mt-1" style="background:#9A7B67;font-size:0.7rem">Administrator</span>
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">
                            <i class="bi bi-person"></i> Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('home') }}">
                            <i class="bi bi-house"></i> Lihat Toko
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        {{-- Konten --}}
        <div class="content-area">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js')
</body>
</html>