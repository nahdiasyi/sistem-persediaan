<!-- edit.blade.php -->
@extends('layouts.app')
@section('title', 'Edit Barang')
@section('content')
    <div class="page-heading">
        <h1 class="page-title">Edit Barang</h1>
        ...
    </div>
    <div class="ibox">
        <div class="ibox-body">
            <form action="{{ route('barang.update', $barang->Id_Barang) }}" method="POST">
                @include('barang.form', ['barang' => $barang])
            </form>
        </div>
    </div>
@endsection
