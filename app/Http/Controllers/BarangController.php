<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Menampilkan daftar semua barang
    public function index()
    {
        $barangs = Barang::with('kategori')->get();
        return view('barang.index', compact('barangs'));
    }

    // Menampilkan form tambah barang
    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    // Menyimpan barang baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'Satuan' => 'nullable|string|max:50',
            'merek' => 'nullable|string|max:100',
            'stok' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|in:stok tersedia,stok hampir habis',
            'id_kategori' => 'required|exists:kategori,id_kategori',
        ]);

        // Generate kode_barang otomatis (misalnya: BRG0001, BRG0002, dst.)
        $latest = Barang::max('kode_barang');
        $number = $latest ? intval(substr($latest, 3)) + 1 : 1;
        $newKode = 'BRG' . str_pad($number, 4, '0', STR_PAD_LEFT);

        Barang::create([
            'kode_barang' => $newKode,
            'nama_barang' => $request->nama_barang,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'Satuan' => $request->Satuan,
            'merek' => $request->merek,
            'stok' => $request->stok ?? 0,
            'keterangan' => $request->keterangan,
            'id_kategori' => $request->id_kategori,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::all();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    // Memperbarui data barang
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'Satuan' => 'nullable|string|max:50',
            'merek' => 'nullable|string|max:100',
            'stok' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'Satuan' => $request->Satuan,
            'merek' => $request->merek,
            'stok' => $request->stok ?? $barang->stok,
            'keterangan' => $request->keterangan,
            'id_kategori' => $request->id_kategori,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    // Menghapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
