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
        $validatedData = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        Supplier::create($validatedData);
        return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
    }

    public function edit(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($validatedData);
        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
    }
}
