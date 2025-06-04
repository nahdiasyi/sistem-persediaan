<!DOCTYPE html>
<html>
<head>
    <title>Laporan Persediaan Toko</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
    </style>
</head>
<body>
    <h1>Laporan Persediaan Toko</h1>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Alokasi</th>
                <th>Total Stok Toko</th>
            </tr>
        </thead>
        <tbody>
            @foreach($persediaan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->Nama_Barang }}</td>
                <td>{{ $item->Alokasi }}</td>
                <td>{{ $item->Total_Stok_Toko }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>