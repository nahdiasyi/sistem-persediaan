@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form action="{{ route('laporan.persediaan') }}" method="GET" class="form-inline">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama barang..." value="{{ $search ?? '' }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('laporan.persediaan', ['pdf' => true]) }}" class="btn btn-danger me-2">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Alokasi</th>
                            <th>Total Stok Toko</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($persediaan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->Nama_Barang }}</td>
                            <td>{{ $item->Alokasi }}</td>
                            <td>{{ $item->Total_Stok_Toko }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .card-header { 
            display: none; 
        }
        .container {
            width: 100%;
            padding: 0;
            margin: 0;
        }
    }
</style>
@endpush
@endsection