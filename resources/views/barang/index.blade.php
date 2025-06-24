@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-box me-2"></i>Daftar Barang
                    </h5>
                    <a href="{{ route('barang.create') }}" class="btn btn-light btn-hover-primary">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </a>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.95);">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Satuan</th>
                                    <th>Merek</th>
                                    <th>Stok</th>
                                    <th>Keterangan</th>
                                    <th>Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangs as $barang)
                                    <tr>
                                        <td>{{ $barang->kode_barang }}</td>
                                        <td>{{ $barang->nama_barang }}</td>
                                        <td>Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                                        <td>{{ $barang->Satuan }}</td>
                                        <td>{{ $barang->merek }}</td>
                                        <td><span class="badge bg-secondary">{{ $barang->stok }}</span></td>
                                        <td>
                                            <span class="badge {{ $barang->keterangan == 'stok tersedia' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $barang->keterangan ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('barang.edit', $barang->kode_barang) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $barang->kode_barang }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <form action="{{ route('barang.destroy', $barang->kode_barang) }}" method="POST" onsubmit="return confirm('Hapus barang ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="detailModal{{ $barang->kode_barang }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">Detail Barang - {{ $barang->kode_barang }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Nama Barang:</strong> {{ $barang->nama_barang }}</p>
                                                            <p><strong>Satuan:</strong> {{ $barang->Satuan }}</p>
                                                            <p><strong>Merek:</strong> {{ $barang->merek }}</p>
                                                            <p><strong>Kategori:</strong> {{ $barang->kategori->nama_kategori ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Harga Beli:</strong> Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</p>
                                                            <p><strong>Harga Jual:</strong> Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                                                            <p><strong>Stok:</strong> {{ $barang->stok }}</p>
                                                            <p><strong>Keterangan:</strong> {{ $barang->keterangan ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
