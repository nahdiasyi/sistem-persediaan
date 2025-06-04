@extends('layouts.app')

@section('title', 'User')

@section('content')

@push('styles')
    <link href="{{ asset('../vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
@endpush

<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Daftar User</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item active">Daftar User</li>
        </ol>
    </div>
    <div>
        <a href="{{ route('user.create') }}" class="btn btn-primary">Tambah User</a>
    </div>
</div>

<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Daftar User</div>
        </div>
        <div class="ibox-body">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                <thead>
                    <tr class="text-center">
                        <th>ID User</th>
                        <th>Nama User</th>
                        <th>Alamat</th>
                        <th>No Telp</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id_user }}</td>
                            <td>{{ $user->nama_user }}</td>
                            <td>{{ $user->alamat }}</td>
                            <td>{{ $user->no_telp }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>
                                <a href="{{ route('user.edit', $user->id_user) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                <form action="{{ route('user.destroy', $user->id_user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('../vendors/DataTables/datatables.min.js') }}" type="text/javascript"></script>
@endpush
