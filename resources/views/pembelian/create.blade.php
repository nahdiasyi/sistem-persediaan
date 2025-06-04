{{-- resources/views/pembelian/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Pembelian</h4>
    <form method="POST" action="{{ route('pembelian.store') }}">
        @csrf
        <div class="form-group">
            <label>Supplier</label>
            <select name="id_supplier" class="form-control" required>
                <option value="">Pilih Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <hr>
        <h5>Detail Pembelian</h5>
        <div id="detail-container"></div>

        <button type="button" class="btn btn-secondary" onclick="tambahDetail()">Tambah Barang</button>

        <button type="submit" class="btn btn-success mt-3">Simpan</button>
    </form>
</div>

@push('scripts')
<script>
let index = 0;

function tambahDetail() {
    $('#detail-container').append(`
        <div class="row mb-2" id="row-${index}">
            <div class="col-md-4">
                <input type="text" name="details[${index}][kode_barang]" class="form-control" placeholder="Kode Barang" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="details[${index}][jumlah]" class="form-control" placeholder="Jumlah" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="details[${index}][harga]" class="form-control" placeholder="Harga" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="hapusDetail(${index})">Hapus</button>
            </div>
        </div>
    `);
    index++;
}

function hapusDetail(id) {
    $(`#row-${id}`).remove();
}
</script>
@endpush
@endsection
