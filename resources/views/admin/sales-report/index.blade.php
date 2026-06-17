@extends('layouts.admin')

@section('title', 'Statistik Penjualan')
@section('page-title', 'Statistik Penjualan')

@section('content')

    {{-- Filter Tanggal & Export --}}
    <div class="card-panel mb-4">
        <div class="card-body-custom">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control"
                           value="{{ $start->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control"
                           value="{{ $end->format('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-theme w-100">Tampilkan</button>
                </div>
                <div class="col-md-4 d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.sales-report.export-excel', request()->query()) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel
                    </a>
                    <a href="{{ route('admin.sales-report.export-pdf', request()->query()) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(168,181,160,0.15);color:var(--sage)">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(139,111,94,0.1);color:var(--secondary)">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <div class="stat-value">{{ $totalOrders }}</div>
                <div class="stat-label">Total Order</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(201,168,124,0.15);color:var(--accent)">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-value">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</div>
                <div class="stat-label">Rata-rata per Order</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

        {{-- Grafik Garis --}}
        <div class="col-lg-8">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Tren Penjualan</h6>
                </div>
                <div class="card-body-custom">
                    <canvas id="salesLineChart" height="280"></canvas>
                </div>
            </div>
        </div>

        {{-- Grafik Batang --}}
        <div class="col-lg-4">
            <div class="card-panel">
                <div class="card-header-custom">
                    <h6>Penjualan per Kategori</h6>
                </div>
                <div class="card-body-custom">
                    <canvas id="categoryBarChart" height="280"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- Tabel Produk Terlaris --}}
    <div class="card-panel">
        <div class="card-header-custom">
            <h6>Produk Terlaris</h6>
        </div>
        <div class="card-body-custom">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Jumlah Terjual</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $product->product_name }}</td>
                            <td>{{ $product->total_sold }} pcs</td>
                            <td style="color:var(--secondary);font-weight:600">
                                Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada penjualan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Grafik Garis - Tren Penjualan
    new Chart(document.getElementById('salesLineChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Penjualan',
                data: @json($chartData),
                borderColor: '#9A7B67',
                backgroundColor: 'rgba(154, 123, 103, 0.1)',
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    // Grafik Batang - Per Kategori
    new Chart(document.getElementById('categoryBarChart'), {
        type: 'bar',
        data: {
            labels: @json($categorySales->pluck('category')),
            datasets: [{
                label: 'Penjualan',
                data: @json($categorySales->pluck('total')),
                backgroundColor: ['#9A7B67', '#C9A87C', '#A8B5A0', '#D4A0A0'],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: {
                        callback: value => 'Rp ' + (value / 1000) + 'rb'
                    }
                }
            }
        }
    });
</script>
@endsection