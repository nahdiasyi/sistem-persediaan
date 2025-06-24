@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-white fw-bold">
                            <i class="fas fa-edit me-2"></i>Edit Pembelian - {{ $pembelian->id_pembelian }}
                        </h4>
                        <a href="{{ route('pembelian.index') }}" class="btn btn-light btn-hover-primary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.95);">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pembelian.update', $pembelian->id_pembelian) }}">
                        @csrf
                        @method('PUT')

                        <!-- Info Section -->
                        <div class="card mb-4" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); border: none;">
                            <div class="card-header" style="background: rgba(255,255,255,0.1);">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Pembelian
                                </h6>
                            </div>
                            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold text-primary">
                                                <i class="fas fa-truck me-1"></i>Supplier
                                            </label>
                                            <select name="id_supplier" class="form-select" required>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id_supplier }}" {{ $pembelian->id_supplier == $supplier->id_supplier ? 'selected' : '' }}>
                                                        {{ $supplier->nama_supplier }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold text-primary">
                                                <i class="fas fa-user me-1"></i>User
                                            </label>
                                            <select name="id_user" class="form-select" required>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id_user }}" {{ $pembelian->id_user == $user->id_user ? 'selected' : '' }}>
                                                    {{ $user->nama_user }}
                                                </option>
                                            @endforeach
                                        </select>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold text-primary">
                                                <i class="fas fa-calendar me-1"></i>Tanggal
                                            </label>
                                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d', strtotime($pembelian->tanggal)) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Section -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1);">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-list me-2"></i>Detail Pembelian
                                </h6>
                                <button type="button" class="btn btn-light btn-sm" onclick="tambahDetail()">
                                    <i class="fas fa-plus"></i> Tambah Barang
                                </button>
                            </div>
                            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                <div id="detail-container">
                                    @foreach($pembelian->detailPembelian as $i => $detail)
                                    <div class="detail-item" id="row-{{ $i }}">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">Kode Barang</label>
                                                <select name="barang[{{ $i }}][kode_barang]" class="form-select" required>
                                                    @foreach($barangs as $barang)
                                                        <option value="{{ $barang->kode_barang }}" {{ $detail->kode_barang == $barang->kode_barang ? 'selected' : '' }}>
                                                            {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">Jumlah</label>
                                                <input type="number" name="barang[{{ $i }}][jumlah]" value="{{ $detail->jumlah }}" class="form-control jumlah-input" required onchange="hitungTotal()">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">Harga Beli</label>
                                                <input type="number" name="barang[{{ $i }}][harga]" value="{{ $detail->harga }}" class="form-control harga-input" required onchange="hitungTotal()">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">Margin (%)</label>
                                                <input type="number" name="barang[{{ $i }}][margin]" value="10" class="form-control" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">Pajak (%)</label>
                                                <input type="number" name="barang[{{ $i }}][pajak]" value="11" class="form-control" required>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-danger w-100" onclick="hapusDetail({{ $i }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Total Section -->
                        <div class="card mb-4" style="background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); border: none;">
                            <div class="card-body text-center" style="background: rgba(255,255,255,0.9);">
                                <h5 class="text-primary mb-2">Total Pembelian</h5>
                                <h3 class="text-success fw-bold" id="total-harga">Rp 0</h3>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning btn-lg me-2">
                                <i class="fas fa-save"></i> Update Pembelian
                            </button>
                            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let index = {{ count($pembelian->detailPembelian) }};

function tambahDetail() {
    const container = document.getElementById('detail-container');

    const detailHtml = `
        <div class="detail-item" id="row-${index}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Kode Barang</label>
                    <select name="barang[${index}][kode_barang]" class="form-select" required>
                        <option value="">Pilih Barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->kode_barang }}">
                                {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Jumlah</label>
                    <input type="number" name="barang[${index}][jumlah]" class="form-control jumlah-input" placeholder="0" min="1" required onchange="hitungTotal()">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Harga Beli</label>
                    <input type="number" name="barang[${index}][harga]" class="form-control harga-input" placeholder="0" min="0" required onchange="hitungTotal()">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Margin (%)</label>
                    <input type="number" name="barang[${index}][margin]" class="form-control" placeholder="0" min="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Pajak (%)</label>
                    <input type="number" name="barang[${index}][pajak]" class="form-control" placeholder="0" min="0" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger w-100" onclick="hapusDetail(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', detailHtml);
    index++;
}

function hapusDetail(id) {
    const row = document.getElementById(`row-${id}`);
    if (row) {
        row.remove();
        hitungTotal();
    }
}

function hitungTotal() {
    let total = 0;
    const jumlahInputs = document.querySelectorAll('.jumlah-input');
    const hargaInputs = document.querySelectorAll('.harga-input');

    for (let i = 0; i < jumlahInputs.length; i++) {
        const jumlah = parseFloat(jumlahInputs[i].value) || 0;
        const harga = parseFloat(hargaInputs[i].value) || 0;
        total += jumlah * harga;
    }

    document.getElementById('total-harga').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Calculate initial total on page load
document.addEventListener('DOMContentLoaded', function() {
    hitungTotal();
});
</script>
@endpush
@endsection
