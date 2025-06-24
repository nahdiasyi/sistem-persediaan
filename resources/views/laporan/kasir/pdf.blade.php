<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kasir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filter-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filter-info h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .statistik {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stat-card {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            width: 23%;
            border: 1px solid #ddd;
        }
        .stat-card h4 {
            margin: 0;
            color: #333;
            font-size: 16px;
        }
        .stat-card p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .detail-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .detail-header {
            background-color: #e9ecef;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        .detail-table {
            font-size: 10px;
        }
        .detail-table th, .detail-table td {
            padding: 4px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN KASIR</h1>
        <p>Sistem Kasir Toko</p>
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
    </div>

    <!-- Filter Info -->
    <div class="filter-info">
        <h3>Filter Laporan</h3>
        @if($tanggal_dari || $tanggal_sampai)
            <p><strong>Periode:</strong>
                {{ $tanggal_dari ? date('d/m/Y', strtotime($tanggal_dari)) : 'Awal' }} -
                {{ $tanggal_sampai ? date('d/m/Y', strtotime($tanggal_sampai)) : 'Akhir' }}
            </p>
        @endif
        @if($cari_barang)
            <p><strong>Filter Barang:</strong> {{ $cari_barang }}</p>
        @endif
        @if(!$tanggal_dari && !$tanggal_sampai && !$cari_barang)
            <p>Semua Data</p>
        @endif
    </div>

    <!-- Statistik -->
    <div class="statistik">
        <div class="stat-card">
            <h4>{{ number_format($statistik['total_transaksi']) }}</h4>
            <p>Total Transaksi</p>
        </div>
        <div class="stat-card">
            <h4>{{ number_format($statistik['total_item']) }}</h4>
            <p>Total Item</p>
        </div>
        <div class="stat-card">
            <h4>Rp {{ number_format($statistik['total_nilai'], 0, ',', '.') }}</h4>
            <p>Total Nilai</p>
        </div>
        <div class="stat-card">
            <h4>{{ number_format($statistik['user_aktif']) }}</h4>
            <p>User Aktif</p>
        </div>
    </div>

    <!-- Tabel Ringkasan -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Penjualan</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Total Item</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->id_penjualan }}</td>
                <td class="text-center">{{ $item->tanggal->format('d/m/Y H:i') }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td class="text-center">{{ $item->detailPenjualan->sum('jumlah') }}</td>
                <td class="text-right">Rp {{ number_format($item->detailPenjualan->sum(function($detail) { return $detail->jumlah * $detail->harga; }), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="4" class="text-center">TOTAL</td>
                <td class="text-center">{{ number_format($statistik['total_item']) }}</td>
                <td class="text-right">Rp {{ number_format($statistik['total_nilai'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Detail Transaksi -->
    @if($penjualan->count() > 0)
    <div class="page-break"></div>
    <h2>DETAIL TRANSAKSI</h2>

    @foreach($penjualan as $item)
    <div class="detail-section">
        <div class="detail-header">
            {{ $item->id_penjualan }} - {{ $item->tanggal->format('d/m/Y H:i') }} - Kasir: {{ $item->user->name ?? '-' }}
        </div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->detailPenjualan as $detail)
                <tr>
                    <td>{{ $detail->kode_barang }}</td>
                    <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td colspan="4" class="text-right">Total:</td>
                    <td class="text-right">Rp {{ number_format($item->detailPenjualan->sum(function($detail) { return $detail->jumlah * $detail->harga; }), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endforeach
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html>
