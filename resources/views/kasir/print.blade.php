<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan - {{ $penjualan->id_penjualan }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: white;
        }

        .nota {
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10px;
            margin: 2px 0;
        }

        .info {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .items {
            margin-bottom: 15px;
        }

        .item-header {
            border-bottom: 1px solid #000;
            padding: 5px 0;
            font-weight: bold;
        }

        .item-row {
            padding: 3px 0;
            border-bottom: 1px dotted #ccc;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
        }

        .total-section {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .grand-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }

        .print-btn {
            text-align: center;
            margin: 20px 0;
            page-break-inside: avoid;
        }

        .print-btn button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }

        .print-btn button:hover {
            background: #0056b3;
        }

        .print-btn button.secondary {
            background: #6c757d;
        }

        .print-btn button.secondary:hover {
            background: #545b62;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .nota {
                width: 100%;
                margin: 0;
                padding: 5px;
            }

            .print-btn {
                display: none;
            }

            .header h1 {
                font-size: 18px;
            }

            body {
                font-size: 11px;
            }
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="print-btn">
        <button onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Nota
        </button>
        <button class="secondary" onclick="window.close()">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <div class="nota">
        <!-- Header Toko -->
        <div class="header">
            <h1>The Best Aquarium & Pet Suply</h1>
            <p>Jl. A.M Sangaji No.33, Cokrodiningratan, Kec. Jetis, Kota Yogyakarta, Daerah Istimewa Yogyakarta</p>
            <p>Telp: 0822-6508-5445                                 b b bb b b b  n     </p>
        </div>

        <!-- Info Transaksi -->
        <div class="info">
            <div class="info-row">
                <span>No. Nota</span>
                <span>{{ $penjualan->id_penjualan }}</span>
            </div>
            <div class="info-row">
                <span>Tanggal</span>
                <span>{{ $penjualan->tanggal }}</span>
            </div>
            <div class="info-row">
                <span>Kasir</span>
                <span>{{ $penjualan->user->nama_user }}</span>
            </div>
        </div>

        <!-- Items -->
        <div class="items">
            @php $total = 0; @endphp
            @foreach($penjualan->detailPenjualan as $detail)
                @php
                    $subtotal = $detail->jumlah * $detail->harga;
                    $total += $subtotal;
                @endphp

                <div class="item-row">
                    <div class="item-name">{{ $detail->barang->nama_barang }}</div>
                    <div class="item-detail">
                        <span>{{ $detail->jumlah }} x Rp {{ number_format($detail->harga, 0, ',', '.') }}</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Total -->
        <div class="total-section">
            <div class="total-row">
                <span>Jumlah Item</span>
                <span>{{ $penjualan->detailPenjualan->sum('jumlah') }} pcs</span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>** TERIMA KASIH **</p>
            <p>Barang yang sudah dibeli</p>
            <p>tidak dapat dikembalikan</p>
            <br>
            <p>{{ date('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // }

        // Close window after printing
        window.onafterprint = function() {
            // window.close();
        }
    </script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
