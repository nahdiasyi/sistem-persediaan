<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with(['supplier', 'user', 'detailPembelian'])->get();
        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('pembelian.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required',
            'tanggal' => 'required|date',
            'details' => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $idPembelian = 'PO' . str_pad(Pembelian::count() + 1, 3, '0', STR_PAD_LEFT);

            Pembelian::create([
                'id_pembelian' => $idPembelian,
                'id_supplier' => $request->id_supplier,
                'id_user' => session('user.id'), // OR Auth::id()
                'tanggal' => $request->tanggal,
            ]);

            foreach ($request->details as $detail) {
                DetailPembelian::create([
                    'id_pembelian' => $idPembelian,
                    'kode_barang' => $detail['kode_barang'],
                    'jumlah' => $detail['jumlah'],
                    'harga' => $detail['harga'],
                ]);
            }
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil ditambahkan.');
    }
}
