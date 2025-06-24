{{-- resources/views/pembelian/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMBELIAN BARANG</h1>
        <p>Periode: {{ request('tanggal_dari') ? date('d/m/Y', strtotime(request('tanggal_dari'))) : 'Semua' }}
           s/d {{ request('tanggal_sampai') ? date('d/m/Y', strtotime(request('tanggal_sampai'))) : 'Semua' }}</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Filter:</strong></p>
        <p>Supplier: {{ request('supplier') ? $pembelian->first()->supplier->nama_supplier ?? 'Semua' : 'Semua' }}</p>
        <p>Barang: {{ request('barang') ? $pembelian->first()->detailPembelian->first()->barang->nama_barang ?? 'Semua' : 'Semua' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">ID Pembelian</th>
                <th width="12%">Tanggal</th>
                <th width="20%">Supplier</th>
                <th width="15%">User</th>
                <th width="10%">Total Item</th>
                <th width="23%">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalKeseluruhan = 0;
                $totalItemKeseluruhan = 0;
            @endphp

            @foreach($pembelian as $key => $item)
                @php
                    $totalItem = $item->detailPembelian->sum('jumlah');
                    $totalHarga = $item->detailPembelian->sum(function($detail) {
                        return $detail->jumlah * $detail->harga;
                    });
                    $totalKeseluruhan += $totalHarga;
                    $totalItemKeseluruhan += $totalItem;
                @endphp

                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->id_pembelian }}</td>
                    <td class="text-center">{{ $item->tanggal ? date('d/m/Y', strtotime($item->tanggal)) : '-' }}</td>
                    <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                    <td>{{ $item->user->nama_user ?? '-' }}</td>
                    <td class="text-center">{{ $totalItem }}</td>
                    <td class="text-right">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="5" class="text-center"><strong>TOTAL KESELURUHAN</strong></td>
                <td class="text-center"><strong>{{ number_format($totalItemKeseluruhan) }}</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- Detail Pembelian --}}
    @if($pembelian->count() > 0)
        <div class="page-break"></div>
        <h2>DETAIL PEMBELIAN</h2>

        @foreach($pembelian as $item)
            <h3>{{ $item->id_pembelian }} - {{ $item->supplier->nama_supplier ?? '-' }}
                ({{ $item->tanggal ? date('d/m/Y', strtotime($item->tanggal)) : '-' }})</h3>

            <table style="margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode Barang</th>
                        <th width="35%">Nama Barang</th>
                        <th width="10%">Jumlah</th>
                        <th width="17.5%">Harga Satuan</th>
                        <th width="17.5%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->detailPembelian as $key => $detail)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $detail->kode_barang }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td class="text-center">{{ $detail->jumlah }}</td>
                            <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4" class="text-center"><strong>TOTAL</strong></td>
                        <td class="text-center"><strong>{{ $item->detailPembelian->sum('jumlah') }}</strong></td>
                        <td class="text-right">
                            <strong>Rp {{ number_format($item->detailPembelian->sum(function($detail) {
                                return $detail->jumlah * $detail->harga;
                            }), 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endif

    <div class="footer">
        <p>{{ date('d F Y') }}</p>
        <br><br><br>
        <p>_____________________</p>
        <p>Manager</p>
    </div>
</body>
</html>