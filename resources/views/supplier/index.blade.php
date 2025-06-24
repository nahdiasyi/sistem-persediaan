@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                        <h5 class="mb-0 text-white fw-bold">
                            <i class="fas fa-truck me-2"></i>Daftar Supplier
                        </h5>
                        <a href="{{ route('supplier.create') }}" class="btn btn-light btn-hover-primary">
                            <i class="fas fa-plus"></i> Tambah Supplier
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
                                <form method="GET" action="{{ route('supplier.index') }}" id="filterForm">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="search_nama" class="form-label fw-bold">Nama Supplier:</label>
                                            <input type="text" class="form-control" id="search_nama" name="search_nama"
                                                   placeholder="Cari nama supplier..."
                                                   value="{{ request('search_nama') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="search_alamat" class="form-label fw-bold">Alamat:</label>
                                            <input type="text" class="form-control" id="search_alamat" name="search_alamat"
                                                   placeholder="Cari alamat..."
                                                   value="{{ request('search_alamat') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="search_telp" class="form-label fw-bold">No Telepon:</label>
                                            <input type="text" class="form-control" id="search_telp" name="search_telp"
                                                   placeholder="Cari no telepon..."
                                                   value="{{ request('search_telp') }}">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary me-2">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                            <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
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
                                        <th class="text-white fw-bold">ID Supplier</th>
                                        <th class="text-white fw-bold">Nama Supplier</th>
                                        <th class="text-white fw-bold">Alamat</th>
                                        <th class="text-white fw-bold">No Telepon</th>
                                        <th class="text-white fw-bold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($suppliers as $supplier)
                                        <tr style="background: rgba(255,255,255,0.9);" class="hover-row">
                                            <td class="fw-bold text-primary">{{ $supplier->id_supplier }}</td>
                                            <td class="fw-bold">{{ $supplier->nama_supplier }}</td>
                                            <td>{{ $supplier->alamat_supplier }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $supplier->no_telp }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('supplier.edit', $supplier->id_supplier) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $supplier->id_supplier }}">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </button>
                                                    <form action="{{ route('supplier.destroy', $supplier->id_supplier) }}"
                                                          method="POST" style="display:inline;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger delete-btn">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center" style="background: rgba(255,255,255,0.9);">
                                                <div class="py-4">
                                                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Tidak ada data supplier</h5>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination if using paginate -->
                        @if(method_exists($suppliers, 'links'))
                            <div class="d-flex justify-content-center mt-4">
                                {{ $suppliers->links() }}
                            </div>
                        @endif

                        <!-- Modals for Detail -->
                        @foreach($suppliers as $supplier)
                            <div class="modal fade" id="detailModal{{ $supplier->id_supplier }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                        <div class="modal-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fas fa-truck me-2"></i>Detail Supplier {{ $supplier->nama_supplier }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body" style="background: rgba(255,255,255,0.9);">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #fa709a 0%, #fee140 100%); border-radius: 8px;">
                                                        <strong class="text-white">ID Supplier:</strong><br>
                                                        <span class="text-white fs-5">{{ $supplier->id_supplier }}</span>
                                                    </div>
                                                    <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); border-radius: 8px;">
                                                        <strong class="text-white">Nama Supplier:</strong><br>
                                                        <span class="text-white fs-5">{{ $supplier->nama_supplier }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%); border-radius: 8px;">
                                                        <strong class="text-white">Alamat:</strong><br>
                                                        <span class="text-white">{{ $supplier->alamat_supplier }}</span>
                                                    </div>
                                                    <div class="info-card p-3" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); border-radius: 8px;">
                                                        <strong class="text-white">No Telepon:</strong><br>
                                                        <span class="text-white fs-5">{{ $supplier->no_telp }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="background: rgba(255,255,255,0.1); border-top: 1px solid rgba(255,255,255,0.2);">
                                            <a href="{{ route('supplier.edit', $supplier->id_supplier) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i> Edit Supplier
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

        .delete-btn:hover {
            background: linear-gradient(45deg, #ff6b6b 0%, #ee5a5a 100%) !important;
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }

        .btn-group .btn {
            margin-right: 5px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }
    </style>

    <script>
        // Confirmation for delete
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin menghapus supplier ini?')) {
                    this.submit();
                }
            });
        });

        // Auto submit form on input change (optional)
        document.querySelectorAll('#filterForm input').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 500);
            });
        });
    </script>
@endsection
