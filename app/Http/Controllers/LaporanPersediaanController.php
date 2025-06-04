<?php

namespace App\Http\Controllers;

use App\Models\LaporanPersediaan;
use Illuminate\Http\Request;
use PDF;

class LaporanPersediaanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        
        $persediaan = LaporanPersediaan::when($search, function($query) use ($search) {
            return $query->where('Nama_Barang', 'like', "%{$search}%");
        })->get();
        
        if($request->has('pdf')) {
            $pdf = PDF::loadView('laporan.persediaan_pdf', compact('persediaan'));
            return $pdf->download('laporan_persediaan.pdf');
        }
        
        return view('laporan.persediaan', compact('persediaan', 'search'));
    }
}

