
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        GLOW<span>&CO</span>
        <small>Admin Panel</small>
    </div>
    <nav class="sidebar-menu">
        <div class="menu-label">Menu Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Produk
        </a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i> Pesanan
        </a>
        <a href="#" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Pelanggan
        </a>

        <div class="menu-label mt-3">Manajemen</div>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Kategori
        </a>

        <a href="#"><i class="bi bi-percent"></i> Promo & Voucher</a>
        <a href="#"><i class="bi bi-star"></i> Review</a>
        <a href="#"><i class="bi bi-truck"></i> Pengiriman</a>

        <div class="menu-label mt-3">Laporan</div>
        <a href="#"><i class="bi bi-graph-up"></i> Statistik Penjualan</a>
        <a href="#"><i class="bi bi-clipboard-data"></i> Laporan Stok</a>

        <div class="menu-label mt-3">Lainnya</div>
        <a href="#"><i class="bi bi-gear"></i> Pengaturan</a>
        <a href="{{ route('home') }}"><i class="bi bi-house"></i> Lihat Toko</a>

        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="w-100 text-start border-0 bg-transparent sidebar-logout">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </nav>
</aside>