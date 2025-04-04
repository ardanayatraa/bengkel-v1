<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\Konsumen;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index()
    {
        $points = Point::with('konsumen')->get();
        return view('point.index', compact('points'));
    }

    public function create()
    {
        $konsumens = Konsumen::all();
        return view('point.create', compact('konsumens'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_konsumen' => 'required|integer|exists:konsumens,id_konsumen',
            'tanggal' => 'required|date',
            'jumlah_point' => 'required|integer',
        ]);

        Point::create($validatedData);
        return redirect()->route('point.index')->with('success', 'Point created successfully.');
    }

    public function edit(string $id)
    {
        $point = Point::findOrFail($id);
        $konsumens = Konsumen::all();
        return view('point.edit', compact('point', 'konsumens'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'id_konsumen' => 'required|integer|exists:konsumens,id_konsumen',
            'tanggal' => 'required|date',
            'jumlah_point' => 'required|integer',
        ]);

        $point = Point::findOrFail($id);
        $point->update($validatedData);
        return redirect()->route('point.index')->with('success', 'Point updated successfully.');
    }

    public function destroy(string $id)
    {
        $point = Point::findOrFail($id);
        $point->delete();
        return redirect()->route('point.index')->with('success', 'Point deleted successfully.');
    }
}
