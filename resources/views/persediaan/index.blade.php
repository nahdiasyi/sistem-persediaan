@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Persediaan</h2>
        <a href="{{ route('persediaan.create') }}" class="btn btn-primary">Tambah Persediaan</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Alokasi</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($persediaan as $p)
                        <tr>
                            <td>{{ $p->Id_Barang }}</td>
                            <td>{{ $p->barang->Nama_Barang }}</td>
                            <td>{{ $p->Alokasi }}</td>
                            <td>{{ $p->Stok }}</td>
                            <td>
                                <a href="{{ route('persediaan.edit', $p->Id_Barang) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $p->Id_Barang }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-delete').click(function() {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            var id = $(this).data('id');
            var form = $('#delete-form');
            form.attr('action', '/persediaan/' + id);
            form.submit();
        }
    });

    @if(session('success'))
        alert("{{ session('success') }}");
    @endif
});
</script>
@endpush
@endsection