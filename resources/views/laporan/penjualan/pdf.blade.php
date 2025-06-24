<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        h3 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

    <h3>Laporan Penjualan</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>User</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualans as $p)
            <tr>
                <td>{{ $p->id_penjualan }}</td>
                <td>{{ $p->tanggal }}</td>
                <td>{{ $p->user->nama_user ?? '-' }}</td>
                <td>Rp {{ number_format($p->detailPenjualan->sum(fn($d) => $d->jumlah * $d->harga), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
