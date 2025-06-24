{{-- EDIT VIEW --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Penjualan</h3>
    <form id="formPenjualan">
        @method('PUT')
        <div class="form-group mb-3">
            <label>User</label>
            <select id="id_user" class="form-control" required>
                <option value="">Pilih User</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ $penjualan->id_user == $u->id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label>Barang</label>
            <select id="kode_barang" class="form-control">
                <option value="">Pilih Barang</option>
                @foreach($barang as $b)
                    <option value="{{ $b->kode_barang }}" data-harga="{{ $b->harga }}">{{ $b->nama_barang }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label>Jumlah</label>
            <input type="number" id="jumlah" class="form-control" min="1">
        </div>

        <button type="button" id="btnTambah" class="btn btn-info mb-3">Tambah ke Detail</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelDetail"></tbody>
        </table>

        <div class="mt-3">
            <button type="button" id="btnSimpan" class="btn btn-success">Update</button>
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<script>
let details = {!! json_encode($penjualan->detailPenjualan->map(fn($d) => [
    'kode_barang' => $d->kode_barang,
    'jumlah' => $d->jumlah,
    'harga' => $d->harga,
    'nama_barang' => $d->barang->nama_barang ?? $d->kode_barang,
])) !!};

function renderTable() {
    let html = '';
    details.forEach((d, i) => {
        html += `<tr>
            <td>${d.nama_barang}</td>
            <td>${d.jumlah}</td>
            <td>Rp ${d.harga.toLocaleString('id-ID')}</td>
            <td>Rp ${(d.jumlah * d.harga).toLocaleString('id-ID')}</td>
            <td><button onclick="hapus(${i})" class="btn btn-sm btn-danger">Hapus</button></td>
        </tr>`;
    });
    document.getElementById('tabelDetail').innerHTML = html;
}

function hapus(i) {
    details.splice(i, 1);
    renderTable();
}

document.getElementById('btnTambah').onclick = () => {
    const kode = document.getElementById('kode_barang').value;
    const jumlah = document.getElementById('jumlah').value;
    const nama = document.getElementById('kode_barang').selectedOptions[0].text;
    const harga = document.getElementById('kode_barang').selectedOptions[0].dataset.harga;

    if (!kode || !jumlah) return alert('Pilih barang dan isi jumlah');

    details.push({
        kode_barang: kode,
        jumlah: parseInt(jumlah),
        harga: parseInt(harga),
        nama_barang: nama
    });
    renderTable();

    document.getElementById('kode_barang').value = '';
    document.getElementById('jumlah').value = '';
};

document.getElementById('btnSimpan').onclick = () => {
    const id_user = document.getElementById('id_user').value;
    if (!id_user || details.length === 0) return alert('Isi semua data');

    fetch(`{{ route('penjualan.update', $penjualan->id_penjualan) }}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ id_user, details })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Data berhasil diperbarui');
            window.location.href = '{{ route('penjualan.index') }}';
        } else {
            alert('Gagal: ' + data.message);
        }
    });
};

renderTable();
</script>
@endsection
