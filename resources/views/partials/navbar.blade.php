<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <div class="brand-logo">GLOW<span>&CO</span></div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#categories">Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#products">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#promo">Promo</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#testimonials">Review</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <button class="btn-cart" data-bs-toggle="modal" data-bs-target="#cartModal">
                    <a href="{{ route('cart.index') }}" class="btn-cart">
                        <i class="bi bi-bag"></i>
                        @if($cartCount > 0)
                            <span class="cart-badge" id="cartBadge">{{ $cartCount }}</span>
                        @else
                            <span class="cart-badge" id="cartBadge" style="display:none">0</span>
                        @endif
                    </a>
                </button>

                @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn-login text-decoration-none">Dashboard</a>
                @else
                    <div class="dropdown">
                        <button class="d-flex align-items-center gap-2 border-0 bg-transparent p-0" 
                                data-bs-toggle="dropdown" aria-expanded="false">               
                            <div class="user-avatar">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                        style="width:36px;height:36px;border-radius:50%;object-fit:cover">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <span style="font-size:0.85rem;color:#999">Halo, {{ auth()->user()->name }}</span>
                            @if(auth()->user()->tier !== 'regular')
                                @php $tierColors = ['silver' => '#c0c0c0', 'gold' => '#C9A87C']; @endphp
                                <span class="badge" style="background:{{ $tierColors[auth()->user()->tier] }};font-size:0.65rem">
                                    {{ strtoupper(auth()->user()->tier) }}
                                </span>
                            @endif
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
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person"></i> Profil Saya
                                    </a>
                                </li>
                            </li>
                            <li>
                                 <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('orders.index') }}">
                                    <i class="bi bi-bag"></i> Pesanan Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('wishlist.index') }}">
                                    <i class="bi bi-heart"></i> Wishlist Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('addresses.index') }}">
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