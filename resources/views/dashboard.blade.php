@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="page-content fade-in-up">
        <div class="row mb-4">
            <div class="col-md-4">
                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="form-group">
                        <label for="month">Filter Bulan:</label>
                        <input type="month" id="month" name="month" class="form-control" 
                               value="{{ $selected_month }}" onchange="this.form.submit()">
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <h2>Dashboard - {{ Carbon\Carbon::parse($selected_month)->isoFormat('MMMM YYYY') }}</h2>
            </div>
        </div>

        <!-- Main Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-primary color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ number_format($total_barang) }}</h2>
                        <div class="m-b-5">TOTAL BARANG</div>
                        <i class="fa fa-archive widget-stat-icon"></i>
                        <div><i class="fa fa-info-circle m-r-5"></i><small>Total item terdaftar</small></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-info color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ number_format($barang_masuk) }}</h2>
                        <div class="m-b-5">BARANG MASUK</div>
                        <i class="fa fa-arrow-up widget-stat-icon"></i>
                        <div>
                            <i class="fa {{ $persen_barang_masuk >= 0 ? 'fa-level-up text-success' : 'fa-level-down text-danger' }} m-r-5"></i>
                            <small>{{ abs($persen_barang_masuk) }}% {{ $persen_barang_masuk >= 0 ? 'naik' : 'turun' }}</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-warning color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ number_format($barang_keluar) }}</h2>
                        <div class="m-b-5">BARANG KELUAR</div>
                        <i class="fa fa-arrow-down widget-stat-icon"></i>
                        <div>
                            <i class="fa {{ $persen_barang_keluar >= 0 ? 'fa-level-up text-success' : 'fa-level-down text-danger' }} m-r-5"></i>
                            <small>{{ abs($persen_barang_keluar) }}% {{ $persen_barang_keluar >= 0 ? 'naik' : 'turun' }}</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-success color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ number_format($total_persediaan) }}</h2>
                        <div class="m-b-5">TOTAL PERSEDIAAN</div>
                        <i class="fa fa-boxes widget-stat-icon"></i>
                        <div><i class="fa fa-warehouse m-r-5"></i><small>Total stok gudang</small></div>
                    </div>
                </div>
                </div>
                </div>

                <!-- Revenue Statistics -->
                <div class="row mb-4">
                    <div class="col-lg-4 col-md-4">
                        <div class="ibox bg-primary color-white">
                            <div class="ibox-body">
                                <h3 class="m-b-5">Rp {{ number_format($revenue_data['total_penjualan']) }}</h3>
                                <div class="m-b-5">TOTAL PENJUALAN</div>
                                <i class="fa fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="ibox bg-info color-white">
                            <div class="ibox-body">
                                <h3 class="m-b-5">Rp {{ number_format($revenue_data['total_pembelian']) }}</h3>
                                <div class="m-b-5">TOTAL PEMBELIAN</div>
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="ibox {{ $revenue_data['profit'] >= 0 ? 'bg-success' : 'bg-danger' }} color-white">
                            <div class="ibox-body">
                                <h3 class="m-b-5">Rp {{ number_format($revenue_data['profit']) }}</h3>
                                <div class="m-b-5">{{ $revenue_data['profit'] >= 0 ? 'PROFIT' : 'LOSS' }}</div>
                                <i class="fa {{ $revenue_data['profit'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row mb-4">
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Grafik Barang Masuk & Keluar</div>
                    </div>
                    <div class="ibox-body">
                        <canvas id="stockChart" height="300"></canvas>
                        </div>
                        </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="ibox">
                                <div class="ibox-head">
                                    <div class="ibox-title">Distribusi per Kategori</div>
                                </div>
                                <div class="ibox-body">
                                    <canvas id="categoryChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- Tables Section -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="ibox">
                                    <div class="ibox-head">
                                        <div class="ibox-title">Produk Terlaris</div>
                    </div>
                    <div class="ibox-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Terjual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($top_products as $product)
                                        <tr>
                                            <td>{{ $product->barang->nama_barang ?? 'N/A' }}</td>
                                            <td><span class="badge badge-success">{{ number_format($product->total_terjual) }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                    </div>
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Stok Menipis</div>
                    </div>
                    <div class="ibox-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($low_stock as $item)
                                        <tr>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>{{ $item->stok }}</td>
                                            <td>
                                                <span class="badge {{ $item->stok < 5 ? 'badge-danger' : 'badge-warning' }}">
                                                    {{ $item->keterangan }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Semua stok aman</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        </div>
                        </div>
            </div>
            </div>

            <!-- Kategori Details -->
            <div class="row mt-4">
                <div class="col-12">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Detail per Kategori</div>
                    </div>
                    <div class="ibox-body">
                        <div class="row">
                            @foreach($kategori_data as $kategori)
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="card border-left-primary h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $kategori['nama'] }}</h5>
                                            <p class="card-text">
                                                <strong>{{ $kategori['jumlah_item'] }}</strong> jenis barang<br>
                                                <strong>{{ number_format($kategori['total_stok']) }}</strong> total stok
                                            </p>
                                        </div>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
@endsection
        
        @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    // Stock Chart
                    const stockCtx = document.getElementById('stockChart').getContext('2d');
                    const stockChart = new Chart(stockCtx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($chart_data['labels']) !!},
                            datasets: [{
                                label: 'Barang Masuk',
                                data: {!! json_encode($chart_data['masuk']) !!},
                                borderColor: 'rgb(75, 192, 192)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                tension: 0.1
                            }, {
                                label: 'Barang Keluar',
                                data: {!! json_encode($chart_data['keluar']) !!},
                                borderColor: 'rgb(255, 99, 132)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // Category Chart
                    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
                    const categoryChart = new Chart(categoryCtx, {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode($kategori_data->pluck('nama')) !!},
                            datasets: [{
                                data: {!! json_encode($kategori_data->pluck('total_stok')) !!},
                                backgroundColor: [
                                    '#FF6384',
                                    '#36A2EB',
                                    '#FFCE56',
                                    '#4BC0C0',
                                    '#9966FF',
                                    '#FF9F40'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                    }
                }
            });
            </script>
        @endpush