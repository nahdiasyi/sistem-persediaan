{{-- resources/views/permintaan/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Form Input -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Input Permintaan Barang</h5>
                </div>
                <div class="card-body">
                    <form id="formPermintaan">
                        <div class="form-group">
                            <label>No Permintaan</label>
                            <input type="text" class="form-control" id="id_permintaan" value="{{ $newId }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" required>
                        </div>

                        <hr>
                        <h6>Detail Barang</h6>

                        <div class="form-group">
                            <label>Barang</label>
                            <select class="form-control" id="id_barang">
                                <option value="">Pilih Barang</option>
                                @foreach ($barang as $b)
                                    <option value="{{ $b->Id_Barang }}">{{ $b->Nama_Barang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" class="form-control" id="jumlah">
                        </div>

                        <button type="button" class="btn btn-primary" id="btnTambah">Tambah Barang</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ringkasan Permintaan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detailBarang">
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success" id="btnSimpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jumlah</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_index">
                <div class="form-group">
                    <label>Jumlah Baru</label>
                    <input type="number" class="form-control" id="edit_jumlah">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnUpdateJumlah">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let detailBarang = [];

    function renderTable() {
        let html = '';
        detailBarang.forEach((item, index) => {
            html += `
                <tr>
                    <td>${item.nama_barang}</td>
                    <td>${item.jumlah}</td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-edit" data-index="${index}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-hapus" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#detailBarang').html(html);
    }

    $('#btnTambah').click(function() {
        let id_barang = $('#id_barang').val();
        let jumlah = parseInt($('#jumlah').val());

        if (!id_barang || !jumlah) {
            alert('Lengkapi data barang!');
            return;
        }

        let barang = $('#id_barang option:selected');
        let nama_barang = barang.text();

        detailBarang.push({
            id_barang: id_barang,
            nama_barang: nama_barang,
            jumlah: jumlah
        });

        renderTable();
        $('#id_barang').val('');
        $('#jumlah').val('');
    });

    $(document).on('click', '.btn-edit', function() {
        let index = $(this).data('index');
        $('#edit_index').val(index);
        $('#edit_jumlah').val(detailBarang[index].jumlah);
        $('#modalEdit').modal('show');
    });

    $('#btnUpdateJumlah').click(function() {
        let index = $('#edit_index').val();
        let newJumlah = parseInt($('#edit_jumlah').val());

        detailBarang[index].jumlah = newJumlah;

        renderTable();
        $('#modalEdit').modal('hide');
    });

    $(document).on('click', '.btn-hapus', function() {
        if (confirm('Yakin ingin menghapus item ini?')) {
            let index = $(this).data('index');
            detailBarang.splice(index, 1);
            renderTable();
        }
    });

    $('#btnSimpan').click(function() {
        if (!$('#tanggal').val()) {
            alert('Lengkapi tanggal!');
            return;
        }

        if (detailBarang.length === 0) {
            alert('Tambahkan minimal 1 barang!');
            return;
        }

        let data = {
            id_permintaan: $('#id_permintaan').val(),
            tanggal: $('#tanggal').val(),
            detail_barang: JSON.stringify(detailBarang)
        };

        $.ajax({
            url: '{{ route('permintaan.store') }}',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert('Data berhasil disimpan!');
                    window.location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan!');
            }
        });
    });
});
</script>
@endpush