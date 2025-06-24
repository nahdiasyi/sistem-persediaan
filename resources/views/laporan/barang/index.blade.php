@extends('layouts.app')
@section('title', 'Laporan Barang')
@section('content')
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
        </style>
    @endpush

    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Laporan Barang</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item active">Laporan Barang</li>
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
                <form method="GET" action="{{ route('laporan.barang.index') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama_barang">Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                       value="{{ request('nama_barang') }}" placeholder="Cari nama barang...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="merek">Merek</label>
                                <input type="text" class="form-control" id="merek" name="merek"
                                       value="{{ request('merek') }}" placeholder="Cari merek...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_kategori">Kategori</label>
                                <select class="form-control select2" id="id_kategori" name="id_kategori">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id_kategori }}"
                                                {{ request('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="la la-search"></i> Filter
                                    </button>
                                    <a href="{{ route('laporan.barang.index') }}" class="btn btn-secondary mr-2">
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
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-item">
                        <div class="stats-number">{{ number_format($totalBarang) }}</div>
                        <div class="stats-label">Total Barang</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-item">
                        <div class="stats-number">Rp{{ number_format($totalNilaiStok, 0, ',', '.') }}</div>
                        <div class="stats-label">Total Nilai Stok (Harga Beli)</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-item">
                        <div class="stats-number">Rp{{ number_format($totalNilaiJual, 0, ',', '.') }}</div>
                        <div class="stats-label">Total Nilai Stok (Harga Jual)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Data Barang</div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Merek</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Nilai Stok (Beli)</th>
                            <th>Nilai Stok (Jual)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangs as $index => $barang)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $barang->kode_barang }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $barang->merek ?? '-' }}</td>
                                <td>{{ $barang->Satuan ?? '-' }}</td>
                                <td class="text-center">{{ $barang->stok ?? 0 }}</td>
                                <td class="text-right">Rp{{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-right">Rp{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-right">Rp{{ number_format($barang->harga_beli * ($barang->stok ?? 0), 0, ',', '.') }}</td>
                                <td class="text-right">Rp{{ number_format($barang->harga_jual * ($barang->stok ?? 0), 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data barang</td>
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
                    width: '100%'
                });
            });

                            function exportPdf() {
                    const params = new URLSearchParams();

                    const namaBarang = document.getElementById('nama_barang').value;
                    const merek = document.getElementById('merek').value;
                    const kategori = document.getElementById('id_kategori').value;

                    if (namaBarang) params.append('nama_barang', namaBarang);
                    if (merek) params.append('merek', merek);
                    if (kategori) params.append('id_kategori', kategori);

                    const url = '{{ route("laporan.barang.pdf") }}' + (params.toString() ? '?' + params.toString() : '');
                    window.open(url, '_blank');
                }

        </script>
    @endpush
@endsection
