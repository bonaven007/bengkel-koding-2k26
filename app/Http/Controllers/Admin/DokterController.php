<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = User::where('role', 'dokter')->with('poli')->get();

        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        $polis = Poli::all();

        return view('admin.dokter.create', compact('polis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'no_ktp' => $data['no_ktp'],
            'no_hp' => $data['no_hp'],
            'id_poli' => $data['id_poli'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'dokter',
        ]);

        return redirect()->route('dokter.index');
    }

    public function edit(User $dokter)
    {
        $polis = Poli::all();

        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    public function update(Request $request, User $dokter)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => ['required', 'string', 'max:16', Rule::unique('users', 'no_ktp')->ignore($dokter->id)],
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($dokter->id)],
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'no_ktp' => $data['no_ktp'],
            'no_hp' => $data['no_hp'],
            'id_poli' => $data['id_poli'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $dokter->update($updateData);

        return redirect()->route('dokter.index');
    }

    public function destroy(User $dokter)
    {
        $dokter->delete();

        return redirect()->route('dokter.index');
    }
}