<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::all();

        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan' => 'nullable|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Obat::create($data);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil ditambahkan');
    }

    public function edit(Obat $obat)
    {
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat)
    {
        $data = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan' => 'nullable|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'add_stock' => 'nullable|integer|min:0',
            'reduce_stock' => 'nullable|integer|min:0',
        ]);

        $addStock = $data['add_stock'] ?? 0;
        $reduceStock = $data['reduce_stock'] ?? 0;
        $newStok = $data['stok'] + $addStock - $reduceStock;

        if ($newStok < 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pengurangan stok tidak boleh lebih besar dari stok tersedia.');
        }

        $data['stok'] = $newStok;
        unset($data['add_stock'], $data['reduce_stock']);

        $obat->update($data);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil diupdate');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil dihapus');
    }
}
