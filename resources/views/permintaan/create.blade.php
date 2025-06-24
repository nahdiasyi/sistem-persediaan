@extends('layouts.app')

@section('title', 'Tambah Permintaan')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Permintaan Baru
                    </h5>
                </div>

                <form action="{{ route('permintaan.store') }}" method="POST" id="permintaanForm">
                    @csrf
                    <div class="card-body" style="background: rgba(255,255,255,0.95);">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Form Header Section -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Permintaan
                                </h6>
                            </div>
                            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="id_supplier" class="form-label fw-bold">Supplier <span class="text-danger">*</span></label>
                                        <select class="form-select @error('id_supplier') is-invalid @enderror"
                                                id="id_supplier" name="id_supplier" required>
                                            <option value="">Pilih Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id_supplier }}"
                                                        {{ old('id_supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                                                    {{ $supplier->nama_supplier }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_supplier')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tanggal" class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                               id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Items Section -->
                        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-boxes me-2"></i>Detail Barang
                                </h6>
                                <button type="button" class="btn btn-light btn-hover-success" id="addItem">
                                    <i class="fas fa-plus"></i> Tambah Barang
                                </button>
                            </div>
                            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                <div id="itemContainer">
                                    <div class="item-row mb-3">
                                        <div class="card item-card" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                                            <div class="card-body" style="background: rgba(255,255,255,0.95);">
                                                <div class="row align-items-end">
                                                    <div class="col-md-5">
                                                        <label class="form-label fw-bold">Barang <span class="text-danger">*</span></label>
                                                        <select class="form-select barang-select" name="kode_barang[]" required>
                                                            <option value="">Pilih Barang</option>
                                                            @foreach($barang as $item)
                                                                <option value="{{ $item->kode_barang }}">
                                                                    {{ $item->nama_barang }} ({{ $item->kode_barang }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" name="jumlah[]"
                                                               min="1" required placeholder="0">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger remove-item btn-animated" disabled>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between" style="background: rgba(255,255,255,0.1); border-top: 1px solid rgba(255,255,255,0.2);">
                        <a href="{{ route('permintaan.index') }}" class="btn btn-light btn-hover-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-light btn-hover-primary">
                            <i class="fas fa-save"></i> Simpan Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .item-card {
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    .btn-hover-primary:hover {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%) !important;
        border: none;
        color: white !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .btn-hover-secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border: none;
        color: white !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .btn-hover-success:hover {
       background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)) !important;
        border: none;
        color: white !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .btn-animated:hover {
        transform: translateY(-1px);
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .card {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        transition: all 0.3s ease;
    }

    .alert {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .form-label {
        color: #2d3748;
    }

    .text-danger {
        color: #e53e3e !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 1;

    // Template untuk item baru dengan styling yang sama
    function getItemTemplate() {
        return `
        <div class="item-row mb-3">
            <div class="card item-card" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none;">
                <div class="card-body" style="background: rgba(255,255,255,0.95);">
                    <div class="row align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Barang <span class="text-danger">*</span></label>
                            <select class="form-select barang-select" name="kode_barang[]" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->kode_barang }}">
                                        {{ $item->nama_barang }} ({{ $item->kode_barang }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="jumlah[]"
                                   min="1" required placeholder="0">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-item btn-animated">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
    }

    // Tambah item baru
    document.getElementById('addItem').addEventListener('click', function() {
        const container = document.getElementById('itemContainer');
        container.insertAdjacentHTML('beforeend', getItemTemplate());
        itemCount++;
        updateRemoveButtons();

        // Add animation effect
        const newItem = container.lastElementChild;
        newItem.style.opacity = '0';
        newItem.style.transform = 'translateY(20px)';
        setTimeout(() => {
            newItem.style.transition = 'all 0.3s ease';
            newItem.style.opacity = '1';
            newItem.style.transform = 'translateY(0)';
        }, 10);
    });

    // Hapus item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
            const itemRow = e.target.closest('.item-row');

            // Add fade out animation
            itemRow.style.transition = 'all 0.3s ease';
            itemRow.style.opacity = '0';
            itemRow.style.transform = 'translateX(-20px)';

            setTimeout(() => {
                itemRow.remove();
                itemCount--;
                updateRemoveButtons();
            }, 300);
        }
    });

    // Update status tombol hapus
    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-item');
        removeButtons.forEach(button => {
            button.disabled = removeButtons.length <= 1;
        });
    }

    // Validasi form sebelum submit
    document.getElementById('permintaanForm').addEventListener('submit', function(e) {
        const barangSelects = document.querySelectorAll('select[name="kode_barang[]"]');
        const jumlahInputs = document.querySelectorAll('input[name="jumlah[]"]');

        let hasValidItem = false;

        for (let i = 0; i < barangSelects.length; i++) {
            if (barangSelects[i].value && jumlahInputs[i].value > 0) {
                hasValidItem = true;
                break;
            }
        }

        if (!hasValidItem) {
            e.preventDefault();
            alert('Minimal harus ada satu barang yang dipilih dengan jumlah > 0');
        }
    });

    // Add loading effect on form submit
    document.getElementById('permintaanForm').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
    });
});
</script>
@endsection
