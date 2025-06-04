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
            <ol class="breadcrumb">
                ...
            </ol>
        </div>
        

    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Data Barang</div>
        </div>
        <div class="ibox-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-striped table-bordered" id="example-table">
                <thead>
                    <tr>
                        <th>ID Barang</th>
                        <th>Nama</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Nilai</th>
                        <th>Satuan</th>
                        <th>Merek</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $barang)
                        <tr>
                            <td>{{ $barang->Id_Barang }}</td>
                            <td>{{ $barang->Nama_Barang }}</td>
                            <td>Rp{{ number_format($barang->Harga_Beli, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($barang->Harga_Jual, 0, ',', '.') }}</td>
                            <td>{{ $barang->Nilai }}</td>
                            <td>{{ $barang->Satuan }}</td>
                            <td>{{ $barang->Merek }}</td>
                            <td>{{ $barang->kategori->Nama_kategori ?? '-' }}</td>
                            <td>
                                <a href="{{ route('barang.edit', $barang->Id_Barang) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('barang.destroy', $barang->Id_Barang) }}" method="POST" style="display:inline-block;">
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
