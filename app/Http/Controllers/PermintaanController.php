<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\DetailPermintaan;
use App\Models\Supplier;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PermintaanController extends Controller
{
    public function index()
    {
        $permintaan = Permintaan::with(['supplier', 'detailPermintaan.barang'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('permintaan.index', compact('permintaan'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $barang = Barang::all();

        return view('permintaan.create', compact('suppliers', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'tanggal' => 'required|date',
            'kode_barang' => 'required|array',
            'kode_barang.*' => 'required|exists:barang,kode_barang',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Generate ID Permintaan
            $idPermintaan = Permintaan::generateId();

            // Simpan data permintaan
            $permintaan = Permintaan::create([
                'id_permintaan' => $idPermintaan,
                'id_supplier' => $request->id_supplier,
                'tanggal' => $request->tanggal,
            ]);

            // Simpan detail permintaan
            foreach ($request->kode_barang as $index => $kodeBarang) {
                if (!empty($kodeBarang) && !empty($request->jumlah[$index])) {
                    DetailPermintaan::create([
                        'id_permintaan' => $idPermintaan,
                        'kode_barang' => $kodeBarang,
                        'jumlah' => $request->jumlah[$index],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('permintaan.index')
                           ->with('success', 'Permintaan berhasil dibuat dengan ID: ' . $idPermintaan);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Gagal membuat permintaan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function show($id)
    {
        $permintaan = Permintaan::with(['supplier', 'detailPermintaan.barang'])
                                ->findOrFail($id);

        return view('permintaan.show', compact('permintaan'));
    }

    public function edit($id)
    {
        $permintaan = Permintaan::with('detailPermintaan')->findOrFail($id);
        $suppliers = Supplier::all();
        $barang = Barang::all();
        

        return view('permintaan.edit', compact('permintaan', 'suppliers', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'tanggal' => 'required|date',
            'kode_barang' => 'required|array',
            'kode_barang.*' => 'required|exists:barang,kode_barang',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $permintaan = Permintaan::findOrFail($id);

            // Update data permintaan
            $permintaan->update([
                'id_supplier' => $request->id_supplier,
                'tanggal' => $request->tanggal,
            ]);

            // Hapus detail permintaan lama
            DetailPermintaan::where('id_permintaan', $id)->delete();

            // Simpan detail permintaan baru
            foreach ($request->kode_barang as $index => $kodeBarang) {
                if (!empty($kodeBarang) && !empty($request->jumlah[$index])) {
                    DetailPermintaan::create([
                        'id_permintaan' => $id,
                        'kode_barang' => $kodeBarang,
                        'jumlah' => $request->jumlah[$index],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('permintaan.index')
                           ->with('success', 'Permintaan berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Gagal mengupdate permintaan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $permintaan = Permintaan::findOrFail($id);
            $permintaan->delete();

            return redirect()->route('permintaan.index')
                           ->with('success', 'Permintaan berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus permintaan: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {
        $permintaan = Permintaan::with(['supplier', 'detailPermintaan.barang'])
                                ->findOrFail($id);

        $pdf = PDF::loadView('permintaan.pdf', compact('permintaan'));

        return $pdf->stream('Permintaan-' . $permintaan->id_permintaan . '.pdf');
    }

    public function getBarang()
    {
        $barang = Barang::select('kode_barang', 'nama_barang')->get();
        return response()->json($barang);
    }
}
