@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@push('styles')
<link href="{{ asset('vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
<style>
    .filter-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .stats-card.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .stats-card.info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    }
    .stats-card.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    .stats-card.warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }
    .stats-item {
        text-align: center;
    }
    .stats-number {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .stats-label {
        font-size: 12px;
        opacity: 0.9;
    }
    .select2-container {
        width: 100% !important;
    }
</style>
@endpush

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Laporan Penjualan</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('penjualan.index') }}">Penjualan</a>
            </li>
            <li class="breadcrumb-item active">Laporan Penjualan</li>
        </ol>
    </div>
</div>

<div class="page-content fade-in-up">
    <!-- Filter Section -->
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Filter Laporan</div>
        </div>
        <div class="ibox-body">
            <form method="GET" action="{{ route('laporan.penjualan') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="barang">Barang</label>
                            <select name="barang" class="form-control select2" id="barang">
                                <option value="">Semua Barang</option>
                                @foreach($barangList as $b)
                                <option value="{{ $b->kode_barang }}" {{ request('barang') == $b->kode_barang ? 'selected' : '' }}>
                                    {{ $b->nama_barang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dari">Dari Tanggal</label>
                            <input type="date" name="dari" value="{{ request('dari') }}" class="form-control" id="dari">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sampai">Sampai Tanggal</label>
                            <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control" id="sampai">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="la la-search"></i> Filter
                                </button>
                                <a href="{{ route('laporan.penjualan') }}" class="btn btn-secondary mr-2">
                                    <i class="la la-refresh"></i> Reset
                                </a>
                                <button type="button" class="btn btn-success" onclick="exportPdf()">
                                    <i class="la la-file-pdf-o"></i> PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card primary">
                <div class="stats-item">
                    <div class="stats-number">{{ number_format($totalTransaksi) }}</div>
                    <div class="stats-label">Total Transaksi</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card info">
                <div class="stats-item">
                    <div class="stats-number">{{ number_format($totalItem) }}</div>
                    <div class="stats-label">Total Item</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card success">
                <div class="stats-item">
                    <div class="stats-number">Rp{{ number_format($totalNilai, 0, ',', '.') }}</div>
                    <div class="stats-label">Total Nilai</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <div class="stats-item">
                    <div class="stats-number">{{ number_format($userAktif) }}</div>
                    <div class="stats-label">User Aktif</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Data Laporan Penjualan</div>
        </div>
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Penjualan</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->id_penjualan }}</td>
                        <td>{{ $item->tanggal ? date('d/m/Y', strtotime($item->tanggal)) : '-' }}</td>
                        <td>{{ $item->user->nama_user ?? '-' }}</td>
                        <td class="text-center">{{ $item->detailPenjualan->sum('jumlah') }}</td>
                        <td class="text-right">Rp{{ number_format($item->detailPenjualan->sum(function($detail) { return $detail->jumlah * $detail->harga; }), 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" data-toggle="collapse"
                                data-target="#detail-{{ $item->id_penjualan }}">
                                <i class="la la-eye"></i> Lihat
                            </button>
                        </td>
                    </tr>
                    <tr id="detail-{{ $item->id_penjualan }}" class="collapse">
                        <td colspan="7">
                            <div class="p-3">
                                <h6>Detail Barang:</h6>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Harga Satuan</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->detailPenjualan as $detail)
                                        <tr>
                                            <td>{{ $detail->kode_barang }}</td>
                                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                                            <td class="text-center">{{ $detail->jumlah }}</td>
                                            <td class="text-right">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                            <td class="text-right">Rp{{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data penjualan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('vendors/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('vendors/select2/dist/js/select2.min.js') }}"></script>
<script>
$(function() {
    // Initialize DataTable
    $('#example-table').DataTable({
        pageLength: 25,
        responsive: true,
        order: [[ 2, "desc" ]],
        dom: '<"top"i>rt<"bottom"flp><"clear">',
        language: {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ entri",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
            "zeroRecords": "Tidak ditemukan data yang sesuai",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: "Pilih...",
        allowClear: true
    });
});

function exportPdf() {
    // Get current filter values
    const params = new URLSearchParams();

    const barang = document.getElementById('barang').value;
    const dari = document.getElementById('dari').value;
    const sampai = document.getElementById('sampai').value;

    if (barang) params.append('barang', barang);
    if (dari) params.append('dari', dari);
    if (sampai) params.append('sampai', sampai);

    const url = '{{ route("laporan.penjualan.pdf") }}' + (params.toString() ? '?' + params.toString() : '');
    window.open(url, '_blank');
}
</script>
@endpush
@endsection
