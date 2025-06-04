<!-- resources/views/laporan/barang-masuk/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Barang Masuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
        }
        h1 {
            text-align: center;
        }
        .info-box {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Laporan Barang Masuk</h1>
    
    <div class="info-box">
        <p><strong>Total Persediaan Gudang:</strong> {{ $totalGudang }}</p>
        <p><strong>Total Persediaan Toko:</strong> {{ $totalToko }}</p>
    </div>

    <table>
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
</body>
</html>