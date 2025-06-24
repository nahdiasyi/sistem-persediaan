@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <h4 class="fw-bold">Laporan Penjualan</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Penjualan</li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Penjualan</li>
            </ol>
        </nav>
    </div>

    <!-- Filter -->

    <form method="GET" action="{{ route('laporan.kasir.index') }}" class="card mb-4 p-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">Filter Laporan</h4>
                </div>
                <label for="barang" class="form-label">Barang</label>
                <select name="cari_barang" id="barang" class="form-select">
                    <option value="">Semua Barang</option>
                    @foreach($listBarang as $barang)
                        <option value="{{ $barang->nama_barang }}" {{ request('cari_barang') == $barang->nama_barang ? 'selected' : '' }}>
                            {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="tanggal_dari" class="form-label">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-3">
                <label for="tanggal_sampai" class="form-label">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-md-2 d-grid gap-2">
                <button class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                <a href="{{ route('laporan.kasir.index') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Reset</a>
                <a href="{{ route('laporan.kasir.pdf') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>
            </div>
        </div>
    </form>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="bg-primary text-white p-3 rounded text-center">
                <div class="fs-4 fw-bold">{{ $statistik['total_transaksi'] }}</div>
                <div>Total Transaksi</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-info text-white p-3 rounded text-center">
                <div class="fs-4 fw-bold">{{ $statistik['total_item'] }}</div>
                <div>Total Item</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-success text-white p-3 rounded text-center">
                <div class="fs-4 fw-bold">Rp {{ number_format($statistik['total_nilai'], 0, ',', '.') }}</div>
                <div>Total Nilai</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-warning text-white p-3 rounded text-center">
                <div class="fs-4 fw-bold">{{ $statistik['user_aktif'] }}</div>
                <div>User Aktif</div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>ID Penjualan</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $index => $item)
                        <tr>
                            <td>{{ $penjualan->firstItem() + $index }}</td>
                            <td>{{ $item->id_penjualan }}</td>
                            <td>{{ $item->tanggal->format('d/m/Y H:i') }}</td>
                            <td>{{ $item->user->nama_user ?? '-' }}</td>
                            <td>{{ $item->detailPenjualan->sum('jumlah') }}</td>
                            <td>Rp {{ number_format($item->detailPenjualan->sum(fn($d) => $d->jumlah * $d->harga), 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Penjualan {{ $item->id_penjualan }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>ID Penjualan:</strong> {{ $item->id_penjualan }}<br>
                                                <strong>Tanggal:</strong> {{ $item->tanggal->format('d/m/Y H:i:s') }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Kasir:</strong> {{ $item->user->nama_user ?? '-' }}
                                            </div>
                                        </div>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah</th>
                                                    <th>Harga</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item->detailPenjualan as $detail)
                                                <tr>
                                                    <td>{{ $detail->kode_barang }}</td>
                                                    <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                                                    <td>{{ $detail->jumlah }}</td>
                                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-info">
                                                    <th colspan="4">Total</th>
                                                    <th>Rp {{ number_format($item->detailPenjualan->sum(fn($d) => $d->jumlah * $d->harga), 0, ',', '.') }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data penjualan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $penjualan->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
