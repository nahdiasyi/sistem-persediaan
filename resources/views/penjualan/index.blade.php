@extends('layouts.app')
@section('content')
<div class="container">
    <h4>Daftar Penjualan</h4>
    <a href="{{ route('penjualan.create') }}" class="btn btn-primary mb-3">Tambah Penjualan</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Penjualan</th>
                <th>User</th>
                <th>Tanggal</th>
                <th>Detail</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $p)
            <tr>
                <td>{{ $p->id_penjualan }}</td>
                <td>{{ $p->user->nama ?? '-' }}</td>
                <td>{{ $p->tanggal }}</td>
                <td>
                    <ul>
                        @foreach($p->detailPenjualan as $d)
                        <li>{{ $d->barang->nama_barang ?? '-' }} - {{ $d->jumlah }} x {{ number_format($d->harga) }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <a href="{{ route('penjualan.edit', $p->id_penjualan) }}" class="btn btn-warning btn-sm">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
