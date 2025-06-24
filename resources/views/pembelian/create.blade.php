@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-header" style="background: rgba(255, 255, 255, 0.1); border-bottom: 1px solid rgba(248, 241, 241, 0.2);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-white fw-bold">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Pembelian
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

                    <form method="POST" action="{{ route('pembelian.store') }}" id="pembelianForm">
                        @csrf

                        <!-- Info Section -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)); border: none;">
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
                                                <option value="">Pilih Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold text-primary">
                                                <i class="fas fa-calendar me-1"></i>Tanggal
                                            </label>
                                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
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
                                    <!-- Detail items will be added here -->
                                </div>

                                <div class="text-center mt-3" id="empty-state">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada barang yang ditambahkan</h5>
                                    <p class="text-muted">Klik tombol "Tambah Barang" untuk menambahkan item</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Section -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <div class="card-body text-center" style="background: rgba(255,255,255,0.9);">
                                <h5 class="text-primary mb-2">Total Pembelian</h5>
                                <h3 class="text-success fw-bold" id="total-harga">Rp 0</h3>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg me-2">
                                <i class="fas fa-save"></i> Simpan Pembelian
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

<style>
    .btn-hover-primary:hover {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%) !important;
        border: none;
        color: white !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .detail-item {
        background: rgba(255,255,255,0.7);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>

@push('scripts')
<script>
let index = 0;

function tambahDetail() {
    const container = document.getElementById('detail-container');
    const emptyState = document.getElementById('empty-state');

    if (emptyState) {
        emptyState.style.display = 'none';
    }

    const detailHtml = `
        <div class="detail-item" id="row-${index}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Kode Barang</label>
                    <select name="barang[${index}][kode_barang]" class="form-select barang-select" required onchange="updateBarang(${index})">
                        <option value="">Pilih Barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->kode_barang }}" data-nama="{{ $barang->nama_barang }}">
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

    // Show empty state if no items
    if (container.children.length === 0 && emptyState) {
        emptyState.style.display = 'block';
    }
}

function hapusDetail(id) {
    const row = document.getElementById(`row-${id}`);
    if (row) {
        row.remove();
        hitungTotal();

        // Show empty state if no items left
        const container = document.getElementById('detail-container');
        const emptyState = document.getElementById('empty-state');
        if (container.children.length === 0 && emptyState) {
            emptyState.style.display = 'block';
        }
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

// Add first detail on page load
document.addEventListener('DOMContentLoaded', function() {
    tambahDetail();
});
</script>
@endpush
@endsection
