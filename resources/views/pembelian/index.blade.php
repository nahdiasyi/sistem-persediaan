@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                        <h5 class="mb-0 text-white fw-bold">
                            <i class="fas fa-shopping-cart me-2"></i>Daftar Pembelian
                        </h5>
                        <a href="{{ route('pembelian.create') }}" class="btn btn-light btn-hover-primary">
                            <i class="fas fa-plus"></i> Tambah Pembelian
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
                                <form method="GET" action="{{ route('pembelian.index') }}" id="filterForm">
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
                                            <label for="user" class="form-label fw-bold">User:</label>
                                            <select class="form-select" id="user" name="user">
                                                <option value="">Semua User</option>
                                                @foreach($users ?? [] as $user)
                                                    <option value="{{ $user->id_user }}"
                                                            {{ request('user') == $user->id_user ? 'selected' : '' }}>
                                                        {{ $user->nama_user }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary me-2">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">
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
                                        <th class="text-white fw-bold">ID Pembelian</th>
                                        <th class="text-white fw-bold">Tanggal</th>
                                        <th class="text-white fw-bold">Supplier</th>
                                        <th class="text-white fw-bold">User</th>
                                        <th class="text-white fw-bold">Total Item</th>
                                        <th class="text-white fw-bold">Total Harga</th>
                                        <th class="text-white fw-bold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pembelian as $item)
                                        <tr style="background: rgba(255,255,255,0.9);" class="hover-row">
                                            <td class="fw-bold text-primary">{{ $item->id_pembelian }}</td>
                                            <td>{{ $item->tanggal }}</td>
                                            <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                                            <td>{{ $item->user->nama_user ?? '-' }}</td>
                                            <td><span class="badge bg-info">{{ $item->detailPembelian->sum('jumlah') }}</span></td>
                                            <td class="fw-bold text-success">
                                                Rp {{ number_format($item->detailPembelian->sum(function ($detail) {
                                                    return $detail->jumlah * $detail->harga;
                                                }), 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('pembelian.edit', $item->id_pembelian) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $item->id_pembelian }}">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center" style="background: rgba(255,255,255,0.9);">
                                                <div class="py-4">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Tidak ada data pembelian</h5>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Modals for Detail -->
                        @foreach($pembelian as $item)
                            <div class="modal fade" id="detailModal{{ $item->id_pembelian }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                        <div class="modal-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fas fa-receipt me-2"></i>Detail Pembelian {{ $item->id_pembelian }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body" style="background: rgba(255,255,255,0.9);">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="info-card p-3 mb-2" style="background: linear-gradient(45deg, #fa709a 0%, #fee140 100%); border-radius: 8px;">
                                                        <strong class="text-white">Tanggal:</strong><br>
                                                        <span class="text-white">{{ $item->tanggal ?? '-' }}</span>
                                                    </div>
                                                    <div class="info-card p-3" style="background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); border-radius: 8px;">
                                                        <strong class="text-white">Supplier:</strong><br>
                                                        <span class="text-white">{{ $item->supplier->nama_supplier ?? '-' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-card p-3" style="background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%); border-radius: 8px;">
                                                        <strong class="text-white">User:</strong><br>
                                                        <span class="text-white">{{ $item->user->nama_user ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; overflow: hidden;">
                                                    <thead style="background: rgba(0,0,0,0.1);">
                                                        <tr>
                                                            <th class="text-white">Barang</th>
                                                            <th class="text-white">Jumlah</th>
                                                            <th class="text-white">Harga</th>
                                                            <th class="text-white">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($item->detailPembelian as $detail)
                                                            <tr style="background: rgba(255,255,255,0.9);">
                                                                <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                                                                <td><span class="badge bg-primary">{{ $detail->jumlah }}</span></td>
                                                                <td class="text-success fw-bold">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                                <td class="text-success fw-bold">
                                                                    Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
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
    </style>
@endsection
