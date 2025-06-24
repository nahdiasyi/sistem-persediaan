@extends('layouts.app')

@section('title', 'Edit Permintaan')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-edit me-2"></i> Edit Permintaan
        </div>

        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('permintaan.update', $permintaan->id_permintaan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id_supplier" class="form-label fw-bold">Supplier</label>
                    <select name="id_supplier" id="id_supplier" class="form-select">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id_supplier }}" {{ $supplier->id_supplier == $permintaan->id_supplier ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" value="{{ $permintaan->tanggal->format('Y-m-d') }}">
                </div>

                <hr class="my-4">

                <h5 class="fw-bold">Detail Barang</h5>
                <div id="barang-wrapper">
                    @foreach($permintaan->detailPermintaan as $index => $detail)
                    <div class="row g-3 mb-3 barang-item">
                        <div class="col-md-6">
                            <label class="form-label">Barang</label>
                            <select name="kode_barang[]" class="form-select">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->kode_barang }}" {{ $item->kode_barang == $detail->kode_barang ? 'selected' : '' }}>
                                        {{ $item->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah[]" class="form-control" min="1" value="{{ $detail->jumlah }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-barang w-100">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-secondary mb-3" id="tambah-barang">
                    <i class="fas fa-plus"></i> Tambah Barang
                </button>

                <div class="text-end">
                    <a href="{{ route('permintaan.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template untuk barang baru -->
<template id="barang-template">
    <div class="row g-3 mb-3 barang-item">
        <div class="col-md-6">
            <select name="kode_barang[]" class="form-select">
                <option value="">-- Pilih Barang --</option>
                @foreach($barang as $item)
                    <option value="{{ $item->kode_barang }}">{{ $item->nama_barang }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="number" name="jumlah[]" class="form-control" min="1" placeholder="Jumlah">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger remove-barang w-100">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>

<script>
    document.getElementById('tambah-barang').addEventListener('click', function () {
        const template = document.getElementById('barang-template').content.cloneNode(true);
        document.getElementById('barang-wrapper').appendChild(template);
    });

    document.getElementById('barang-wrapper').addEventListener('click', function (e) {
        if (e.target.closest('.remove-barang')) {
            e.target.closest('.barang-item').remove();
        }
    });
</script>
@endsection
