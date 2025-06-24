<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        return view('kasir.penjualan');
    }

    public function getBarang(Request $request)
    {
        $query = $request->get('q');
        $barang = Barang::where('nama_barang', 'LIKE', "%{$query}%")
                        ->orWhere('kode_barang', 'LIKE', "%{$query}%")
                        ->limit(10)
                        ->get(['kode_barang', 'nama_barang', 'harga_jual', 'stok']);

        return response()->json($barang);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.kode_barang' => 'required|exists:barang,kode_barang',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Generate ID Penjualan
            $lastPenjualan = Penjualan::orderBy('id_penjualan', 'desc')->first();
            $nextNumber = 1;

            if ($lastPenjualan) {
                $lastNumber = (int) substr($lastPenjualan->id_penjualan, 3);
                $nextNumber = $lastNumber + 1;
            }

            $idPenjualan = 'PJL' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            // Create Penjualan
            $penjualan = Penjualan::create([
                'id_penjualan' => $idPenjualan,
                'id_user' => Auth::id(),
                'tanggal' => now(),
            ]);

            // Create Detail Penjualan
            foreach ($request->items as $item) {
                DetailPenjualan::create([
                    'id_penjualan' => $idPenjualan,
                    'kode_barang' => $item['kode_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                ]);

                // Update stok barang
                $barang = Barang::where('kode_barang', $item['kode_barang'])->first();
                $barang->stok -= $item['jumlah'];
                $barang->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penjualan berhasil disimpan',
                'id_penjualan' => $idPenjualan,
                'print_url' => route('kasir.print', $idPenjualan)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function print($id)
    {
        $penjualan = Penjualan::with(['user', 'detailPenjualan.barang'])
                              ->where('id_penjualan', $id)
                              ->firstOrFail();

        return view('kasir.print', compact('penjualan'));
    }
}
