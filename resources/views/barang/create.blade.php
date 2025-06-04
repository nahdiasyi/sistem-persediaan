<!-- create.blade.php -->
@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('content')
    <div class="page-heading">
        <h1 class="page-title">Tambah Barang</h1>
        ...
    </div>
    <div class="ibox">
        <div class="ibox-body">
            <form action="{{ route('barang.store') }}" method="POST">
                @include('barang.form')
            </form>
        </div>
    </div>
@endsection
