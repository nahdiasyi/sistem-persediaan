<!-- resources/views/kategori/create.blade.php -->
@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-plus me-2"></i>Tambah Kategori Baru
                    </h5>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.95);">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb" style="background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%); padding: 10px 15px; border-radius: 8px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-home me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('kategori.index') }}" class="text-white text-decoration-none">Kategori</a>
                            </li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Tambah Kategori</li>
                        </ol>
                    </nav>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: linear-gradient(45deg, #00b894 0%, #00cec9 100%); border: none; color: white;">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Form Section -->
                    <div class="form-container p-4" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border-radius: 15px;">
                        <form action="{{ route('kategori.store') }}" method="POST" id="createForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-4">
                                        <input type="text"
                                               class="form-control form-control-lg @error('nama_kategori') is-invalid @enderror"
                                               id="nama_kategori"
                                               name="nama_kategori"
                                               value="{{ old('nama_kategori') }}"
                                               placeholder="Masukkan nama kategori"
                                               required
                                               style="background: rgba(255,255,255,0.9); border: 2px solid #dee2e6;">
                                        <label for="nama_kategori" class="fw-bold text-dark">
                                            <i class="fas fa-tags me-2"></i>Nama Kategori
                                        </label>
                                        @error('nama_kategori')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-4">
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                          id="deskripsi"
                                          name="deskripsi"
                                          placeholder="Deskripsi kategori (opsional)"
                                          style="height: 100px; background: rgba(255,255,255,0.9); border: 2px solid #dee2e6;">{{ old('deskripsi') }}</textarea>
                                <label for="deskripsi" class="fw-bold text-dark">
                                    <i class="fas fa-file-alt me-2"></i>Deskripsi (Opsional)
                                </label>
                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary btn-lg me-2 btn-animated">
                                        <i class="fas fa-save me-2"></i>Simpan Kategori
                                    </button>
                                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary btn-lg btn-animated">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Info Card -->
                    <div class="mt-4">
                        <div class="info-card p-3" style="background: linear-gradient(45deg, #fd79a8 0%, #fdcb6e 100%); border-radius: 10px;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x text-white me-3"></i>
                                <div class="text-white">
                                    <h6 class="mb-1 fw-bold">Tips Menambah Kategori:</h6>
                                    <small>Gunakan nama kategori yang jelas dan mudah dipahami. Kategori akan membantu mengelompokkan barang dengan lebih baik.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #43e97b !important;
        box-shadow: 0 0 0 0.2rem rgba(67, 233, 123, 0.25) !important;
        background: rgba(255,255,255,1) !important;
    }

    .btn-animated {
        transition: all 0.3s ease;
        border: none;
    }

    .btn-animated:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .form-container {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255,255,255,0.8);
    }

    .card {
        border: none;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .info-card {
        transition: transform 0.3s ease;
    }

    .info-card:hover {
        transform: scale(1.02);
    }

    /* Form Animation */
    #createForm {
        animation: slideInUp 0.5s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Button Pulse Effect */
    .btn-primary {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(67, 233, 123, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(67, 233, 123, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(67, 233, 123, 0);
        }
    }
</style>

@endsection
