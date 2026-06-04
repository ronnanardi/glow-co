@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Stats --}}
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(139,111,94,0.1);color:var(--secondary)">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <div class="stat-value">1,248</div>
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-change up"><i class="bi bi-arrow-up"></i> 12.5% bulan ini</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(168,181,160,0.15);color:var(--sage)">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-value">85.2M</div>
                <div class="stat-label">Pendapatan</div>
                <div class="stat-change up"><i class="bi bi-arrow-up"></i> 8.3% bulan ini</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(201,168,124,0.15);color:var(--accent)">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div class="stat-value">156</div>
                <div class="stat-label">Total Produk</div>
                <div class="stat-change up"><i class="bi bi-arrow-up"></i> 5 produk baru</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(212,160,160,0.15);color:var(--rose)">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-value">3,842</div>
                <div class="stat-label">Pelanggan</div>
                <div class="stat-change up"><i class="bi bi-arrow-up"></i> 24 pelanggan baru</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Recent Orders --}}
        <div class="col-lg-8">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Pesanan Terbaru</h6>
                    <a href="#" style="font-size:0.82rem;color:var(--secondary);text-decoration:none;font-weight:600">Lihat Semua</a>
                </div>
                <div class="card-body-custom">
                    <table class="table-clean">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Produk</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#GC-1024</td>
                                <td>Vitamin C Serum 30ml</td>
                                <td>Dinda S.</td>
                                <td>Rp 189.000</td>
                                <td><span class="status-badge status-success">Dikirim</span></td>
                            </tr>
                            <tr>
                                <td>#GC-1023</td>
                                <td>Gentle Cleanser pH 5.5</td>
                                <td>Rani M.</td>
                                <td>Rp 129.000</td>
                                <td><span class="status-badge status-pending">Diproses</span></td>
                            </tr>
                            <tr>
                                <td>#GC-1022</td>
                                <td>Niacinamide Serum 30ml</td>
                                <td>Budi A.</td>
                                <td>Rp 175.000</td>
                                <td><span class="status-badge status-success">Dikirim</span></td>
                            </tr>
                            <tr>
                                <td>#GC-1021</td>
                                <td>HA Moisturizer Gel</td>
                                <td>Maya K.</td>
                                <td>Rp 159.000</td>
                                <td><span class="status-badge status-cancelled">Dibatalkan</span></td>
                            </tr>
                            <tr>
                                <td>#GC-1020</td>
                                <td>Sunscreen SPF 50+</td>
                                <td>Sari W.</td>
                                <td>Rp 145.000</td>
                                <td><span class="status-badge status-success">Dikirim</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="col-lg-4">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Produk Terlaris</h6>
                </div>
                <div class="card-body-custom">
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--border)">
                        <div style="width:42px;height:42px;border-radius:10px;background:var(--cream);display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-droplet-fill" style="color:var(--secondary)"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-weight:600;font-size:0.88rem">Vitamin C Serum</div>
                            <div style="font-size:0.75rem;color:#999">328 terjual</div>
                        </div>
                        <div style="font-weight:700;font-size:0.88rem;color:var(--secondary)">Rp 189K</div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--border)">
                        <div style="width:42px;height:42px;border-radius:10px;background:var(--cream);display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-droplet-fill" style="color:var(--accent)"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-weight:600;font-size:0.88rem">Niacinamide Serum</div>
                            <div style="font-size:0.75rem;color:#999">256 terjual</div>
                        </div>
                        <div style="font-weight:700;font-size:0.88rem;color:var(--secondary)">Rp 175K</div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--border)">
                        <div style="width:42px;height:42px;border-radius:10px;background:var(--cream);display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-moisture" style="color:var(--rose)"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-weight:600;font-size:0.88rem">HA Moisturizer</div>
                            <div style="font-size:0.75rem;color:#999">198 terjual</div>
                        </div>
                        <div style="font-weight:700;font-size:0.88rem;color:var(--secondary)">Rp 175K</div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;border-radius:10px;background:var(--cream);display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-sun-fill" style="color:var(--sage)"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-weight:600;font-size:0.88rem">Sunscreen SPF 50+</div>
                            <div style="font-size:0.75rem;color:#999">175 terjual</div>
                        </div>
                        <div style="font-weight:700;font-size:0.88rem;color:var(--secondary)">Rp 145K</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection