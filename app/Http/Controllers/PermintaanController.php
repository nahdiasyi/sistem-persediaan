<?php
namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\DetailPermintaan;
use App\Models\Barang;
use Illuminate\Http\Request;
use DB;

class PermintaanController extends Controller
{
    public function index()
    {
        // Generate new ID
        $lastId = Permintaan::orderBy('Id_Permintaan', 'desc')->first();
        $newId = 'REQ002';
        
        if($lastId) {
            $lastNumber = intval(substr($lastId->Id_Permintaan, 3));
            $newId = 'REQ' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        $barang = Barang::all();
        return view('permintaan.create', compact('newId', 'barang'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $permintaan = new Permintaan();
            $permintaan->Id_Permintaan = $request->id_permintaan;
            $permintaan->Id_Admin_Toko = auth()->user()->id;
            $permintaan->Tanggal = $request->tanggal;
            $permintaan->status = 'tertunda';
            $permintaan->save();

            $details = json_decode($request->detail_barang, true);
            foreach($details as $detail) {
                DetailPermintaan::create([
                    'Id_Permintaan' => $permintaan->Id_Permintaan,
                    'Id_barang' => $detail['id_barang'],
                    'Jumlah_Diminta' => $detail['jumlah'],
                    'Jumlah_Disetujui' => null
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