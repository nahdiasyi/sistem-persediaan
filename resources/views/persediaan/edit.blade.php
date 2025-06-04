@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2>Edit Persediaan</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('persediaan.update', $persediaan->Id_Barang) }}" method="POST">
                @csrf
                @method('PUT')
                
                <input type="number" name="Stok" value="{{ $persediaan->Stok }}" required>
                
                <div class="form-group">
                    <label>Barang</label>
                    <input type="text" class="form-control" value="{{ $persediaan->barang->Nama_Barang }}" readonly>
                    <input type="hidden" name="Id_Barang" value="{{ $persediaan->Id_Barang }}">
                </div>

                
                <div class="form-group">
                    <label>Alokasi</label>
                    <input type="text" class="form-control" value="{{ $persediaan->Alokasi }}" readonly>
                    <input type="hidden" name="Alokasi" value="{{ $persediaan->Alokasi }}">
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="Stok" class="form-control" min="0" value="{{ $persediaan->Stok }}" required>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('persediaan.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection