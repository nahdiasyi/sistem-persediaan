{{-- resources/views/pembelian/index.blade.php
@extends('layouts.app')
@section('title', 'Pembelian')
@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Daftar @yield('title')</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item active">Daftar @yield('title')</li>
        </ol>
    </div>
    <div>
        <a href="{{ route('pembelian.create') }}" class="btn btn-primary">Tambah @yield('title')</a>
    </div>
</div>

<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">@yield('title')</div>
        </div>
        <div class="ibox-body">

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0"
                width="100%">
                <thead>
                    <tr>
                        <th>ID Pembelian</th>
                        <th>Supplier</th>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembelians as $pembelian)
                    <tr>
                        <td>{{ $pembelian->id_pembelian }}</td>
                        <td>{{ $pembelian->supplier->nama_supplier }}</td>
                        <td>{{ $pembelian->user->nama_user }}</td>
                        <td>{{ $pembelian->tanggal }}</td>
                        <td>
                            <button class="btn btn-sm btn-info" data-toggle="collapse"
                                data-target="#detail-{{ $pembelian->id_pembelian }}">Lihat</button>
                        </td>
                    </tr>
                    <tr id="detail-{{ $pembelian->id_pembelian }}" class="collapse">
                        <td colspan="5">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembelian->detailPembelian as $detail)
                                    <tr>
                                        <td>{{ $detail->kode_barang }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>{{ $detail->harga }}</td>
                                        <td>{{ $detail->jumlah * $detail->harga }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Pembelian</h5>
                        <a href="{{ route('pembelian.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Pembelian
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID Pembelian</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>User</th>
                                        <th>Total Item</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pembelian as $item)
                                                                <tr>
                                                                    <td>{{ $item->id_pembelian }}</td>
                                                                    <td>{{ $item->tanggal }}</td>
                                                                    <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                                                                    <td>{{ $item->user->nama_user ?? '-' }}</td>
                                                                    <td>{{ $item->detailPembelian->sum('jumlah') }}</td>
                                                                    <td>Rp {{ number_format($item->detailPembelian->sum(function ($detail) {
                                        return $detail->jumlah * $detail->harga; }), 0, ',', '.') }}</td>
                                                                    <td>
                                                                        <div class="btn-group" role="group">
                                                                            <a href="{{ route('pembelian.edit', $item->id_pembelian) }}"
                                                                                class="btn btn-sm btn-warning">
                                                                                <i class="fas fa-edit"></i> Edit
                                                                            </a>
                                                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                                                data-bs-target="#detailModal{{ $item->id_pembelian }}">
                                                                                <i class="fas fa-eye"></i> Detail
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>


                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data pembelian</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @foreach($pembelian as $item)
                                <div class="modal fade" id="detailModal{{ $item->id_pembelian }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Pembelian {{ $item->id_pembelian }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Tanggal:</strong> {{ $item->tanggal ?? '-' }}<br>
                                                        <strong>Supplier:</strong> {{ $item->supplier->nama_supplier ?? '-' }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>User:</strong> {{ $item->user->nama_user ?? '-' }}
                                                    </div>
                                                </div>
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Barang</th>
                                                            <th>Jumlah</th>
                                                            <th>Harga</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($item->detailPembelian as $detail)
                                                            <tr>
                                                                <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                                                                <td>{{ $detail->jumlah }}</td>
                                                                <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                                <td>Rp
                                                                    {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
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