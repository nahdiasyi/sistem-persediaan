<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class LaporanBarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $barangMasuk = DB::table('barang_masuk as bm')
            ->join('detail_barang_masuk as dbm', 'bm.Id_Barang_Masuk', '=', 'dbm.Id_Barang_Masuk')
            ->join('barang as b', 'dbm.Id_barang', '=', 'b.Id_Barang')
            ->select('bm.Tanggal', 'dbm.Id_barang', 'b.Nama_Barang', 'b.Id_Kategori', 'dbm.Jumlah')
            ->when($search, function($query) use ($search) {
                return $query->where('b.Nama_Barang', 'like', "%{$search}%")
                           ->orWhere('dbm.Id_barang', 'like', "%{$search}%");
            })
            ->orderBy('bm.Tanggal', 'desc')
            ->get();

            // if($request->has('pdf')) {
            //     $pdf = PDF::loadView('barang_masuk.barang_masuk_pdf', compact('barangMasuk'));
            //     return $pdf->download('laporan_barang_masuk.pdf');
            // }

            return view('barang_masuk.barang_masuk', compact('barangMasuk', 'search'));
        }
    }


