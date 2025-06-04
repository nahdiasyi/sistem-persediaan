@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ isset($pembelian) ? 'Edit' : 'Tambah' }} Pembelian</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ isset($pembelian) ? route('pembelian.update', $pembelian->id_pembelian) : route('pembelian.store') }}" 
                          method="POST" id="pembelianForm">
                        @csrf
                        @if(isset($pembelian))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_supplier" class="form-label">Supplier</label>
                                    <select class="form-control" name="id_supplier" id="id_supplier" required>
                                        <option value="">Pilih Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id_supplier }}" 
                                                {{ (isset($pembelian) && $pembelian->id_supplier == $supplier->id_supplier) ? 'selected' : '' }}>
                                                {{ $supplier->nama_supplier }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h6>Detail Barang</h6>
                        
                        <div id="barang-container">
                            @if(isset($pembelian) && $pembelian->detailPembelian->count() > 0)
                                @foreach($pembelian->detailPembelian as $index => $detail)
                                    <div class="barang-item border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Barang</label>
                                                <select class="form-control barang-select" name="barang[{{ $index }}][kode_barang]" required>
                                                    <option value="">Pilih Barang</option>
                                                    @foreach($barangs as $barang)
                                                        <option value="{{ $barang->kode_barang }}" 
                                                            {{ $detail->kode_barang == $barang->kode_barang ? 'selected' : '' }}>
                                                            {{ $barang->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Jumlah</label>
                                                <input type="number" class="form-control jumlah-input" 
                                                       name="barang[{{ $index }}][jumlah]" 
                                                       value="{{ $detail->jumlah }}" 
                                                       min="1" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Harga Beli</label>
                                                <input type="number" class="form-control harga-input" 
                                                       name="barang[{{ $index }}][harga]" 
                                                       value="{{ $detail->harga }}" 
                                                       min="0" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Margin (%)</label>
                                                <input type="number" class="form-control margin-input" 
                                                       name="barang[{{ $index }}][margin]" 
                                                       value="0" 
                                                       min="0" step="0.01" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">PPN (%)</label>
                                                <input type="number" class="form-control pajak-input" 
                                                       name="barang[{{ $index }}][pajak]" 
                                                       value="0" 
                                                       min="0" step="0.01" required>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm d-block remove-barang">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <small class="text-muted">
                                                    Preview Harga Jual: <span class="preview-harga-jual fw-bold">Rp 0</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="barang-item border p-3 mb-3 rounded">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Barang</label>
                                            <select class="form-control barang-select" name="barang[0][kode_barang]" required>
                                                <option value="">Pilih Barang</option>
                                                @foreach($barangs as $barang)
                                                    <option value="{{ $barang->kode_barang }}">{{ $barang->nama_barang }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" class="form-control jumlah-input" 
                                                   name="barang[0][jumlah]" 
                                                   value="1" 
                                                   min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Harga Beli</label>
                                            <input type="number" class="form-control harga-input" 
                                                   name="barang[0][harga]" 
                                                   value="0" 
                                                   min="0" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Margin (%)</label>
                                            <input type="number" class="form-control margin-input" 
                                                   name="barang[0][margin]" 
                                                   value="0" 
                                                   min="0" step="0.01" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">PPN (%)</label>
                                            <input type="number" class="form-control pajak-input" 
                                                   name="barang[0][pajak]" 
                                                   value="0" 
                                                   min="0" step="0.01" required>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-sm d-block remove-barang">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <small class="text-muted">
                                                Preview Harga Jual: <span class="preview-harga-jual fw-bold">Rp 0</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="button" class="btn btn-success mb-3" id="add-barang">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>

                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($pembelian) ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let barangIndex = {{ isset($pembelian) ? $pembelian->detailPembelian->count() : 1 }};
    
    // Template barang baru
    const barangTemplate = `
        <div class="barang-item border p-3 mb-3 rounded">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Barang</label>
                    <select class="form-control barang-select" name="barang[INDEX][kode_barang]" required>
                        <option value="">Pilih Barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->kode_barang }}">{{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jumlah</label>
                    <input type="number" class="form-control jumlah-input" 
                           name="barang[INDEX][jumlah]" 
                           value="1" 
                           min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" class="form-control harga-input" 
                           name="barang[INDEX][harga]" 
                           value="0" 
                           min="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Margin (%)</label>
                    <input type="number" class="form-control margin-input" 
                           name="barang[INDEX][margin]" 
                           value="0" 
                           min="0" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">PPN (%)</label>
                    <input type="number" class="form-control pajak-input" 
                           name="barang[INDEX][pajak]" 
                           value="0" 
                           min="0" step="0.01" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm d-block remove-barang">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <small class="text-muted">
                        Preview Harga Jual: <span class="preview-harga-jual fw-bold">Rp 0</span>
                    </small>
                </div>
            </div>
        </div>
    `;

    // Tambah barang
    document.getElementById('add-barang').addEventListener('click', function() {
        const newBarang = barangTemplate.replace(/INDEX/g, barangIndex);
        document.getElementById('barang-container').insertAdjacentHTML('beforeend', newBarang);
        barangIndex++;
    });

    // Hapus barang
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-barang') || e.target.closest('.remove-barang')) {
            if (document.querySelectorAll('.barang-item').length > 1) {
                e.target.closest('.barang-item').remove();
            } else {
                alert('Minimal harus ada satu barang');
            }
        }
    });

    // Hitung preview harga jual
    function hitungHargaJual(item) {
        const harga = parseFloat(item.querySelector('.harga-input').value) || 0;
        const margin = parseFloat(item.querySelector('.margin-input').value) || 0;
        const pajak = parseFloat(item.querySelector('.pajak-input').value) || 0;
        
        const nilaiMargin = (harga * margin) / 100;
        const nilaiPajak = ((harga + nilaiMargin) * pajak) / 100;
        const hargaJual = harga + nilaiMargin + nilaiPajak;
        
        item.querySelector('.preview-harga-jual').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaJual);
    }

    // Event listener untuk perubahan input
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('harga-input') || 
            e.target.classList.contains('margin-input') || 
            e.target.classList.contains('pajak-input')) {
            const barangItem = e.target.closest('.barang-item');
            hitungHargaJual(barangItem);
        }
    });

    // Hitung preview awal
    document.querySelectorAll('.barang-item').forEach(function(item) {
        hitungHargaJual(item);
    });
});
</script>
@endsection