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
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Filter Laporan</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.kasir.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
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
                        <input type="date" name="tanggal_dari" id="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="tanggal_sampai" class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" id="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                    </div>
                    <div class="col-md-2">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('laporan.kasir.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                            <a href="{{ route('laporan.kasir.pdf') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success" target="_blank">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <div class="fs-4 fw-bold">{{ $statistik['total_transaksi'] }}</div>
                    <div>Total Transaksi</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <div class="fs-4 fw-bold">{{ $statistik['total_item'] }}</div>
                    <div>Total Item</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <div class="fs-4 fw-bold">Rp {{ number_format($statistik['total_nilai'], 0, ',', '.') }}</div>
                    <div>Total Nilai</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <div class="fs-4 fw-bold">{{ $statistik['user_aktif'] }}</div>
                    <div>User Aktif</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Data Penjualan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
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
                                <td><strong>{{ $item->id_penjualan }}</strong></td>
                                <td>{{ $item->tanggal->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->user->nama_user ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $item->detailPenjualan->sum('jumlah') }}</span>
                                </td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($item->detailPenjualan->sum(fn($d) => $d->jumlah * $d->harga), 0, ',', '.') }}</strong>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada data penjualan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($penjualan->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $penjualan->firstItem() }} sampai {{ $penjualan->lastItem() }} dari {{ $penjualan->total() }} data
                    </div>
                    <div>
                        {{ $penjualan->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail -->
@foreach($penjualan as $item)
<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel{{ $item->id }}">
                    <i class="fas fa-receipt me-2"></i>Detail Penjualan {{ $item->id_penjualan }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Informasi Transaksi</h6>
                                <p class="mb-1"><strong>ID Penjualan:</strong> {{ $item->id_penjualan }}</p>
                                <p class="mb-0"><strong>Tanggal:</strong> {{ $item->tanggal->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-user me-2"></i>Kasir</h6>
                                <p class="mb-0"><strong>Nama:</strong> {{ $item->user->nama_user ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <h6><i class="fas fa-shopping-cart me-2"></i>Detail Barang</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->detailPenjualan as $detail)
                            <tr>
                                <td><code>{{ $detail->kode_barang }}</code></td>
                                <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $detail->jumlah }}</span>
                                </td>
                                <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <th colspan="4" class="text-end">Total Keseluruhan:</th>
                                <th class="text-end">
                                    <strong>Rp {{ number_format($item->detailPenjualan->sum(fn($d) => $d->jumlah * $d->harga), 0, ',', '.') }}</strong>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
