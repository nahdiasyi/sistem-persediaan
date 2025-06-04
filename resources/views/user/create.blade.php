@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="page-heading">
    <h1 class="page-title">Tambah User</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('user.index') }}">User</a>
        </li>
        <li class="breadcrumb-item">Tambah User</li>
    </ol>
</div>

<div class="ibox mt-4">
    <div class="ibox-head">
        <div class="ibox-title">Form Tambah User</div>
        <div class="ibox-tools">
            <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
        </div>
    </div>

    <div class="ibox-body">
        <form action="{{ route('user.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="id_user">ID User</label>
                    <input type="text" name="id_user" class="form-control" value="{{ $newId }}" readonly>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="nama_user">Nama User</label>
                    <input type="text" class="form-control" name="nama_user" required>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="no_telp">No Telp</label>
                    <input type="text" class="form-control" name="no_telp" required>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="role">Role</label>
                    <select class="form-control" name="role" required>
                        <option value="back office">Back Office</option>
                        <option value="kasir">Kasir</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control" name="alamat" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
