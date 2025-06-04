<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\LaporanPersediaanController;
use App\Http\Controllers\LaporanBarangMasukController;

// Login routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard & Protected Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');

Route::resource('user', UserController::class);
Route::resource('supplier', SupplierController::class);
Route::resource('kategori', KategoriController::class);
Route::resource('barang', BarangController::class);
Route::resource('pembelian', PembelianController::class);


// ✅ Gunakan route grup penjualan tanpa duplikat use
Route::prefix('penjualan')->name('penjualan.')->group(function () {
    Route::get('/', [PenjualanController::class, 'index'])->name('index');
    Route::get('/create', [PenjualanController::class, 'create'])->name('create');
    Route::post('/', [PenjualanController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PenjualanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PenjualanController::class, 'update'])->name('update');
    Route::delete('/{id}', [PenjualanController::class, 'destroy'])->name('destroy');
});

Route::get('/retur', [ReturController::class, 'index'])->name('retur.index');
Route::get('/retur/create', [ReturController::class, 'create'])->name('retur.create');
Route::post('/retur', [ReturController::class, 'store'])->name('retur.store');
Route::get('/retur/{id}/edit', [ReturController::class, 'edit'])->name('retur.edit');
Route::put('/retur/{id}', [ReturController::class, 'update'])->name('retur.update');

Route::patch('/retur/{id}/status', [ReturController::class, 'updateStatus'])->name('retur.updateStatus');


// Laporan Barang Masuk
Route::get('/laporan/barang-masuk', [LaporanBarangMasukController::class, 'index'])->name('laporan.barang-masuk.index');
Route::get('/laporan/barang-masuk/pdf', [LaporanBarangMasukController::class, 'generatePDF'])->name('laporan.barang-masuk.pdf');
