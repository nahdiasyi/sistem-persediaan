{{-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #7f8c8d;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            width: 120px;
            font-weight: bold;
        }

        .info-value {
            flex: 1;
        }

        .filter-section {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .filter-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .stats-section {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            background-color: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #2980b9;
        }

        .stat-label {
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #95a5a6;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #7f8c8d;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN BARANG</h1>
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
    </div>

    <!-- Filter Information -->
    @if($filter['nama_barang'] || $filter['merek'] || $filter['kategori'] || $filter['tanggal_dari'] || $filter['tanggal_sampai'])
    <div class="filter-section">
        <div class="filter-title">Filter Laporan:</div>
        @if($filter['nama_barang'])
            <div class="info-row">
                <span class="info-label">Nama Barang:</span>
                <span class="info-value">{{ $filter['nama_barang'] }}</span>
            </div>
        @endif
        @if($filter['merek'])
            <div class="info-row">
                <span class="info-label">Merek:</span>
                <span class="info-value">{{ $filter['merek'] }}</span>
            </div>
        @endif
        @if($filter['kategori'])
            <div class="info-row">
                <span class="info-label">Kategori:</span>
                <span class="info-value">{{ $filter['kategori'] }}</span>
            </div>
        @endif
        @if($filter['tanggal_dari'])
            <div class="info-row">
                <span class="info-label">Tanggal Dari:</span>
                <span class="info-value">{{ date('d/m/Y', strtotime($filter['tanggal_dari'])) }}</span>
            </div>
        @endif
        @if($filter['tanggal_sampai'])
            <div class="info-row">
                <span class="info-label">Tanggal Sampai:</span>
                <span class="info-value">{{ date('d/m/Y', strtotime($filter['tanggal_sampai'])) }}</span>
            </div>
        @endif
    </div>
    @endif

    <!-- Statistics -->
    <div class="stats-section">
        <div class="stat-item">
            <div class="stat-number">{{ number_format($totalBarang) }}</div>
            <div class="stat-label">Total Barang</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">Rp{{ number_format($totalNilaiStok, 0, ',', '.') }}</div>
            <div class="stat-label">Total Nilai Stok (Harga Beli)</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">Rp{{ number_format($totalNilaiJual, 0, ',', '.') }}</div>
            <div class="stat-label">Total Nilai Stok (Harga Jual)</div>
        </div>
    </div>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Kode Barang</th>
                <th style="width: 20%;">Nama Barang</th>
                <th style="width: 12%;">Kategori</th>
                <th style="width: 10%;">Merek</th>
                <th style="width: 8%;">Satuan</th>
                <th style="width: 6%;">Stok</th>
                <th style="width: 12%;">Harga Beli</th>
                <th style="width: 12%;">Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $index => $barang)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $barang->merek ?? '-' }}</td>
                    <td class="text-center">{{ $barang->satuan ?? '-' }}</td>
                    <td class="text-center">{{ $barang->stok ?? 0 }}</td>
                    <td class="text-right">Rp{{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="no-data">Tidak ada data barang yang sesuai dengan filter</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem</p>
    </div>
</body>
</html> --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang</title>
    <style>
        body { font-family: Arial; font-size: 12px; padding: 20px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 11px; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 20px; font-size: 10px; text-align: right; }
    </style>
</head>
<body>

    <h1>LAPORAN BARANG</h1>
    <p>Dicetak pada: {{ $tanggal_cetak }}</p>

    @if($filter['nama_barang'] || $filter['merek'] || $filter['kategori'] || $filter['tanggal_dari'] || $filter['tanggal_sampai'])
        <strong>Filter:</strong><br>
        @if($filter['nama_barang']) Nama: {{ $filter['nama_barang'] }}<br> @endif
        @if($filter['merek']) Merek: {{ $filter['merek'] }}<br> @endif
        @if($filter['kategori']) Kategori: {{ $filter['kategori'] }}<br> @endif
        @if($filter['tanggal_dari']) Dari: {{ date('d/m/Y', strtotime($filter['tanggal_dari'])) }}<br> @endif
        @if($filter['tanggal_sampai']) Sampai: {{ date('d/m/Y', strtotime($filter['tanggal_sampai'])) }}<br> @endif
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Merek</th>
                <th>Satuan</th>
                <th>Stok</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $index => $barang)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $barang->merek ?? '-' }}</td>
                    <td>{{ $barang->Satuan ?? '-' }}</td>
                    <td class="text-center">{{ $barang->stok ?? 0 }}</td>
                    <td class="text-right">Rp{{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center"><i>Tidak ada data</i></td></tr>
            @endforelse
        </tbody>
    </table>

    <br>
    <strong>Total Barang:</strong> {{ number_format($totalBarang) }}<br>
    <strong>Total Nilai Stok (Beli):</strong> Rp{{ number_format($totalNilaiStok, 0, ',', '.') }}<br>
    <strong>Total Nilai Stok (Jual):</strong> Rp{{ number_format($totalNilaiJual, 0, ',', '.') }}

    <!-- Signature Area -->
    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td class="signature-left">
                    <div class="signature-title">Mengetahui</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">( Owner )</div>
                </td>
                <td class="signature-right">
                    <div class="signature-date">Yogyakarta, {{ date('d F Y') }}</div>
                    <div class="signature-title"> Back Office</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">( _________________ )</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem</p>
        <p>Halaman 1 dari 1</p>
    </div>

</body>
</html>
