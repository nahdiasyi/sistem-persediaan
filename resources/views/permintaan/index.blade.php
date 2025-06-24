@extends('layouts.app')

@section('title', 'Daftar Permintaan')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-clipboard-list me-2"></i>Daftar Permintaan
                    </h5>
                    <a href="{{ route('permintaan.create') }}" class="btn btn-light btn-hover-primary">
                        <i class="fas fa-plus"></i> Tambah Permintaan
                    </a>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.95);">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
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
                            <form method="GET" action="{{ route('permintaan.index') }}" id="filterForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="tanggal_dari" class="form-label fw-bold">Tanggal Dari:</label>
                                        <input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari"
                                               value="{{ request('tanggal_dari') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tanggal_sampai" class="form-label fw-bold">Tanggal Sampai:</label>
                                        <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai"
                                               value="{{ request('tanggal_sampai') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="supplier" class="form-label fw-bold">Supplier:</label>
                                        <select class="form-select" id="supplier" name="supplier">
                                            <option value="">Semua Supplier</option>
                                            @foreach($suppliers ?? [] as $supplier)
                                                <option value="{{ $supplier->id_supplier }}"
                                                        {{ request('supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                                                    {{ $supplier->nama_supplier }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="search" class="form-label fw-bold">Cari ID Permintaan:</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                               placeholder="Masukkan ID Permintaan" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ route('permintaan.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-refresh"></i> Reset
                                        </a>
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
                                    <th class="text-white fw-bold">No</th>
                                    <th class="text-white fw-bold">ID Permintaan</th>
                                    <th class="text-white fw-bold">Supplier</th>
                                    <th class="text-white fw-bold">Tanggal</th>
                                    <th class="text-white fw-bold">Total Item</th>
                                    <th class="text-white fw-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permintaan as $index => $item)
                                    <tr style="background: rgba(255,255,255,0.9);" class="hover-row">
                                        <td class="fw-bold">{{ $permintaan->firstItem() + $index }}</td>
                                        <td class="fw-bold text-primary">{{ $item->id_permintaan }}</td>
                                        <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                                        <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                                        <td><span class="badge bg-info">{{ $item->detailPermintaan->count() }} item</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('permintaan.show', $item->id_permintaan) }}"
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('permintaan.edit', $item->id_permintaan) }}"
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('permintaan.pdf', $item->id_permintaan) }}"
                                                   class="btn btn-success btn-sm">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete('{{ $item->id_permintaan }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-form-{{ $item->id_permintaan }}"
                                                      action="{{ route('permintaan.destroy', $item->id_permintaan) }}"
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center" style="background: rgba(255,255,255,0.9);">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Tidak ada data permintaan</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $permintaan->links() }}
                    </div>
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
        transition: all 0.3s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
</style>

<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus permintaan ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
