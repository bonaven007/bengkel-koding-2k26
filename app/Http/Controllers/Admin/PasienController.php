<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = User::where('role', 'pasien')->with('poli')->get();

        return view('admin.pasien.index', compact('pasiens'));
    }

    public function create()
    {
        return view('admin.pasien.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'no_ktp' => $data['no_ktp'],
            'no_hp' => $data['no_hp'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'pasien',
        ]);

        return redirect()->route('pasien.index')
            ->with('message', 'Pasien berhasil ditambahkan')
            ->with('type', 'success');
    }

    public function edit(User $pasien)
    {
        return view('admin.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, User $pasien)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' . $pasien->id,
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email,' . $pasien->id,
            'password' => 'nullable|string|min:6',
        ]);

        $updatedData = [
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'no_ktp' => $data['no_ktp'],
            'no_hp' => $data['no_hp'],
            'email' => $data['email'],
        ];

        if ($request->filled('password')) {
            $updatedData['password'] = Hash::make($data['password']);
        }

        $pasien->update($updatedData);

        return redirect()->route('pasien.index')
            ->with('message', 'Data pasien berhasil diupdate')
            ->with('type', 'success');
    }

    public function destroy(User $pasien)
    {
        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('message', 'Data pasien berhasil dihapus')
            ->with('type', 'success');
    }
}
