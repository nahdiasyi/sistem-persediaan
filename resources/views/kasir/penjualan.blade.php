@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-cash-register me-2"></i>Kasir - Input Penjualan</h4>
                </div>
                <div class="card-body">
                    <!-- Form Pencarian Barang -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="searchBarang" class="form-label">Cari Barang</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchBarang"
                                       placeholder="Ketik nama barang atau kode barang...">
                                <button class="btn btn-outline-secondary" type="button" id="btnCari">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total Transaksi</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control form-control-lg text-end fw-bold"
                                       id="totalTransaksi" value="0" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Hasil Pencarian -->
                    <div id="hasilPencarian" class="mb-3" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <small class="text-muted">Hasil Pencarian</small>
                            </div>
                            <div class="card-body p-2">
                                <div id="listBarang" class="row"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Keranjang -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Keranjang Belanja</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="15%">Kode</th>
                                            <th width="35%">Nama Barang</th>
                                            <th width="15%" class="text-center">Qty</th>
                                            <th width="20%" class="text-end">Harga</th>
                                            <th width="20%" class="text-end">Subtotal</th>
                                            <th width="5%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="keranjangBody">
                                        <tr id="emptyCart">
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-shopping-cart fa-2x mb-2"></i><br>
                                                Keranjang masih kosong
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-warning btn-lg" onclick="clearKeranjang()">
                                <i class="fas fa-trash me-2"></i>Bersihkan Keranjang
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-success btn-lg" id="btnProses" onclick="prosesTransaksi()" disabled>
                                <i class="fas fa-check me-2"></i>Proses Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Loading -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 mb-0">Memproses transaksi...</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let keranjang = [];
let searchTimeout;

$(document).ready(function() {
    // Event listener untuk pencarian
    $('#searchBarang').on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();

        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                cariBarang(query);
            }, 300);
        } else {
            $('#hasilPencarian').hide();
        }
    });

    // Enter key untuk pencarian
    $('#searchBarang').on('keypress', function(e) {
        if (e.which === 13) {
            cariBarang($(this).val());
        }
    });

    $('#btnCari').click(function() {
        cariBarang($('#searchBarang').val());
    });
});

function cariBarang(query) {
    if (query.length < 2) return;

    $.ajax({
        url: '{{ route("kasir.barang.search") }}',
        method: 'GET',
        data: { q: query },
        success: function(response) {
            tampilkanHasilPencarian(response);
        },
        error: function() {
            Swal.fire('Error', 'Terjadi kesalahan saat mencari barang', 'error');
        }
    });
}

function tampilkanHasilPencarian(barang) {
    const container = $('#listBarang');
    container.empty();

    if (barang.length === 0) {
        container.html('<div class="col-12"><p class="text-muted text-center">Barang tidak ditemukan</p></div>');
    } else {
        barang.forEach(function(item) {
            const card = `
                <div class="col-md-6 col-lg-4 mb-2">
                    <div class="card border-0 shadow-sm h-100" style="cursor: pointer;" onclick="tambahKeKeranjang('${item.kode_barang}', '${item.nama_barang}', ${item.harga_jual}, ${item.stok})">
                        <div class="card-body p-3">
                            <h6 class="card-title mb-1">${item.nama_barang}</h6>
                            <p class="card-text small text-muted mb-1">Kode: ${item.kode_barang}</p>
                            <p class="card-text small mb-1">Stok: <span class="badge bg-info">${item.stok}</span></p>
                            <p class="card-text fw-bold text-primary">Rp ${numberFormat(item.harga_jual)}</p>
                        </div>
                    </div>
                </div>
            `;
            container.append(card);
        });
    }

    $('#hasilPencarian').show();
}

function tambahKeKeranjang(kode, nama, harga, stok) {
    // Cek apakah barang sudah ada di keranjang
    const existingIndex = keranjang.findIndex(item => item.kode_barang === kode);

    if (existingIndex !== -1) {
        // Jika sudah ada, tambah quantity
        if (keranjang[existingIndex].jumlah < stok) {
            keranjang[existingIndex].jumlah++;
            keranjang[existingIndex].subtotal = keranjang[existingIndex].jumlah * keranjang[existingIndex].harga;
        } else {
            Swal.fire('Peringatan', 'Stok tidak mencukupi!', 'warning');
            return;
        }
    } else {
        // Jika belum ada, tambah item baru
        if (stok > 0) {
            keranjang.push({
                kode_barang: kode,
                nama_barang: nama,
                harga: harga,
                jumlah: 1,
                subtotal: harga,
                stok: stok
            });
        } else {
            Swal.fire('Peringatan', 'Stok habis!', 'warning');
            return;
        }
    }

    updateKeranjang();
    $('#hasilPencarian').hide();
    $('#searchBarang').val('').focus();
}

