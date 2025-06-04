<?php
// File: resources/views/retur/create.blade.php
?>
@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Tambah Retur</h3>
    <form action="{{ route('retur.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Supplier</label>
            <select name="id_supplier" class="form-control">
                @foreach($supplier as $s)
                    <option value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Pembelian</label>
            <select name="id_pembelian" class="form-control">
                @foreach($pembelian as $p)
                    <option value="{{ $p->id_pembelian }}">{{ $p->id_pembelian }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>User</label>
            <select name="id_user" class="form-control">
                @foreach($users as $u)
                    <option value="{{ $u->id_user }}">{{ $u->nama_user }}</option>
                @endforeach
            </select>
        </div>

        <h5>Detail Barang</h5>
        <div id="detail-container"></div>
        <button type="button" onclick="addDetail()" class="btn btn-secondary mb-3">Tambah Barang</button>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
<script>
    let detailIndex = 0;
    function addDetail() {
        const container = document.getElementById('detail-container');
        const html = `
        <div class="card mb-2 p-2">
            <div class="row">
                <div class="col">
                    <select name="details[${detailIndex}][kode_barang]" class="form-control">
                        @foreach($barang as $b)
                            <option value="{{ $b->kode_barang }}">{{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col"><input name="details[${detailIndex}][jumlah]" type="number" class="form-control" placeholder="Jumlah"></div>
                <div class="col"><input name="details[${detailIndex}][harga]" type="number" class="form-control" placeholder="Harga"></div>
                <div class="col"><input name="details[${detailIndex}][alasan]" type="text" class="form-control" placeholder="Alasan"></div>
            </div>
        </div>`;
        container.insertAdjacentHTML('beforeend', html);
        detailIndex++;
    }
</script>
@endsection