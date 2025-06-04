<!-- resources/views/supplier/index.blade.php -->
@extends('layouts.app')
@section('title', 'Supplier')
@section('content')

@push('styles')
    <link href="{{ asset('../vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
@endpush

<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Daftar Supplier</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item active">Daftar Supplier</li>
        </ol>
    </div>
    <div>
        <a href="{{ route('supplier.create') }}" class="btn btn-primary">Tambah Supplier</a>
    </div>
</div>

<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Daftar Supplier</div>
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
                        <th>ID Supplier</th>
                        <th>Nama Supplier</th>
                        <th>Alamat Supplier</th>
                        <th>No Telp</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->id_supplier }}</td>
                            <td>{{ $supplier->nama_supplier }}</td>
                            <td>{{ $supplier->alamat_supplier }}</td>
                            <td>{{ $supplier->no_telp }}</td>
                            <td>
                                <a href="{{ route('supplier.edit', $supplier->id_supplier) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                <form action="{{ route('supplier.destroy', $supplier->id_supplier) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
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
