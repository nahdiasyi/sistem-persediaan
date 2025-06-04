<!-- resources/views/supplier/create.blade.php -->
@extends('layouts.app')
@section('title', 'Tambah Supplier')
@section('content')
    <div class="page-heading">
        <h1 class="page-title">Tambah Supplier</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('supplier.index') }}">Supplier</a>
            </li>
            <li class="breadcrumb-item">Tambah Supplier</li>
        </ol>
    </div>
    <div class="ibox mt-4">
        <div class="ibox-head">
            <div class="ibox-title">Tambah Supplier</div>
            <div class="ibox-tools">
                <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
            </div>
        </div>
        <div class="ibox-body">
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_supplier">Nama Supplier</label>
                    <input type="text" class="form-control" name="nama_supplier" required>
                </div>
                <div class="form-group">
                    <label for="no_telp">No Telp</label>
                    <input type="text" class="form-control" name="no_telp" required>
                </div>
                <div class="form-group">
                    <label for="alamat_supplier">Alamat Supplier</label>
                    <input type="text" class="form-control" name="alamat_supplier" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
