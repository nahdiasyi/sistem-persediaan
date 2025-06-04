<?php
// File: resources/views/retur/edit.blade.php
?>
@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Edit Retur</h3>
    <form action="{{ route('retur.update', $retur->id_retur) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Supplier</label>
            <select name="id_supplier" class="form-control">
                @foreach($supplier as $s)
                    <option value="{{ $s->id_supplier }}" {{ $retur->id_supplier == $s->id_supplier ? 'selected' : '' }}>{{ $s->nama_supplier }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Pembelian</label>
            <select name="id_pembelian" class="form-control">
                @foreach($pembelian as $p)
                    <option value="{{ $p->id_pembelian }}" {{ $retur->id_pembelian == $p->id_pembelian ? 'selected' : '' }}>{{ $p->id_pembelian }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>User</label>
            <select name="id_user" class="form-control">
                @foreach($users as $u)
                    <option value="{{ $u->id_user }}" {{ $retur->id_user == $u->id_user ? 'selected' : '' }}>{{ $u->nama_user }}</option>
                @endforeach
            </select>
        </div>

        <h5>Detail Barang</h5>
        <div id="detail-container">
            @foreach($retur->detailRetur as $i => $detail)
            <div class="card mb-2 p-2">
                <div class="row">
                    <div class="col">
                        <select name="details[{{ $i }}][kode_barang]" class="form-control">
                            @foreach($barang as $b)
                                <option value="{{ $b->kode_barang }}" {{ $detail->kode_barang == $b->kode_barang ? 'selected' : '' }}>{{ $b->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col"><input name="details[{{ $i }}][jumlah]" type="number" class="form-control" value="{{ $detail->jumlah }}"></div>
                    <div class="col"><input name="details[{{ $i }}][harga]" type="number" class="form-control" value="{{ $detail->harga }}"></div>
                    <div class="col"><input name="details[{{ $i }}][alasan]" type="text" class="form-control" value="{{ $detail->alasan }}"></div>
                </div>
            </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
