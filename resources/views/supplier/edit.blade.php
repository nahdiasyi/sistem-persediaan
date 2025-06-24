@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-white fw-bold">
                                <i class="fas fa-edit me-2"></i>Edit Supplier
                            </h5>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0" style="background: none;">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                                            <i class="fas fa-home"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('supplier.index') }}" class="text-white text-decoration-none">
                                            Supplier
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active text-white" aria-current="page">
                                        Edit Supplier
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                    <div class="card-body" style="background: rgba(255,255,255,0.95);">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Supplier Info Card -->
                        <div class="card mb-4" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); border: none;">
                            <div class="card-body" style="background: rgba(255,255,255,0.1);">
                                <div class="row text-white">
                                    <div class="col-md-4 text-center">
                                        <i class="fas fa-id-card fa-2x mb-2"></i>
                                        <h6>ID Supplier</h6>
                                        <span class="fs-5 fw-bold">{{ $supplier->id_supplier }}</span>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                        <h6>Dibuat</h6>
                                        <span>{{ $supplier->created_at ? $supplier->created_at->format('d/m/Y') : '-' }}</span>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <i class="fas fa-clock fa-2x mb-2"></i>
                                        <h6>Terakhir Update</h6>
                                        <span>{{ $supplier->updated_at ? $supplier->updated_at->format('d/m/Y H:i') : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('supplier.update', $supplier->id_supplier) }}" method="POST" id="supplierForm">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-4">
                                        <input type="text"
                                               class="form-control @error('nama_supplier') is-invalid @enderror"
                                               id="nama_supplier"
                                               name="nama_supplier"
                                               placeholder="Masukkan nama supplier"
                                               value="{{ old('nama_supplier', $supplier->nama_supplier) }}"
                                               required>
                                        <label for="nama_supplier">
                                            <i class="fas fa-building me-2"></i>Nama Supplier
                                        </label>
                                        @error('nama_supplier')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input type="tel"
                                               class="form-control @error('no_telp') is-invalid @enderror"
                                               id="no_telp"
                                               name="no_telp"
                                               placeholder="Masukkan nomor telepon"
                                               value="{{ old('no_telp', $supplier->no_telp) }}"
                                               required>
                                        <label for="no_telp">
                                            <i class="fas fa-phone me-2"></i>No Telepon
                                        </label>
                                        @error('no_telp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                        

                                <div class="col-md-12">
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control @error('alamat_supplier') is-invalid @enderror"
                                                  id="alamat_supplier"
                                                  name="alamat_supplier"
                                                  placeholder="Masukkan alamat lengkap supplier"
                                                  style="height: 100px;"
                                                  required>{{ old('alamat_supplier', $supplier->alamat_supplier) }}</textarea>
                                        <label for="alamat_supplier">
                                            <i class="fas fa-map-marker-alt me-2"></i>Alamat Supplier
                                        </label>
                                        @error('alamat_supplier')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('supplier.index') }}" class="btn btn-secondary btn-lg">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </a>
                                        <div>
                                            <button type="reset" class="btn btn-outline-warning btn-lg me-2">
                                                <i class="fas fa-undo me-2"></i>Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary btn-lg btn-submit">
                                                <i class="fas fa-save me-2"></i>Update Supplier
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Log Card (if you want to show history) -->
                <div class="card mt-4 shadow" style="background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); border: none;">
                    <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                        <h6 class="mb-0 text-white fw-bold">
                            <i class="fas fa-history me-2"></i>Informasi Perubahan
                        </h6>
                    </div>
                    <div class="card-body" style="background: rgba(255,255,255,0.9);">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-plus-circle text-success me-2"></i>
                                    <span><strong>Dibuat:</strong> {{ $supplier->created_at ? $supplier->created_at->format('d/m/Y H:i:s') : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-edit text-primary me-2"></i>
                                    <span><strong>Terakhir Diupdate:</strong> {{ $supplier->updated_at ? $supplier->updated_at->format('d/m/Y H:i:s') : '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Pastikan data yang diubah sudah benar sebelum menyimpan. Perubahan akan mempengaruhi semua transaksi yang terkait dengan supplier ini.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-floating > .form-control:focus,
        .form-floating > .form-control:not(:placeholder-shown) {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        .form-floating > label {
            opacity: 0.8;
            font-weight: 500;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            opacity: 1;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            color: #f5576c;
        }

        .btn-submit:hover {
            background: linear-gradient(45deg, #007bff 0%, #0056b3 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
            transition: all 0.3s ease;
        }

        .card {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .form-control:focus {
            border-color: #f5576c;
            box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.25);
        }

        .btn-secondary:hover {
            background: linear-gradient(45deg, #6c757d 0%, #5a6268 100%) !important;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .btn-outline-warning:hover {
            background: linear-gradient(45deg, #ffc107 0%, #e0a800 100%) !important;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.7);
        }

        .alert-info {
            background: linear-gradient(45deg, #17a2b8 0%, #138496 100%);
            border: none;
            color: white;
        }
    </style>

    <script>
        // Form validation and enhancement
        document.getElementById('supplierForm').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.btn-submit');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupdate...';
            submitBtn.disabled = true;
        });

        // Phone number formatting
        document.getElementById('no_telp').addEventListener('input', function(e) {
            // Remove non-numeric characters except +
            e.target.value = e.target.value.replace(/[^\d+]/g, '');
        });

        // Auto-capitalize supplier name
        document.getElementById('nama_supplier').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\b\w/g, function(match) {
                return match.toUpperCase();
            });
        });

        // Reset form to original values
        document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin mereset form ke nilai awal?')) {
                document.getElementById('supplierForm').reset();
            }
        });

        // Confirmation before leaving page with unsaved changes
        let formChanged = false;
        document.getElementById('supplierForm').addEventListener('input', function() {
            formChanged = true;
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
            }
        });

        // Remove the beforeunload listener when form is submitted
        document.getElementById('supplierForm').addEventListener('submit', function() {
            formChanged = false;
        });
    </script>
@endsection
