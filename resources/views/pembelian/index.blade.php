{{-- resources/views/pembelian/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">



    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Pembelian</h4>
        <a href="{{ route('pembelian.create') }}" class="btn btn-primary">Tambah Pembelian</a>
    </div>

    <table class="table table-bordered">
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
                    <button class="btn btn-sm btn-info" data-toggle="collapse" data-target="#detail-{{ $pembelian->id_pembelian }}">Lihat</button>
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
@endsection
