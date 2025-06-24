<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use Barryvdh\DomPDF\PDF;
use App\Models\Penjualan;
use Illuminate\Http\Request;


class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $barangList = Barang::all();

        $query = Penjualan::with('detailPenjualan.barang', 'user');

        if ($request->filled('barang')) {
            $query->whereHas('detailPenjualan', function ($q) use ($request) {
                $q->where('kode_barang', $request->barang);
            });
        }

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', Carbon::parse($request->dari));
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', Carbon::parse($request->sampai));
        }

        $penjualans = $query->get();

        $totalTransaksi = $penjualans->count();
        $totalItem = $penjualans->sum(fn($p) => $p->detailPenjualan->sum('jumlah'));
        $totalNilai = $penjualans->sum(fn($p) => $p->detailPenjualan->sum(fn($d) => $d->jumlah * $d->harga));
        $userAktif = $penjualans->pluck('id_user')->unique()->count();

        return view('laporan.penjualan.index', compact(
            'penjualans', 'barangList', 'totalTransaksi', 'totalItem', 'totalNilai', 'userAktif'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = Penjualan::with('detailPenjualan.barang', 'user');

        if ($request->filled('barang')) {
            $query->whereHas('detailPenjualan', function ($q) use ($request) {
                $q->where('kode_barang', $request->barang);
            });
        }

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', Carbon::parse($request->dari));
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', Carbon::parse($request->sampai));
        }

        $penjualans = $query->get();

        $pdf = PDF::loadView('laporan.penjualan.pdf', compact('penjualans'));
        return $pdf->stream('laporan-penjualan.pdf');
    }
}
