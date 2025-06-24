<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanBarangController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::all();

        $query = Barang::with('kategori');

        if ($request->filled('nama_barang')) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }

        if ($request->filled('merek')) {
            $query->where('merek', 'like', '%' . $request->merek . '%');
        }

        if ($request->filled('id_kategori')) {
            $query->where('id_kategori', $request->id_kategori);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $barangs = $query->get();

        $totalBarang = $barangs->count();
        $totalNilaiStok = $barangs->sum(fn($b) => $b->harga_beli * ($b->stok ?? 0));
        $totalNilaiJual = $barangs->sum(fn($b) => $b->harga_jual * ($b->stok ?? 0));

        return view('laporan.barang.index', compact(
            'barangs', 'kategoris', 'totalBarang', 'totalNilaiStok', 'totalNilaiJual', 'request'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->filled('nama_barang')) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }

        if ($request->filled('merek')) {
            $query->where('merek', 'like', '%' . $request->merek . '%');
        }

        if ($request->filled('id_kategori')) {
            $query->where('id_kategori', $request->id_kategori);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $barangs = $query->get();

        $totalBarang = $barangs->count();
        $totalNilaiStok = $barangs->sum(fn($b) => $b->harga_beli * ($b->stok ?? 0));
        $totalNilaiJual = $barangs->sum(fn($b) => $b->harga_jual * ($b->stok ?? 0));

        $data = [
            'barangs' => $barangs,
            'totalBarang' => $totalBarang,
            'totalNilaiStok' => $totalNilaiStok,
            'totalNilaiJual' => $totalNilaiJual,
            'tanggal_cetak' => Carbon::now()->format('d/m/Y H:i:s'),
            'filter' => [
                'nama_barang' => $request->nama_barang,
                'merek' => $request->merek,
                'kategori' => $request->id_kategori ? Kategori::find($request->id_kategori)->nama_kategori : null,
                'tanggal_dari' => $request->tanggal_dari,
                'tanggal_sampai' => $request->tanggal_sampai,
            ]
        ];

        $pdf = PDF::loadView('laporan.barang.pdf', $data)->setPaper('A4', 'landscape');

        return $pdf->download('laporan-barang-' . date('Y-m-d-H-i-s') . '.pdf');
    }
}
