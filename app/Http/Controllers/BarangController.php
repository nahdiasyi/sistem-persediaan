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
            'Nama_Barang' => 'required|string|max:255',
            'Harga_Beli' => 'required|numeric',
            'Harga_Jual' => 'required|numeric',
            'Nilai' => 'nullable|string|max:100',
            'Satuan' => 'nullable|string|max:50',
            'Merek' => 'nullable|string|max:100',
            'Id_Kategori' => 'required|exists:kategori,Id_kategori',
        ]);

        // Generate ID Barang otomatis jika diperlukan
        $latestId = Barang::max('Id_Barang');
        $newId = 'BRG' . str_pad((intval(substr($latestId, 3)) + 1), 4, '0', STR_PAD_LEFT);

        Barang::create([
            'Id_Barang' => $newId,
            'Nama_Barang' => $request->Nama_Barang,
            'Harga_Beli' => $request->Harga_Beli,
            'Harga_Jual' => $request->Harga_Jual,
            'Nilai' => $request->Nilai,
            'Satuan' => $request->Satuan,
            'Merek' => $request->Merek,
            'Id_Kategori' => $request->Id_Kategori,
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
            'Nama_Barang' => 'required|string|max:255',
            'Harga_Beli' => 'required|numeric',
            'Harga_Jual' => 'required|numeric',
            'Nilai' => 'nullable|string|max:100',
            'Satuan' => 'nullable|string|max:50',
            'Merek' => 'nullable|string|max:100',
            'Id_Kategori' => 'required|exists:kategori,Id_kategori',
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'Nama_Barang' => $request->Nama_Barang,
            'Harga_Beli' => $request->Harga_Beli,
            'Harga_Jual' => $request->Harga_Jual,
            'Nilai' => $request->Nilai,
            'Satuan' => $request->Satuan,
            'Merek' => $request->Merek,
            'Id_Kategori' => $request->Id_Kategori,
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
