<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <div class="brand-logo">GLOW<span>&CO</span></div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#categories">Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="#products">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="#promo">Promo</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimonials">Review</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <button class="btn-cart" data-bs-toggle="modal" data-bs-target="#cartModal">
                    <i class="bi bi-bag"></i>
                    <span class="cart-badge" id="cartBadge">0</span>
                </button>

                @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn-login text-decoration-none">Dashboard</a>
                @else
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
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">
                                    <i class="bi bi-person"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">
                                    <i class="bi bi-bag"></i> Pesanan Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">
                                    <i class="bi bi-geo-alt"></i> Alamat Saya
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
                @endif
                @else
                    <a href="{{ route('login') }}" class="btn-login text-decoration-none">Login</a>
                @endauth

            </div>
        </div>
    </div>
</nav>