<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianController;
// use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\LaporanKasirController;
use App\Http\Controllers\LaporanBarangController;
use App\Http\Controllers\LaporanPembelianController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\LaporanPersediaanController;
use App\Http\Controllers\LaporanBarangMasukController;

// Login routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/pembelian/export-pdf', [LaporanPembelianController::class, 'exportPdf'])->name('pembelian.export-pdf');
// Dashboard & Protected Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');

Route::resource('user', UserController::class);
Route::resource('supplier', SupplierController::class);
Route::resource('kategori', KategoriController::class);
Route::resource('barang', BarangController::class);

Route::resource('pembelian', PembelianController::class);


// Tambahkan routes ini ke web.php

Route::middleware(['auth'])->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update.password');
    });
});


// Resource routes untuk CRUD permintaan
    Route::resource('permintaan', PermintaanController::class)->parameters([
        'permintaan' => 'id_permintaan'
    ]);

    // Route khusus untuk export PDF
    Route::get('permintaan/{id_permintaan}/pdf', [PermintaanController::class, 'exportPdf'])
        ->name('permintaan.pdf');

    // Route API untuk mendapatkan data barang (untuk AJAX)
    Route::get('api/barang', [PermintaanController::class, 'getBarang'])
        ->name('api.barang');
Route::get('/permintaan/{id_permintaan}/edit', [PermintaanController::class, 'edit'])
     ->name('permintaan.edit');

// // Routes untuk Penjualan
// Route::prefix('penjualan')->name('penjualan.')->group(function () {
//     Route::get('/', [PenjualanController::class, 'index'])->name('index');
//     Route::get('/create', [PenjualanController::class, 'create'])->name('create');
//     Route::post('/store', [PenjualanController::class, 'store'])->name('store');
//     Route::get('/{id}/edit', [PenjualanController::class, 'edit'])->name('edit');
//     Route::put('/{id}', [PenjualanController::class, 'update'])->name('update');
// });

Route::get('/retur', [ReturController::class, 'index'])->name('retur.index');
Route::get('/retur/create', [ReturController::class, 'create'])->name('retur.create');
Route::post('/retur', [ReturController::class, 'store'])->name('retur.store');
Route::get('/retur/{id}/edit', [ReturController::class, 'edit'])->name('retur.edit');
Route::put('/retur/{id}', [ReturController::class, 'update'])->name('retur.update');

Route::patch('/retur/{id}/status', [ReturController::class, 'updateStatus'])->name('retur.updateStatus');

// Route untuk laporan barang
Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/barang', [LaporanBarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/pdf', [LaporanBarangController::class, 'exportPdf'])->name('barang.pdf');
});

// Laporan pembelian
Route::prefix('laporan')->group(function () {
    Route::get('/pembelian', [LaporanPembelianController::class, 'index'])->name('laporan.pembelian.index');
    Route::get('/pembelian/pdf', [LaporanPembelianController::class, 'exportPdf'])->name('laporan.pembelian.export-pdf');
});

//laporan penjualan
// Route::get('/laporan/penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
// Route::get('/laporan/penjualan/pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('laporan.penjualan.pdf');

//laporan kasir
Route::group(['prefix' => 'laporan'], function() {
    Route::get('/kasir', [LaporanKasirController::class, 'index'])->name('laporan.kasir.index');
    Route::get('/kasir/pdf', [LaporanKasirController::class, 'generatePDF'])->name('laporan.kasir.pdf');
    Route::get('/kasir/barang', [LaporanKasirController::class, 'getBarang'])->name('laporan.kasir.barang');
});
//halaman kasir penjualan
Route::prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/penjualan', [KasirController::class, 'index'])->name('penjualan');
        Route::post('/penjualan', [KasirController::class, 'store'])->name('penjualan.store');
        Route::get('/barang/search', [KasirController::class, 'getBarang'])->name('barang.search');
        Route::get('/print/{id}', [KasirController::class, 'print'])->name('print');
    });
