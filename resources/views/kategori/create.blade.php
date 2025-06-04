<!-- resources/views/kategori/create.blade.php -->
@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('content')
    <div class="page-heading">
        <h1 class="page-title">Tambah Kategori</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kategori.index') }}">Kategori</i></a>
            </li>
            <li class="breadcrumb-item">Tambah Kategori</li>
        </ol>
    </div>
    <div class="ibox mt-4">
        <div class="ibox-head">
            <div class="ibox-title">Tambah Kategori</div>
            <div class="ibox-tools">
                <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
            </div>
        </div>
        <div class="ibox-body">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
<input type="text" class="form-control" name="nama_kategori" required>
 </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
