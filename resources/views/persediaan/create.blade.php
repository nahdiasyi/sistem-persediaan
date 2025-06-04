@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2>Tambah Persediaan</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('persediaan.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Barang</label>
                    <select name="Id_Barang" class="form-control" required>
                        <option value="">Pilih Barang</option>
                        @foreach($barang as $b)
                            <option value="{{ $b->Id_Barang }}">{{ $b->Nama_Barang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Alokasi</label>
                    <select name="Alokasi" class="form-control" required>
                        <option value="Gudang">Gudang</option>
                        <option value="Toko">Toko</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="Stok" class="form-control" min="0" required>
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
