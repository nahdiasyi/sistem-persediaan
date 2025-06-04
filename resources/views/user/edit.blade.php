@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="page-heading">
    <h1 class="page-title">Edit User</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('user.index') }}">User</a>
        </li>
        <li class="breadcrumb-item">Edit User</li>
    </ol>
</div>

<div class="ibox mt-4">
    <div class="ibox-head">
        <div class="ibox-title">Edit Data User</div>
        <div class="ibox-tools">
            <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
        </div>
    </div>

    <div class="ibox-body">
        <form action="{{ route('user.update', $user->id_user) }}" method="POST">
            @csrf
            @method('PUT')
        

            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="id_user">ID User</label>
                    <input type="text" class="form-control" name="id_user" value="{{ $user->id_user }}" readonly>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="nama_user">Nama User</label>
                    <input type="text" class="form-control" name="nama_user" value="{{ $user->nama_user }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" value="{{ $user->username }}" required>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password">
                    <span class="help-block">Kosongkan jika tidak ingin mengubah password</span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="no_telp">No Telp</label>
                    <input type="text" class="form-control" name="no_telp" value="{{ $user->no_telp }}" required>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="role">Role</label>
                    <select class="form-control" name="role" required>
                        <option value="back office" {{ $user->role == 'back office' ? 'selected' : '' }}>Back Office</option>
                        <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control" name="alamat" value="{{ $user->alamat }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