function updateKeranjang() {
    const tbody = $('#keranjangBody');
    tbody.empty();

    if (keranjang.length === 0) {
        tbody.html(`
            <tr id="emptyCart">
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="fas fa-shopping-cart fa-2x mb-2"></i><br>
                    Keranjang masih kosong
                </td>
            </tr>
        `);
        $('#btnProses').prop('disabled', true);
    } else {
        let total = 0;
        keranjang.forEach(function(item, index) {
            total += item.subtotal;
            const row = `
                <tr>
                    <td>${item.kode_barang}</td>
                    <td>${item.nama_barang}</td>
                    <td class="text-center">
                        <div class="input-group input-group-sm" style="width: 100px;">
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="ubahQty(${index}, -1)">-</button>
                            <input type="number" class="form-control text-center" value="${item.jumlah}" onchange="ubahQtyManual(${index}, this.value)" min="1" max="${item.stok}">
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="ubahQty(${index}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-end">Rp ${numberFormat(item.harga)}</td>
                    <td class="text-end fw-bold">Rp ${numberFormat(item.subtotal)}</td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-sm" onclick="hapusDariKeranjang(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
        $('#btnProses').prop('disabled', false);
        $('#totalTransaksi').val(numberFormat(total));
    }
}

function ubahQty(index, change) {
    const newQty = keranjang[index].jumlah + change;

    if (newQty <= 0) {
        hapusDariKeranjang(index);
        return;
    }

    if (newQty > keranjang[index].stok) {
        Swal.fire('Peringatan', 'Quantity melebihi stok yang tersedia!', 'warning');
        return;
    }

    keranjang[index].jumlah = newQty;
    keranjang[index].subtotal = keranjang[index].jumlah * keranjang[index].harga;
    updateKeranjang();
}

function ubahQtyManual(index, newQty) {
    newQty = parseInt(newQty) || 1;

    if (newQty <= 0) {
        hapusDariKeranjang(index);
        return;
    }

    if (newQty > keranjang[index].stok) {
        Swal.fire('Peringatan', 'Quantity melebihi stok yang tersedia!', 'warning');
        updateKeranjang();
        return;
    }

    keranjang[index].jumlah = newQty;
    keranjang[index].subtotal = keranjang[index].jumlah * keranjang[index].harga;
    updateKeranjang();
}

function hapusDariKeranjang(index) {
    keranjang.splice(index, 1);
    updateKeranjang();
}

function clearKeranjang() {
    if (keranjang.length === 0) return;

    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menghapus semua item dari keranjang?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            keranjang = [];
            updateKeranjang();
        }
    });
}

function prosesTransaksi() {
    if (keranjang.length === 0) {
        Swal.fire('Peringatan', 'Keranjang masih kosong!', 'warning');
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Transaksi',
        html: `Total transaksi: <strong>Rp ${$('#totalTransaksi').val()}</strong><br>Proses transaksi ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Proses',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            simpanTransaksi();
        }
    });
}

function simpanTransaksi() {
    $('#loadingModal').modal('show');

    $.ajax({
        url: '{{ route("kasir.penjualan.store") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            items: keranjang
        },
        success: function(response) {
            $('#loadingModal').modal('hide');

            if (response.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Transaksi berhasil disimpan',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#28a745',
                    confirmButtonText: 'Cetak Nota',
                    cancelButtonText: 'Transaksi Baru'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buka halaman cetak di tab baru
                        window.open(response.print_url, '_blank');
                    }

                    // Reset form
                    keranjang = [];
                    updateKeranjang();
                    $('#searchBarang').focus();
                });
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            $('#loadingModal').modal('hide');
            let message = 'Terjadi kesalahan saat menyimpan transaksi';

            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            Swal.fire('Error', message, 'error');
        }
    });
}

function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s;
}

.input-group-sm .form-control {
    font-size: 0.875rem;
}

#totalTransaksi {
    font-size: 1.5rem !important;
    color: #28a745 !important;
}

.table th {
    border-top: none;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
@endpush
