<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:70',
            'alamat_supplier' => 'required|string|max:100',
            'no_telp' => 'required|string|max:13',
        ]);

        $lastSupplier = Supplier::orderBy('id_supplier', 'desc')->first();

        if ($lastSupplier) {
            $lastId = intval(substr($lastSupplier->id_supplier, 3));
            $newId = 'SUP' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newId = 'SUP001';
        }

        Supplier::create([
            'id_supplier' => $newId,
            'nama_supplier' => $request->nama_supplier,
            'alamat_supplier' => $request->alamat_supplier,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:70',
            'alamat_supplier' => 'required|string|max:100',
            'no_telp' => 'required|string|max:13',
        ]);

        $supplier = Supplier::findOrFail($id);

        $supplier->nama_supplier = $request->nama_supplier;
        $supplier->alamat_supplier = $request->alamat_supplier;
        $supplier->no_telp = $request->no_telp;
        $supplier->save();

        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
    }
}
