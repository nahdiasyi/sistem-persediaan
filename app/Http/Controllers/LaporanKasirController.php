<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKasirController extends Controller
{
    public function index(Request $request)
    {
        // Ambil barang yang pernah terjual (yang punya detail penjualan)
        $listBarang = Barang::whereHas('detailPenjualan')->get();

        $query = Penjualan::with(['user', 'detailPenjualan.barang']);

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan barang
        if ($request->filled('cari_barang')) {
            $query->whereHas('detailPenjualan.barang', function ($q) use ($request) {
                $q->where('nama_barang', 'LIKE', '%' . $request->cari_barang . '%')
                  ->orWhere('kode_barang', 'LIKE', '%' . $request->cari_barang . '%');
            });
        }

        $penjualan = $query->orderBy('tanggal', 'desc')->paginate(10);

        // Statistik
        $statistik = $this->getStatistik($request);

        return view('laporan.kasir.index', compact('penjualan', 'statistik', 'listBarang'));
    }

    private function getStatistik($request)
    {
        $query = Penjualan::query();

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan barang jika ada
        if ($request->filled('cari_barang')) {
            $query->whereHas('detailPenjualan.barang', function ($q) use ($request) {
                $q->where('nama_barang', 'LIKE', '%' . $request->cari_barang . '%')
                  ->orWhere('kode_barang', 'LIKE', '%' . $request->cari_barang . '%');
            });
        }

        $totalTransaksi = $query->count();

        // Total item dan nilai
        $detailQuery = DetailPenjualan::whereIn('id_penjualan', $query->pluck('id_penjualan'));

        if ($request->filled('cari_barang')) {
            $detailQuery->whereHas('barang', function ($q) use ($request) {
                $q->where('nama_barang', 'LIKE', '%' . $request->cari_barang . '%')
                  ->orWhere('kode_barang', 'LIKE', '%' . $request->cari_barang . '%');
            });
        }

        $totalItem = $detailQuery->sum('jumlah');
        $totalNilai = $detailQuery->sum(DB::raw('jumlah * harga'));

        // User aktif (yang melakukan transaksi dalam periode)
        $userAktif = $query->distinct('id_user')->count('id_user');

        return [
            'total_transaksi' => $totalTransaksi,
            'total_item' => $totalItem,
            'total_nilai' => $totalNilai,
            'user_aktif' => $userAktif
        ];
    }

    public function generatePDF(Request $request)
    {
        $query = Penjualan::with(['user', 'detailPenjualan.barang']);

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan barang
        if ($request->filled('cari_barang')) {
            $query->whereHas('detailPenjualan.barang', function ($q) use ($request) {
                $q->where('nama_barang', 'LIKE', '%' . $request->cari_barang . '%')
                  ->orWhere('kode_barang', 'LIKE', '%' . $request->cari_barang . '%');
            });
        }

        $penjualan = $query->orderBy('tanggal', 'desc')->get();
        $statistik = $this->getStatistik($request);

        $listBarang = Barang::whereHas('detailPenjualan')->get();


        // Data untuk PDF
        $data = [
            'penjualan' => $penjualan,
            'statistik' => $statistik,
            'tanggal_dari' => $request->tanggal_dari,
            'tanggal_sampai' => $request->tanggal_sampai,
            'cari_barang' => $request->cari_barang,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('laporan.kasir.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'laporan-kasir-' . date('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    public function getBarang(Request $request)
    {
        $query = $request->get('q');
        $barang = Barang::where('nama_barang', 'LIKE', "%{$query}%")
                        ->orWhere('kode_barang', 'LIKE', "%{$query}%")
                        ->limit(10)
                        ->get(['kode_barang', 'nama_barang']);

        return response()->json($barang);
    }
}
