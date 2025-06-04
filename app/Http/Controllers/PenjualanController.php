<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::with('user', 'detailPenjualan.barang')->get();
        return view('penjualan.index', compact('penjualan'));
    }

    public function create()
    {
        $users = User::all();
        $barang = Barang::all();
        return view('penjualan.create', compact('users', 'barang'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = 'PJ' . date('YmdHis');
            $penjualan = Penjualan::create([
                'id_penjualan' => $id,
                'id_user' => $request->id_user,
                'tanggal' => now()
            ]);

            foreach ($request->details as $detail) {
                DetailPenjualan::create([
                    'id_penjualan' => $id,
                    'kode_barang' => $detail['kode_barang'],
                    'jumlah' => $detail['jumlah'],
                    'harga' => $detail['harga']
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $penjualan = Penjualan::with('detailPenjualan')->findOrFail($id);
        $users = User::all();
        $barang = Barang::all();
        return view('penjualan.edit', compact('penjualan', 'users', 'barang'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $penjualan = Penjualan::findOrFail($id);
            $penjualan->update(['id_user' => $request->id_user]);

            DetailPenjualan::where('id_penjualan', $id)->delete();

            foreach ($request->details as $detail) {
                DetailPenjualan::create([
                    'id_penjualan' => $id,
                    'kode_barang' => $detail['kode_barang'],
                    'jumlah' => $detail['jumlah'],
                    'harga' => $detail['harga']
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
