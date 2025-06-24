<?php
// File: resources/views/retur/edit.blade.php
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-white fw-bold">
                                <i class="fas fa-edit me-2"></i>Edit Retur #{{ $retur->id_retur }}
                            </h5>
                            <a href="{{ route('retur.index') }}" class="btn btn-light btn-hover-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body" style="background: rgba(255,255,255,0.95);">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Terjadi kesalahan!</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('retur.update', $retur->id_retur) }}" method="POST" id="returForm">
                            @csrf
                            @method('PUT')

                            <!-- Main Information Card -->
                            <div class="card mb-4" style="background: linear-gradient(45deg, #a8edea 0%, #fed6e3 100%); border: none;">
                                <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                    <h6 class="mb-0 text-white fw-bold">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Utama
                                    </h6>
                                </div>
                                <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="id_supplier" class="form-label fw-bold">
                                                <i class="fas fa-truck text-primary me-1"></i>Supplier
                                            </label>
                                            <select name="id_supplier" id="id_supplier" class="form-select" required>
                                                <option value="">Pilih Supplier</option>
                                                @foreach($supplier as $s)
                                                    <option value="{{ $s->id_supplier }}"
                                                            {{ $retur->id_supplier == $s->id_supplier ? 'selected' : '' }}>
                                                        {{ $s->nama_supplier }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="id_pembelian" class="form-label fw-bold">
                                                <i class="fas fa-shopping-cart text-success me-1"></i>ID Pembelian
                                            </label>
                                            <select name="id_pembelian" id="id_pembelian" class="form-select" required>
                                                <option value="">Pilih Pembelian</option>
                                                @foreach($pembelian as $p)
                                                    <option value="{{ $p->id_pembelian }}"
                                                            {{ $retur->id_pembelian == $p->id_pembelian ? 'selected' : '' }}>
                                                        {{ $p->id_pembelian }} - {{ $p->tanggal }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="id_user" class="form-label fw-bold">
                                                <i class="fas fa-user text-info me-1"></i>User
                                            </label>
                                            <select name="id_user" id="id_user" class="form-select" required>
                                                <option value="">Pilih User</option>
                                                @foreach($users as $u)
                                                    <option value="{{ $u->id_user }}"
                                                            {{ $retur->id_user == $u->id_user ? 'selected' : '' }}>
                                                        {{ $u->nama_user }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Barang Card -->
                            <div class="card mb-4" style="background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%); border: none;">
                                <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                    <h6 class="mb-0 text-white fw-bold">
                                        <i class="fas fa-boxes me-2"></i>Detail Barang Retur
                                    </h6>
                                    <button type="button" onclick="addDetail()" class="btn btn-light btn-sm">
                                        <i class="fas fa-plus me-1"></i>Tambah Barang
                                    </button>
                                </div>
                                <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                    <div id="detail-container">
                                        @foreach($retur->detailRetur as $i => $detail)
                                        <div class="detail-item" id="detail-existing-{{ $i }}">
                                            <button type="button" class="remove-btn" onclick="removeDetail('existing-{{ $i }}')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold text-white">
                                                        <i class="fas fa-box me-1"></i>Barang
                                                    </label>
                                                    <select name="details[{{ $i }}][kode_barang]" class="form-select" required>
                                                        <option value="">Pilih Barang</option>
                                                        @foreach($barang as $b)
                                                            <option value="{{ $b->kode_barang }}"
                                                                    {{ $detail->kode_barang == $b->kode_barang ? 'selected' : '' }}>
                                                                {{ $b->nama_barang }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label fw-bold text-white">
                                                        <i class="fas fa-sort-numeric-up me-1"></i>Jumlah
                                                    </label>
                                                    <input name="details[{{ $i }}][jumlah]" type="number" class="form-control"
                                                           value="{{ $detail->jumlah }}" min="1" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold text-white">
                                                        <i class="fas fa-money-bill-wave me-1"></i>Harga
                                                    </label>
                                                    <input name="details[{{ $i }}][harga]" type="number" class="form-control"
                                                           value="{{ $detail->harga }}" min="0" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold text-white">
                                                        <i class="fas fa-comment-alt me-1"></i>Alasan Retur
                                                    </label>
                                                    <input name="details[{{ $i }}][alasan]" type="text" class="form-control"
                                                           value="{{ $detail->alasan }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('retur.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Update Retur
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-hover-secondary:hover {
            background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%) !important;
            border: none;
            color: white !important;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .detail-item {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .detail-item .form-control, .detail-item .form-select {
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .remove-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: #dc3545;
            border: none;
            color: white;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }

        .card {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>

    <script>
        let detailIndex = {{ count($retur->detailRetur) }};

        function addDetail() {
            const container = document.getElementById('detail-container');

            const html = `
            <div class="detail-item" id="detail-${detailIndex}">
                <button type="button" class="remove-btn" onclick="removeDetail(${detailIndex})">
                    <i class="fas fa-times"></i>
                </button>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-white">
                            <i class="fas fa-box me-1"></i>Barang
                        </label>
                        <select name="details[${detailIndex}][kode_barang]" class="form-select" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->kode_barang }}">{{ $b->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold text-white">
                            <i class="fas fa-sort-numeric-up me-1"></i>Jumlah
                        </label>
                        <input name="details[${detailIndex}][jumlah]" type="number" class="form-control"
                               placeholder="Jumlah" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-white">
                            <i class="fas fa-money-bill-wave me-1"></i>Harga
                        </label>
                        <input name="details[${detailIndex}][harga]" type="number" class="form-control"
                               placeholder="Harga" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-white">
                            <i class="fas fa-comment-alt me-1"></i>Alasan Retur
                        </label>
                        <input name="details[${detailIndex}][alasan]" type="text" class="form-control"
                               placeholder="Alasan retur" required>
                    </div>
                </div>
            </div>`;

            container.insertAdjacentHTML('beforeend', html);
            detailIndex++;
        }

        function removeDetail(index) {
            const detailItem = document.getElementById(`detail-${index}`);
            if (detailItem) {
                detailItem.remove();
            }
        }
    </script>
@endsection
