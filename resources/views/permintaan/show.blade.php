@extends('layouts.app')

@section('title', 'Detail Permintaan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detail Permintaan: {{ $permintaan->id_permintaan }}</h3>
                    <div>
                        <a href="{{ route('permintaan.pdf', $permintaan->id_permintaan) }}"
                           class="btn btn-success me-2">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </a>
                        <a href="{{ route('permintaan.edit', $permintaan->id_permintaan) }}"
                           class="btn btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('permintaan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>ID Permintaan:</strong></td>
                                    <td>{{ $permintaan->id_permintaan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Supplier:</strong></td>
                                    <td>{{ $permintaan->supplier->nama_supplier ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal:</strong></td>
                                    <td>{{ $permintaan->tanggal->format('d F Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Dibuat:</strong></td>
                                    <td>{{ $permintaan->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diupdate:</strong></td>
                                    <td>{{ $permintaan->updated_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Item:</strong></td>
                                    <td>{{ $permintaan->detailPermintaan->count() }} item</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Detail Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="20%">Kode Barang</th>
                                    <th width="50%">Nama Barang</th>
                                    <th width="20%">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permintaan->detailPermintaan as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->kode_barang }}</td>
                                        <td>{{ $detail->barang->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                                        <td>{{ number_format($detail->jumlah) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada detail barang</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($permintaan->detailPermintaan->count() > 0)
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3">Total Jumlah:</th>
                                        <th>{{ number_format($permintaan->detailPermintaan->sum('jumlah')) }}</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
