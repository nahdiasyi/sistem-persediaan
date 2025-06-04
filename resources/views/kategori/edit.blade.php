@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('content')
    <div class="page-heading">
        <h1 class="page-title">Edit Kategori</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kategori.index') }}">Kategori</a>
            </li>
            <li class="breadcrumb-item">Edit Kategori</li>
        </ol>
    </div>
    <div class="ibox mt-4">
        <div class="ibox-head">
            <div class="ibox-title">Edit Kategori</div>
            <div class="ibox-tools">
                <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
            </div>
        </div>
        <div class="ibox-body">
            <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" class="form-control" name="nama_kategori" id="nama_kategori"
                        value="{{ $kategori->nama_kategori }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
