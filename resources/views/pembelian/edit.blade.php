{{-- resources/views/pembelian/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Pembelian - {{ $pembelian->id_pembelian }}</h4>
    <form method="POST" action="{{ route('pembelian.update', $pembelian->id_pembelian) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Supplier</label>
            <select name="id_supplier" class="form-control" required>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id_supplier }}" {{ $pembelian->id_supplier == $supplier->id_supplier ? 'selected' : '' }}>
                        {{ $supplier->nama_supplier }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d', strtotime($pembelian->tanggal)) }}" required>
        </div>

        <h5>Detail Pembelian</h5>
        @foreach($pembelian->detail as $i => $detail)
        <div class="row mb-2">
            <div class="col-md-4">
                <input type="text" name="details[{{ $i }}][kode_barang]" value="{{ $detail->kode_barang }}" class="form-control" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="details[{{ $i }}][jumlah]" value="{{ $detail->jumlah }}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="details[{{ $i }}][harga]" value="{{ $detail->harga }}" class="form-control" required>
            </div>
        </div>
        @endforeach

        <button type="submit" class="btn btn-success mt-3">Update</button>
    </form>
</div>
@endsection
