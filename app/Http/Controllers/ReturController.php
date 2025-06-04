<?php
// File: app/Http/Controllers/ReturController.php

namespace App\Http\Controllers;

use App\Models\Retur;
use App\Models\DetailRetur;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    public function index()
    {
        $returs = Retur::with('user', 'supplier', 'pembelian')->get();
        return view('retur.index', compact('returs'));
    }

    public function create()
    {
        $pembelian = Pembelian::all();
        $supplier = Supplier::all();
        $users = User::all();
        $barang = Barang::all();
        return view('retur.create', compact('pembelian', 'supplier', 'users', 'barang'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = 'RT' . date('YmdHis');
            $retur = Retur::create([
                'id_retur' => $id,
                'id_pembelian' => $request->id_pembelian,
                'id_supplier' => $request->id_supplier,
                'id_user' => $request->id_user,
                'tanggal' => now()
            ]);

            foreach ($request->details as $detail) {
                DetailRetur::create([
                    'id_retur' => $id,
                    'kode_barang' => $detail['kode_barang'],
                    'jumlah' => $detail['jumlah'],
                    'harga' => $detail['harga'],
                    'alasan' => $detail['alasan']
                ]);
            }

            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Data retur berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors($e->getMessage());
        }
    }

    public function edit($id)
    {
        $retur = Retur::with('detailRetur')->findOrFail($id);
        $pembelian = Pembelian::all();
        $supplier = Supplier::all();
        $users = User::all();
        $barang = Barang::all();
        return view('retur.edit', compact('retur', 'pembelian', 'supplier', 'users', 'barang'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $retur = Retur::findOrFail($id);
            $retur->update($request->only(['id_pembelian', 'id_supplier', 'id_user']));
            DetailRetur::where('id_retur', $id)->delete();

            foreach ($request->details as $detail) {
                DetailRetur::create([
                    'id_retur' => $id,
                    'kode_barang' => $detail['kode_barang'],
                    'jumlah' => $detail['jumlah'],
                    'harga' => $detail['harga'],
                    'alasan' => $detail['alasan']
                ]);
            }

            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Data retur berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors($e->getMessage());
        }
    }
    public function updateStatus(Request $request, $id)
{
    $retur = Retur::findOrFail($id);
    $retur->status = $request->status;
    $retur->save();

    return back()->with('success', 'Status retur berhasil diperbarui.');
}
}