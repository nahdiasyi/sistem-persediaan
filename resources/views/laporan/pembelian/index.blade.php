@extends('layouts.app')

@section('title', 'Laporan Pembelian')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Laporan Pembelian</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item active">Laporan Pembelian</li>
            </ol>
        </div>
    </div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Filter Laporan</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.pembelian.index') }}">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="supplier" class="form-label">Supplier</label>
                                <select name="supplier" id="supplier" class="form-select">
                                    <option value="">Semua Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id_supplier }}" {{ request('supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                                            {{ $supplier->nama_supplier }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="barang" class="form-label">Barang</label>
                                <select name="barang" id="barang" class="form-select">
                                    <option value="">Semua Barang</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->kode_barang }}" {{ request('barang') == $barang->kode_barang ? 'selected' : '' }}>
                                            {{ $barang->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                                <input type="date" name="tanggal_dari" id="tanggal_dari" class="form-control"
                                       value="{{ request('tanggal_dari') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                                <input type="date" name="tanggal_sampai" id="tanggal_sampai" class="form-control"
                                       value="{{ request('tanggal_sampai') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('laporan.pembelian.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h2 class="mb-0">{{ $statistics['total_pembelian'] }}</h2>
                            <p class="mb-0">Total Pembelian</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h2 class="mb-0">{{ $statistics['total_item'] }}</h2>
                            <p class="mb-0">Total Item</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h2 class="mb-0">Rp{{ number_format($statistics['total_nilai'], 0, ',', '.') }}</h2>
                            <p class="mb-0">Total Nilai</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h2 class="mb-0">{{ $statistics['supplier_aktif'] }}</h2>
                            <p class="mb-0">Supplier Aktif</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Data Laporan Pembelian</h4>
                    <div>
                        <a href="{{ route('laporan.pembelian.export-pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                           class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>ID Pembelian</th>
                                    <th>Tanggal</th>
                                    <th>Supplier</th>
                                    <th>User</th>
                                    <th>Total Item</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembelian as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->id_pembelian }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                                    <td>{{ $item->user->nama_user ?? '-' }}</td>
                                    <td>{{ $item->detailPembelian->sum('jumlah') }}</td>
                                    <td>Rp{{ number_format($item->detailPembelian->sum(fn($d) => $d->jumlah * $d->harga), 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
