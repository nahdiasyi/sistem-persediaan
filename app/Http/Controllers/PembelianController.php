<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    // public function index()
    // {
    //     $pembelians = Pembelian::with(['supplier', 'user', 'detailPembelian'])->get();
    //     return view('pembelian.index', compact('pembelians'));
    // }

    // public function create()
    // {
    //     $suppliers = Supplier::all();
    //     return view('pembelian.create', compact('suppliers'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'id_supplier' => 'required',
    //         'tanggal' => 'required|date',
    //         'details' => 'required|array|min:1',
    //     ]);

    //     DB::transaction(function () use ($request) {
    //         $idPembelian = 'PO' . str_pad(Pembelian::count() + 1, 3, '0', STR_PAD_LEFT);

    //         Pembelian::create([
    //             'id_pembelian' => $idPembelian,
    //             'id_supplier' => $request->id_supplier,
    //             'id_user' => session('user.id'), // OR Auth::id()
    //             'tanggal' => $request->tanggal,
    //         ]);

    //         foreach ($request->details as $detail) {
    //             DetailPembelian::create([
    //                 'id_pembelian' => $idPembelian,
    //                 'kode_barang' => $detail['kode_barang'],
    //                 'jumlah' => $detail['jumlah'],
    //                 'harga' => $detail['harga'],
    //             ]);
    //         }
    //     });

    //     return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil ditambahkan.');
    // }

    public function index()
    {
        $pembelian = Pembelian::with(['supplier', 'user', 'detailPembelian.barang'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pembelian.index', compact('pembelian'));
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
            // Generate ID Pembelian
            $idPembelian = 'PB' . date('YmdHis');

            // Simpan Pembelian
            $pembelian = Pembelian::create([
                'id_pembelian' => $idPembelian,
                'id_supplier' => $request->id_supplier,
                'id_user' => Auth::user()->id_user,
            ]);

            // Simpan Detail Pembelian dan Update Barang
            foreach ($request->barang as $item) {
                // Simpan Detail Pembelian
                DetailPembelian::create([
                    'id_pembelian' => $idPembelian,
                    'kode_barang' => $item['kode_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                ]);

                // Update Barang
                $barang = Barang::where('kode_barang', $item['kode_barang'])->first();
                if ($barang) {
                    $hargaBeli = $item['harga'];
                    $margin = ($hargaBeli * $item['margin']) / 100;
                    $ppn = (($hargaBeli + $margin) * $item['pajak']) / 100;
                    $hargaJual = $hargaBeli + $margin + $ppn;

                    $barang->update([
                        'stok' => $barang->stok + $item['jumlah'],
                        'harga_beli' => $hargaBeli,
                        'harga_jual' => $hargaJual,
                        'keterangan' => ($barang->stok + $item['jumlah']) > 10 ? 'stok tersedia' : 'stok hampir habis'
                    ]);
                }
            }
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan');
    }

    public function edit($id)
    {
        $pembelian = Pembelian::with('detailPembelian')->findOrFail($id);
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

            // Kembalikan stok lama
            foreach ($pembelian->detailPembelian as $detail) {
                $barang = Barang::where('kode_barang', $detail->kode_barang)->first();
                if ($barang) {
                    $barang->update([
                        'stok' => $barang->stok - $detail->jumlah
                    ]);
                }
            }

            // Hapus detail lama
            DetailPembelian::where('id_pembelian', $id)->delete();

            // Update Pembelian
            $pembelian->update([
                'id_supplier' => $request->id_supplier,
                'id_user' => $request->id_user,
            ]);

            // Simpan Detail Pembelian Baru dan Update Barang
            foreach ($request->barang as $item) {
                // Simpan Detail Pembelian
                DetailPembelian::create([
                    'id_pembelian' => $id,
                    'kode_barang' => $item['kode_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                ]);

                // Update Barang
                $barang = Barang::where('kode_barang', $item['kode_barang'])->first();
                if ($barang) {
                    $hargaBeli = $item['harga'];
                    $margin = ($hargaBeli * $item['margin']) / 100;
                    $ppn = (($hargaBeli + $margin) * $item['pajak']) / 100;
                    $hargaJual = $hargaBeli + $margin + $ppn;

                    $barang->update([
                        'stok' => $barang->stok + $item['jumlah'],
                        'harga_beli' => $hargaBeli,
                        'harga_jual' => $hargaJual,
                        'keterangan' => ($barang->stok + $item['jumlah']) > 10 ? 'stok tersedia' : 'stok hampir habis'
                    ]);
                }
            }
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil diupdate');
    }
}
