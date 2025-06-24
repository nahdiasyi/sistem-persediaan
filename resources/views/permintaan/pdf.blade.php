<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Permintaan {{ $permintaan->id_permintaan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .company-address {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            color: #333;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 8px 0;
            vertical-align: top;
        }

        .info-table .label {
            font-weight: bold;
            width: 120px;
            color: #333;
        }

        .info-table .colon {
            width: 20px;
        }

        .info-table .value {
            color: #555;
        }

        .detail-section {
            margin-top: 30px;
        }

        .detail-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .detail-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
            text-align: center;
        }

        .detail-table td {
            color: #555;
        }

        .detail-table .text-center {
            text-align: center;
        }

        .detail-table .text-right {
            text-align: right;
        }

        .summary-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px;
            border-top: 1px solid #ddd;
        }

        .summary-table .summary-label {
            font-weight: bold;
            text-align: right;
            width: 80%;
            color: #333;
        }

        .summary-table .summary-value {
            font-weight: bold;
            text-align: right;
            color: #333;
        }

        .footer {
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature-section {
            width: 100%;
            margin-top: 40px;
        }

        .signature-box {
            width: 45%;
            display: inline-block;
            text-align: center;
            vertical-align: top;
        }

        .signature-box.left {
            float: left;
        }

        .signature-box.right {
            float: right;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 60px;
            color: #333;
        }

        .signature-name {
            border-top: 1px solid #333;
            padding-top: 5px;
            font-weight: bold;
            color: #333;
        }

        .page-break {
            page-break-after: always;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0,0,0,0.05);
            z-index: -1;
            font-weight: bold;
        }

        .notes {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .notes-content {
            font-size: 11px;
            color: #666;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">PERMINTAAN</div>

    <!-- Header -->
    <div class="header">
        <div class="company-name">The Best Aquarium & Pet Suply</div>
        <div class="company-address">
            Jl. AM Sangaji<br>
            Telp: (021) 1234-5678 | Email: th3best@perusahaan.com
        </div>
        <div class="document-title">SURAT PERMINTAAN BARANG</div>
    </div>

    <!-- Informasi Permintaan -->
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="label">No. Permintaan</td>
                <td class="colon">:</td>
                <td class="value">{{ $permintaan->id_permintaan }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td class="colon">:</td>
                <td class="value">{{ $permintaan->tanggal->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Supplier</td>
                <td class="colon">:</td>
                <td class="value">{{ $permintaan->supplier->nama_supplier ?? 'Supplier tidak ditemukan' }}</td>
            </tr>
            @if(isset($permintaan->supplier->alamat))
            <tr>
                <td class="label">Alamat Supplier</td>
                <td class="colon">:</td>
                <td class="value">{{ $permintaan->supplier->alamat }}</td>
            </tr>
            @endif
            @if(isset($permintaan->supplier->telepon))
            <tr>
                <td class="label">Telepon Supplier</td>
                <td class="colon">:</td>
                <td class="value">{{ $permintaan->supplier->telepon }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Dibuat Tanggal</td>
                <td class="colon">:</td>
                <td class="value">{{ $permintaan->created_at->format('d F Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <!-- Detail Barang -->
    <div class="detail-section">
        <div class="detail-title">DETAIL BARANG YANG DIMINTA</div>

        <table class="detail-table">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th width="18%">Kode Barang</th>
                    <th width="45%">Nama Barang</th>
                    <th width="15%">Satuan</th>
                    <th width="14%">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $totalJumlah = 0; @endphp
                @forelse($permintaan->detailPermintaan as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $detail->kode_barang }}</td>
                        <td>{{ $detail->barang->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                        <td class="text-center">{{ $detail->barang->Satuan ?? '-' }}</td>
                        <td class="text-right">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalJumlah += $detail->jumlah; @endphp
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada detail barang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($permintaan->detailPermintaan->count() > 0)
            <table class="summary-table">
                <tr>
                    <td class="summary-label">Total Item:</td>
                    <td class="summary-value">{{ $permintaan->detailPermintaan->count() }} item</td>
                </tr>
                <tr>
                    <td class="summary-label">Total Jumlah:</td>
                    <td class="summary-value">{{ number_format($totalJumlah, 0, ',', '.') }}</td>
                </tr>
            </table>
        @endif
    </div>

    <!-- Catatan -->
    {{-- <div class="notes">
        <div class="notes-title">CATATAN:</div>
        <div class="notes-content">
            1. Barang yang diminta mohon segera dikirim sesuai dengan spesifikasi yang tertera<br>
            2. Harap konfirmasi ketersediaan barang sebelum pengiriman<br>
            3. Jika ada pertanyaan, silakan hubungi bagian purchasing<br>
            4. Surat permintaan ini berlaku selama 30 hari sejak tanggal diterbitkan
        </div>
    </div> --}}

    <!-- Tanda Tangan -->
    <div class="footer">
        <div class="signature-section">
            <div class="signature-box left">
                <div class="signature-title">Disetujui Oleh</div>
                <div class="signature-name">
                    Owner<br>
                    (..............................)
                </div>
            </div>

            <div class="signature-box right">
                <div class="signature-title">Dibuat Oleh</div>
                <div class="signature-name">
                    Back Ofiice<br>
                    (..............................)
                </div>
            </div>
        </div>
    </div>

    <!-- Footer dengan informasi tambahan -->
    <div style="clear: both; margin-top: 100px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px;">
        Dokumen ini dibuat secara otomatis oleh sistem pada {{ now()->format('d F Y H:i:s') }}
    </div>
</body>
</html>
