@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-shopping-cart me-2"></i>{{ isset($pembelian) ? 'Edit' : 'Tambah' }} Pembelian
                    </h5>
                    <a href="{{ route('pembelian.index') }}" class="btn btn-light btn-hover-primary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ isset($pembelian) ? route('pembelian.update', $pembelian->id_pembelian) : route('pembelian.store') }}"
                          method="POST" id="pembelianForm">
                        @csrf
                        @if(isset($pembelian))
                            @method('PUT')
                        @endif
                        <input type="hidden" name="id_user" value="{{ auth()->user()->id_user }}">

                        <!-- Supplier Section -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                            <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                <h6 class="mb-0 text-white fw-bold">
                                    <i class="fas fa-truck me-2"></i>Informasi Supplier
                                </h6>
                            </div>
                            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="id_supplier" class="form-label fw-bold">Supplier</label>
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
                                                            <!-- ✅ Input User (Tambahan) -->
                                    <div class="col-md-6">
                                        <label for="id_user" class="form-label fw-bold">User</label>
                                        <select class="form-control" name="id_user" id="id_user" required>
                                            <option value="">Pilih User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id_user }}"
                                                    {{ (isset($pembelian) && $pembelian->id_user == $user->id_user) ? 'selected' : '' }}>
                                                    {{ $user->nama_user }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Barang Section -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                <h6 class="mb-0 text-white fw-bold">
                                    <i class="fas fa-boxes me-2"></i>Detail Barang
                                </h6>
                                <button type="button" class="btn btn-light btn-sm btn-hover-primary" id="add-barang">
                                    <i class="fas fa-plus"></i> Tambah Barang
                                </button>
                            </div>
                            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                <div id="barang-container">
                                    @if(isset($pembelian) && $pembelian->detailPembelian->count() > 0)
                                        @foreach($pembelian->detailPembelian as $index => $detail)
                                            <div class="barang-item card mb-3" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                                                <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0 text-white fw-bold">
                                                            <i class="fas fa-box me-2"></i>Barang {{ $index + 1 }}
                                                        </h6>
                                                        <button type="button" class="btn btn-danger btn-sm remove-barang">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label class="form-label fw-bold">Barang</label>
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
                                                            <label class="form-label fw-bold">Jumlah</label>
                                                            <input type="number" class="form-control jumlah-input"
                                                                   name="barang[{{ $index }}][jumlah]"
                                                                   value="{{ $detail->jumlah }}"
                                                                   min="1" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label fw-bold">Harga Beli</label>
                                                            <input type="number" class="form-control harga-input"
                                                                   name="barang[{{ $index }}][harga]"
                                                                   value="{{ $detail->harga }}"
                                                                   min="0" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label fw-bold">Margin (%)</label>
                                                            <input type="number" class="form-control margin-input"
                                                                   name="barang[{{ $index }}][margin]"
                                                                   value="0"
                                                                   min="0" step="0.01" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label fw-bold">PPN (%)</label>
                                                            <input type="number" class="form-control pajak-input"
                                                                   name="barang[{{ $index }}][pajak]"
                                                                   value="0"
                                                                   min="0" step="0.01" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <div class="info-card p-3" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                                                                <strong class="text-white">Preview Harga Jual: </strong>
                                                                <span class="preview-harga-jual fw-bold text-white">Rp 0</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="barang-item card mb-3" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                                            <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0 text-white fw-bold">
                                                        <i class="fas fa-box me-2"></i>Barang 1
                                                    </h6>
                                                    <button type="button" class="btn btn-danger btn-sm remove-barang">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold">Barang</label>
                                                        <select class="form-control barang-select" name="barang[0][kode_barang]" required>
                                                            <option value="">Pilih Barang</option>
                                                            @foreach($barangs as $barang)
                                                                <option value="{{ $barang->kode_barang }}">{{ $barang->nama_barang }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label fw-bold">Jumlah</label>
                                                        <input type="number" class="form-control jumlah-input"
                                                               name="barang[0][jumlah]"
                                                               value="1"
                                                               min="1" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label fw-bold">Harga Beli</label>
                                                        <input type="number" class="form-control harga-input"
                                                               name="barang[0][harga]"
                                                               value="0"
                                                               min="0" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label fw-bold">Margin (%)</label>
                                                        <input type="number" class="form-control margin-input"
                                                               name="barang[0][margin]"
                                                               value="0"
                                                               min="0" step="0.01" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label fw-bold">PPN (%)</label>
                                                        <input type="number" class="form-control pajak-input"
                                                               name="barang[0][pajak]"
                                                               value="0"
                                                               min="0" step="0.01" required>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <div class="info-card p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px;">
                                                            <strong class="text-white">Preview Harga Jual: </strong>
                                                            <span class="preview-harga-jual fw-bold text-white">Rp 0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg btn-hover-success">
                                        <i class="fas fa-save"></i> {{ isset($pembelian) ? 'Update' : 'Simpan' }}
                                    </button>
                                </div>
                            </div>
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
        <div class="barang-item card mb-3" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
            <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white fw-bold">
                        <i class="fas fa-box me-2"></i>Barang INDEX_DISPLAY
                    </h6>
                    <button type="button" class="btn btn-danger btn-sm remove-barang">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Barang</label>
                        <select class="form-control barang-select" name="barang[INDEX][kode_barang]" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->kode_barang }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Jumlah</label>
                        <input type="number" class="form-control jumlah-input"
                               name="barang[INDEX][jumlah]"
                               value="1"
                               min="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Harga Beli</label>
                        <input type="number" class="form-control harga-input"
                               name="barang[INDEX][harga]"
                               value="0"
                               min="0" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Margin (%)</label>
                        <input type="number" class="form-control margin-input"
                               name="barang[INDEX][margin]"
                               value="0"
                               min="0" step="0.01" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">PPN (%)</label>
                        <input type="number" class="form-control pajak-input"
                               name="barang[INDEX][pajak]"
                               value="0"
                               min="0" step="0.01" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="info-card p-3" style="background: linear-gradient(45deg, #fa709a 0%, #fee140 100%); border-radius: 8px;">
                            <strong class="text-white">Preview Harga Jual: </strong>
                            <span class="preview-harga-jual fw-bold text-white">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Tambah barang
    document.getElementById('add-barang').addEventListener('click', function() {
        const newBarang = barangTemplate
            .replace(/INDEX/g, barangIndex)
            .replace(/INDEX_DISPLAY/g, barangIndex + 1);
        document.getElementById('barang-container').insertAdjacentHTML('beforeend', newBarang);
        barangIndex++;
        updateBarangNumbers();
    });

    // Hapus barang
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-barang') || e.target.closest('.remove-barang')) {
            if (document.querySelectorAll('.barang-item').length > 1) {
                e.target.closest('.barang-item').remove();
                updateBarangNumbers();
            } else {
                alert('Minimal harus ada satu barang');
            }
        }
    });

    // Update nomor barang
    function updateBarangNumbers() {
        const barangItems = document.querySelectorAll('.barang-item');
        barangItems.forEach((item, index) => {
            const header = item.querySelector('.card-header h6');
            header.innerHTML = `<i class="fas fa-box me-2"></i>Barang ${index + 1}`;
        });
    }

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

<style>
    .btn-hover-primary:hover {
        background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%) !important;
        border: none;
        color: white !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .btn-hover-success:hover {
        background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%) !important;
        border: none;
        color: white !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .info-card {
        transition: transform 0.3s ease;
    }

    .info-card:hover {
        transform: scale(1.02);
    }

    .card {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .barang-item {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>
@endsection
