@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <h5 class="mb-0 text-white fw-bold"><i class="fas fa-edit me-2"></i>Edit Barang</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="background: transparent;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="text-white-50 text-decoration-none"><i class="fas fa-home"></i> Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('barang.index') }}" class="text-white-50 text-decoration-none">Daftar Barang</a>
                            </li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Edit Barang</li>
                        </ol>
                    </nav>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.95);">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Terdapat kesalahan:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card" style="background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%); border: none;">
                        <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                            <h6 class="mb-0 text-white">
                                <i class="fas fa-edit me-2"></i>Form Edit Barang - {{ $barang->kode_barang }}
                            </h6>
                        </div>
                        <div class="card-body" style="background: rgba(255,255,255,0.9);">
                            <form action="{{ route('barang.update', $barang->kode_barang) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="kode_barang" class="form-label fw-bold">
                                                <i class="fas fa-barcode text-primary me-1"></i>Kode Barang
                                            </label>
                                            <input type="text" name="kode_barang" id="kode_barang"
                                                   class="form-control form-control-gradient"
                                                   value="{{ old('kode_barang', $barang->kode_barang) }}"
                                                   required readonly>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="nama_barang" class="form-label fw-bold">
                                                <i class="fas fa-box text-primary me-1"></i>Nama Barang
                                            </label>
                                            <input type="text" name="nama_barang" id="nama_barang"
                                                   class="form-control form-control-gradient"
                                                   value="{{ old('nama_barang', $barang->nama_barang) }}"
                                                   required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="id_kategori" class="form-label fw-bold">
                                                <i class="fas fa-tags text-primary me-1"></i>Kategori
                                            </label>
                                            <select name="id_kategori" id="id_kategori" class="form-select form-control-gradient" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach($kategoris as $kategori)
                                                    <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori', $barang->id_kategori) == $kategori->id_kategori ? 'selected' : '' }}>
                                                        {{ $kategori->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="merek" class="form-label fw-bold">
                                                <i class="fas fa-trademark text-primary me-1"></i>Merek
                                            </label>
                                            <input type="text" name="merek" id="merek"
                                                   class="form-control form-control-gradient"
                                                   value="{{ old('merek', $barang->merek) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="Satuan" class="form-label fw-bold">
                                                <i class="fas fa-ruler text-primary me-1"></i>Satuan
                                            </label>
                                            <input type="text" name="Satuan" id="Satuan"
                                                   class="form-control form-control-gradient"
                                                   value="{{ old('Satuan', $barang->Satuan) }}"
                                                   placeholder="Contoh: PCS, KG, Liter">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="harga_beli" class="form-label fw-bold">
                                                <i class="fas fa-money-bill text-success me-1"></i>Harga Beli
                                            </label>
                                            <input type="number" name="harga_beli" id="harga_beli"
                                                   class="form-control form-control-gradient"
                                                   value="{{ old('harga_beli', $barang->harga_beli) }}" min="0" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="harga_jual" class="form-label fw-bold">
                                                <i class="fas fa-tag text-success me-1"></i>Harga Jual
                                            </label>
                                            <input type="number" name="harga_jual" id="harga_jual"
                                                   class="form-control form-control-gradient"
                                                   value="{{ old('harga_jual', $barang->harga_jual) }}" min="0" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="stok" class="form-label fw-bold">
                                                <i class="fas fa-cubes text-info me-1"></i>Stok
                                            </label>
                                            <input type="number" name="stok" id="stok"
                                                   class="form-control form-control-gradient"
                                                   value="{{ old('stok', $barang->stok) }}" min="0" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="keterangan" class="form-label fw-bold">
                                                <i class="fas fa-info-circle text-primary me-1"></i>Keterangan
                                            </label>
                                            <select name="keterangan" id="keterangan" class="form-select form-control-gradient">
                                                <option value="">-- Pilih Keterangan --</option>
                                                <option value="stok tersedia" {{ old('keterangan', $barang->keterangan) == 'stok tersedia' ? 'selected' : '' }}>Stok Tersedia</option>
                                                <option value="stok habis" {{ old('keterangan', $barang->keterangan) == 'stok habis' ? 'selected' : '' }}>Stok Habis</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-4" style="background: linear-gradient(45deg, #74b9ff 0%, #0984e3 100%);">
                                    <h6 class="text-white mb-2">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Barang Saat Ini
                                    </h6>
                                    <div class="row text-white">
                                        <div class="col-md-4">
                                            <small><strong>Stok Saat Ini:</strong> {{ $barang->stok ?? 0 }}</small>
                                        </div>
                                        <div class="col-md-4">
                                            <small><strong>Harga Beli:</strong> Rp {{ number_format($barang->harga_beli ?? 0, 0, ',', '.') }}</small>
                                        </div>
                                        <div class="col-md-4">
                                            <small><strong>Harga Jual:</strong> Rp {{ number_format($barang->harga_jual ?? 0, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4 gap-2">
                                    <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-warning btn-lg btn-gradient-warning">
                                        <i class="fas fa-save me-2"></i>Update Barang
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control-gradient {
        border: 2px solid transparent;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #667eea, #764ba2) border-box;
        transition: all 0.3s ease;
    }

    .form-control-gradient:focus {
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #f093fb, #f5576c) border-box;
    }

    .btn-gradient-warning {
        background: linear-gradient(45deg, #ffc107 0%, #ff8c00 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-gradient-warning:hover {
        background: linear-gradient(45deg, #ff8c00 0%, #ff6b00 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        color: white;
    }

    .card {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: rgba(255,255,255,0.7);
    }

    .alert {
        border-radius: 10px;
        border: none;
    }
</style>
@endsection
