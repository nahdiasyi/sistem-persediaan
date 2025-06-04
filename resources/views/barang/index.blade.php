<!-- index.blade.php -->
@extends('layouts.app')
@section('title', 'Daftar Barang')
@section('content')
    @push('styles')
        <link href="{{ asset('vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
    @endpush

    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Daftar Barang</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item active">Daftar Barang</li>
            </ol>
        </div>
        <div>
            <a href="{{ route('barang.create') }}" class="btn btn-primary">Tambah Barang</a>
        </div>
        </div>

        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Daftar Barang</div>
                </div>
                <div class="ibox-body">

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID Barang</th>
                        <th>Nama</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Nilai</th>
                        <th>Satuan</th>
                        <th>Merek</th>
                        <th>Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $barang)
                        <tr>
                            <td>{{ $barang->kode_barang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>Rp{{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $barang->nilai }}</td>
                            <td>{{ $barang->satuan }}</td>
                            <td>{{ $barang->merek }}</td>
                            <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                            <td>
                                <a href="{{ route('barang.edit', $barang->kode_barang) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('barang.destroy', $barang->kode_barang) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus barang ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
