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
        .signature-container {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            border: none;
        }
        .signature-table td {
            border: none;
            padding: 20px;
            vertical-align: top;
            width: 50%;
        }
        .signature-left {
            text-align: left;
        }
        .signature-right {
            text-align: right;
        }
        .signature-date {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .signature-title {
            margin-bottom: 60px;
            font-weight: bold;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin-bottom: 5px;
        }
        .signature-left .signature-line {
            margin-left: 0;
        }
        .signature-right .signature-line {
            margin-left: auto;
            margin-right: 0;
        }
        .signature-name {
            font-weight: bold;
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
                <td>{{ $item->user->nama_user ?? '-' }}</td>
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
                    <div class="signature-title"> Kasir</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">( _________________ )</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html>
