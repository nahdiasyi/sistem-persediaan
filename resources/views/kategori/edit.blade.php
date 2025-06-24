@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-edit me-2"></i>Edit Kategori
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
                            <li class="breadcrumb-item active text-white" aria-current="page">Edit Kategori</li>
                        </ol>
                    </nav>

                    <!-- Form Section -->
                    <div class="form-container p-4" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 15px;">
                        <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST" id="editForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-4">
                                        <input type="text"
                                               class="form-control form-control-lg @error('nama_kategori') is-invalid @enderror"
                                               id="nama_kategori"
                                               name="nama_kategori"
                                               value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
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
                                          style="height: 100px; background: rgba(255,255,255,0.9); border: 2px solid #dee2e6;">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
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
                                    <button type="submit" class="btn btn-success btn-lg me-2 btn-animated">
                                        <i class="fas fa-save me-2"></i>Update Kategori
                                    </button>
                                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary btn-lg btn-animated">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
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
</style>

@endsection
