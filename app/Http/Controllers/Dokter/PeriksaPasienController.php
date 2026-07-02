<?php
namespace App\Http\Controllers\Dokter;
use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    public function index()
    {
    $dokterId = Auth::id();
    $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksa'])
    ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
    $query->where('id_dokter', $dokterId);
    })
    ->orderBy('no_antrian')
    ->get();

    return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
    $obats = Obat::all();
    return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_poli,id',
            'obat_json' => 'required',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        $obatIds = json_decode($request->obat_json, true);
        $obats = Obat::whereIn('id', $obatIds)->get()->keyBy('id');

        try {
            DB::transaction(function () use ($request, $obatIds, $obats) {
                foreach ($obatIds as $idObat) {
                    if (!isset($obats[$idObat]) || $obats[$idObat]->stok <= 0) {
                        throw new \RuntimeException('Stok obat tidak mencukupi atau obat tidak ditemukan.');
                    }
                }

                $periksa = Periksa::create([
                    'id_daftar_poli' => $request->id_daftar_poli,
                    'tgl_periksa' => now(),
                    'catatan' => $request->catatan,
                    'biaya_periksa' => $request->biaya_periksa + 150000,
                ]);

                foreach ($obatIds as $idObat) {
                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $idObat,
                    ]);

                    $obats[$idObat]->reduceStock();
                }
            });
        } catch (\RuntimeException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->route('periksa-pasien.index')->with('success', 'Data periksa berhasil disimpan dan stok obat diperbarui.');
    }
}