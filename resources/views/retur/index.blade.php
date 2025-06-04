@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Daftar Retur</h3>
    <a href="{{ route('retur.create') }}" class="btn btn-primary mb-3">Tambah Retur</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Retur</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>User</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returs as $retur)
            <tr>
                <td>{{ $retur->id_retur }}</td>
                <td>{{ $retur->tanggal }}</td>
                <td>{{ $retur->supplier->nama_supplier ?? '-' }}</td>
                <td>{{ $retur->user->nama_user ?? '-' }}</td>
                <td>
                    <!-- Ganti logika di bawah ini sesuai status dari retur (jika ada field status) -->
                    <form method="POST" action="{{ route('retur.updateStatus', $retur->id_retur) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" name="status" value="berhasil" class="btn btn-success btn-sm">Berhasil</button>
                        <button type="submit" name="status" value="gagal" class="btn btn-danger btn-sm">Gagal</button>
                    </form>
                </td>
                <td>
                    <button class="btn btn-info btn-sm" onclick="toggleDetail('{{ $retur->id_retur }}')">Lihat Detail</button>
                </td>
            </tr>
            <tr id="detail-{{ $retur->id_retur }}" style="display:none;">
                <td colspan="6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($retur->detailRetur as $detail)
                            <tr>
                                <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ $detail->harga }}</td>
                                <td>{{ $detail->alasan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function toggleDetail(id) {
        let row = document.getElementById('detail-' + id);
        row.style.display = row.style.display === 'none' ? '' : 'none';
    }
</script>
@endsection
