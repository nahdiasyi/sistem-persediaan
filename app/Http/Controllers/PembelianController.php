<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['supplier', 'user', 'detailPembelian.barang']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('supplier')) {
            $query->where('id_supplier', $request->supplier);
        }

        if ($request->filled('user')) {
            $query->where('id_user', $request->user);
        }

        $pembelian = $query->orderBy('tanggal', 'desc')->get();

        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $users = User::orderBy('nama_user')->get();

        return view('pembelian.index', compact('pembelian', 'suppliers', 'users'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $users = User::all();
        $barangs = Barang::all();

        return view('pembelian.form', compact('suppliers', 'users', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required',
            'barang' => 'required|array',
            'barang.*.kode_barang' => 'required',
            'barang.*.jumlah' => 'required|numeric|min:1',
            'barang.*.harga' => 'required|numeric|min:0',
            'barang.*.margin' => 'required|numeric|min:0',
            'barang.*.pajak' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $idPembelian = 'PB' . date('YmdHis');

            $pembelian = Pembelian::create([
                'id_pembelian' => $idPembelian,
                'id_supplier' => $request->id_supplier,
                'id_user' => Auth::user()->id_user,
                'tanggal' => now(),
            ]);

            foreach ($request->barang as $item) {
                DetailPembelian::create([
                    'id_pembelian' => $idPembelian,
                    'kode_barang' => $item['kode_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                ]);

                $barang = Barang::where('kode_barang', $item['kode_barang'])->first();
                if ($barang) {
                    $hargaBeli = $item['harga'];
                    $margin = ($hargaBeli * $item['margin']) / 100;
                    $pajak = ($hargaBeli * $item['pajak']) / 100;
                    $hargaJual = $hargaBeli + $margin + $pajak;

                    $stokBaru = $barang->stok + $item['jumlah'];

                    $barang->update([
                        'stok' => $stokBaru,
                        'harga_beli' => $hargaBeli,
                        'harga_jual' => $hargaJual,
                        'keterangan' => $stokBaru > 10 ? 'stok tersedia' : 'stok hampir habis'
                    ]);
                }
            }
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan');
    }

    public function edit($id)
    {
        $pembelian = Pembelian::with(['detailPembelian', 'user'])->findOrFail($id);
        $suppliers = Supplier::all();
        $users = User::all();
        $barangs = Barang::all();

        return view('pembelian.form', compact('pembelian', 'suppliers', 'users', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_supplier' => 'required',
            'id_user' => 'required',
            'barang' => 'required|array',
            'barang.*.kode_barang' => 'required',
            'barang.*.jumlah' => 'required|numeric|min:1',
            'barang.*.harga' => 'required|numeric|min:0',
            'barang.*.margin' => 'required|numeric|min:0',
            'barang.*.pajak' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {
            $pembelian = Pembelian::findOrFail($id);

            foreach ($pembelian->detailPembelian as $detail) {
                $barang = Barang::where('kode_barang', $detail->kode_barang)->first();
                if ($barang) {
                    $barang->update([
                        'stok' => $barang->stok - $detail->jumlah
                    ]);
                }
            }

            DetailPembelian::where('id_pembelian', $id)->delete();

            $pembelian->update([
                'id_supplier' => $request->id_supplier,
                'id_user' => $request->id_user,
                'tanggal' => $request->tanggal ?? now(),
            ]);

            foreach ($request->barang as $item) {
                DetailPembelian::create([
                    'id_pembelian' => $id,
                    'kode_barang' => $item['kode_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                ]);

                $barang = Barang::where('kode_barang', $item['kode_barang'])->first();
                if ($barang) {
                    $hargaBeli = $item['harga'];
                    $margin = ($hargaBeli * $item['margin']) / 100;
                    $ppn = (($hargaBeli + $margin) * $item['pajak']) / 100;
                    $hargaJual = $hargaBeli + $margin + $ppn;

                    $stokBaru = $barang->stok + $item['jumlah'];

                    $barang->update([
                        'stok' => $stokBaru,
                        'harga_beli' => $hargaBeli,
                        'harga_jual' => $hargaJual,
                        'keterangan' => $stokBaru > 10 ? 'stok tersedia' : 'stok hampir habis'
                    ]);
                }
            }
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil diupdate');
    }

   
}
