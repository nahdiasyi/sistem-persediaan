@extends('layouts.app')
@section('title', 'Kategori')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-tags me-2"></i>Daftar Kategori
                    </h5>
                    <a href="{{ route('kategori.create') }}" class="btn btn-light btn-hover-primary">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </a>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.95);">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filter/Search Section -->
                    <div class="card mb-4" style="background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%); border: none;">
                        <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                            <h6 class="mb-0 text-white">
                                <i class="fas fa-search me-2"></i>Filter Pencarian
                            </h6>
                        </div>
                        <div class="card-body" style="background: rgba(255,255,255,0.9);">
                            <form method="GET" action="{{ route('kategori.index') }}" id="filterForm">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nama_kategori" class="form-label fw-bold">Nama Kategori:</label>
                                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                                               placeholder="Cari berdasarkan nama kategori..."
                                               value="{{ request('nama_kategori') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" style="color: transparent;">Action</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary me-2">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-refresh"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-hover" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border-radius: 10px; overflow: hidden;">
                            <thead style="background: rgba(0,0,0,0.1);">
                                <tr>
                                    <th class="text-white fw-bold">ID Kategori</th>
                                    <th class="text-white fw-bold">Nama Kategori</th>
                                    <th class="text-white fw-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategoris as $kategori)
                                    <tr style="background: rgba(255,255,255,0.9);" class="hover-row">
                                        <td class="fw-bold text-primary">{{ $kategori->id_kategori }}</td>
                                        <td class="fw-semibold">{{ $kategori->nama_kategori }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('kategori.edit', $kategori->id_kategori) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#detailModal{{ $kategori->id_kategori }}">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                                <form action="{{ route('kategori.destroy', $kategori->id_kategori) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus kategori ini?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center" style="background: rgba(255,255,255,0.9);">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Tidak ada data kategori</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Modals for Detail -->
                    @foreach($kategoris as $kategori)
                        <div class="modal fade" id="detailModal{{ $kategori->id_kategori }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                    <div class="modal-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                        <h5 class="modal-title fw-bold">
                                            <i class="fas fa-tags me-2"></i>Detail Kategori {{ $kategori->id_kategori }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body" style="background: rgba(255,255,255,0.9);">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #fa709a 0%, #fee140 100%); border-radius: 8px;">
                                                    <strong class="text-white">ID Kategori:</strong><br>
                                                    <span class="text-white">{{ $kategori->id_kategori }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); border-radius: 8px;">
                                                    <strong class="text-white">Nama Kategori:</strong><br>
                                                    <span class="text-white">{{ $kategori->nama_kategori }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($kategori->deskripsi)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="info-card p-3" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); border-radius: 8px;">
                                                    <strong class="text-white">Deskripsi:</strong><br>
                                                    <span class="text-white">{{ $kategori->deskripsi }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-row:hover {
        background: rgba(255,255,255,0.7) !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-hover-primary:hover {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%) !important;
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
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endsection
