@extends('layouts.app')
@section('title', 'User')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-users me-2"></i>Daftar User
                    </h5>
                    <a href="{{ route('user.create') }}" class="btn btn-light btn-hover-primary">
                        <i class="fas fa-plus"></i> Tambah User
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
                            <form method="GET" action="{{ route('user.index') }}" id="filterForm">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="nama_user" class="form-label fw-bold">Nama User:</label>
                                        <input type="text" class="form-control" id="nama_user" name="nama_user"
                                               placeholder="Cari berdasarkan nama user..."
                                               value="{{ request('nama_user') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="role" class="form-label fw-bold">Role:</label>
                                        <select class="form-select" id="role" name="role">
                                            <option value="">Semua Role</option>
                                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                            <option value="peminjam" {{ request('role') == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold" style="color: transparent;">Action</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary me-2">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                            <a href="{{ route('user.index') }}" class="btn btn-secondary">
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
                                    <th class="text-white fw-bold">ID User</th>
                                    <th class="text-white fw-bold">Nama User</th>
                                    <th class="text-white fw-bold">Alamat</th>
                                    <th class="text-white fw-bold">No Telp</th>
                                    <th class="text-white fw-bold">Username</th>
                                    <th class="text-white fw-bold">Role</th>
                                    <th class="text-white fw-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr style="background: rgba(255,255,255,0.9);" class="hover-row">
                                        <td class="fw-bold text-primary">{{ $user->id_user }}</td>
                                        <td class="fw-semibold">{{ $user->nama_user }}</td>
                                        <td>{{ Str::limit($user->alamat, 30) }}</td>
                                        <td>{{ $user->no_telp }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            <span class="badge
                                                @if($user->role == 'admin') bg-danger
                                                @elseif($user->role == 'petugas') bg-warning
                                                @else bg-success
                                                @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('user.edit', $user->id_user) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#detailModal{{ $user->id_user }}">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                                <form action="{{ route('user.destroy', $user->id_user) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user ini?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center" style="background: rgba(255,255,255,0.9);">
                                            <div class="py-4">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Tidak ada data user</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination if needed -->
                    @if(method_exists($users, 'links'))
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif

                    <!-- Modals for Detail -->
                    @foreach($users as $user)
                        <div class="modal fade" id="detailModal{{ $user->id_user }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                    <div class="modal-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                        <h5 class="modal-title fw-bold">
                                            <i class="fas fa-user me-2"></i>Detail User {{ $user->id_user }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body" style="background: rgba(255,255,255,0.9);">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #fa709a 0%, #fee140 100%); border-radius: 8px;">
                                                    <strong class="text-white">ID User:</strong><br>
                                                    <span class="text-white">{{ $user->id_user }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); border-radius: 8px;">
                                                    <strong class="text-white">Nama User:</strong><br>
                                                    <span class="text-white">{{ $user->nama_user }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); border-radius: 8px;">
                                                    <strong class="text-white">Username:</strong><br>
                                                    <span class="text-white">{{ $user->username }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #ff9a9e 0%, #fecfef 100%); border-radius: 8px;">
                                                    <strong class="text-white">Role:</strong><br>
                                                    <span class="text-white">{{ ucfirst($user->role) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #ffecd2 0%, #fcb69f 100%); border-radius: 8px;">
                                                    <strong class="text-dark">No Telp:</strong><br>
                                                    <span class="text-dark">{{ $user->no_telp }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card p-3 mb-3" style="background: linear-gradient(45deg, #a1c4fd 0%, #c2e9fb 100%); border-radius: 8px;">
                                                    <strong class="text-dark">Alamat:</strong><br>
                                                    <span class="text-dark">{{ $user->alamat }}</span>
                                                </div>
                                            </div>
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

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.6em;
    }

    .table td {
        vertical-align: middle;
    }

    .pagination .page-link {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        margin: 0 2px;
        border-radius: 5px;
    }

    .pagination .page-link:hover {
        background: linear-gradient(45deg, #764ba2 0%, #667eea 100%);
        color: white;
        transform: translateY(-1px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
        border: none;
    }
</style>
@endsection
