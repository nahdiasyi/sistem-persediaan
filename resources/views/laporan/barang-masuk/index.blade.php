<!-- resources/views/laporan/barang-masuk/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Barang Masuk</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" placeholder="Cari barang masuk...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-warehouse"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Persediaan Gudang</span>
                            <span class="info-box-number" id="totalGudang">{{ $totalGudang }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-store"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Persediaan Toko</span>
                            <span class="info-box-number" id="totalToko">{{ $totalToko }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right mb-3">
                <a href="{{ route('laporan.barang-masuk.pdf') }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="barangMasukTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Barang Masuk</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Admin</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangMasuk as $index => $item)
                            @foreach($item->detail as $detail)
                            <tr>
                                <td>{{ $loop->parent->iteration }}</td>
                                <td>{{ $item->Id_Barang_Masuk }}</td>
                                <td>{{ date('d/m/Y', strtotime($item->Tanggal)) }}</td>
                                <td>{{ $item->supplier->Nama_Supplier }}</td>
                                <td>{{ $item->admin->Nama_admin }}</td>
                                <td>{{ $detail->barang->Nama_Barang }}</td>
                                <td>{{ $detail->Jumlah }}</td>
                                <td>Rp {{ number_format($detail->Harga, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($detail->Jumlah * $detail->Harga, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .info-box {
        border: 1px solid #ddd;
        margin-bottom: 20px;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#searchBtn').click(function() {
        let search = $('#search').val();
        $.get("{{ route('laporan.barang-masuk.index') }}", { search: search }, function(data) {
            let html = '';
            data.barangMasuk.forEach(function(item, parentIndex) {
                item.detail.forEach(function(detail) {
                    html += `
                        <tr>
                            <td>${parentIndex + 1}</td>
                            <td>${item.Id_Barang_Masuk}</td>
                            <td>${new Date(item.Tanggal).toLocaleDateString('id-ID')}</td>
                            <td>${item.supplier.Nama_Supplier}</td>
                            <td>${item.admin.Nama_admin}</td>
                            <td>${detail.barang.Nama_Barang}</td>
                            <td>${detail.Jumlah}</td>
                            <td>Rp ${new Intl.NumberFormat('id-ID').format(detail.Harga)}</td>
                            <td>Rp ${new Intl.NumberFormat('id-ID').format(detail.Jumlah * detail.Harga)}</td>
                        </tr>
                    `;
                });
            });
            $('#barangMasukTable tbody').html(html);
            $('#totalGudang').text(data.totalGudang);
            $('#totalToko').text(data.totalToko);
        });
    });

    $('#search').on('keyup', function(e) {
        if(e.keyCode === 13) {
            $('#searchBtn').click();
        }
    });
});
</script>
@endpush
@endsection