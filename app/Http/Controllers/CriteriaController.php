<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Penilai;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // untuk generate password sementara (opsional)

class CriteriaController extends Controller
{
    // Menampilkan semua kriteria (hanya bisa diakses admin)
    public function index()
    {
        if (!empty(session('role'))) {
            return redirect()->route('penilaian.index');
        } else {
            if (Auth::check()) {
                if (Auth::user()->id != 1) {
                    return redirect()->route('login');
                }
            }
        }
        $criterias = Criteria::all(); // Ambil semua kriteria dari database
        return view('criteria.index', compact('criterias')); // Kirim ke view
    }

    // Tampilkan form tambah kriteria
    public function create()
    {
        return view('criteria.create');
    }

    // Simpan kriteria baru
   public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:criterias,kode|max:10',
            'name' => 'required|string|max:255',
            'type' => 'required|in:Benefit,Cost',
            'weight' => 'required|integer|min:1|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $criteria = Criteria::create($validated);

        // Hitung urutan ke berapa kriteria ini
        $totalCriterias = Criteria::count();
        $role = 'c' . $totalCriterias; // c1, c2, ...

        // Buat penilai dengan nim = admin + role, password = admin123
        Penilai::create([
            'nama' => 'Penilai ' . strtoupper($role),
            'nim' => 'admin' . $role, // contoh: adminc1
            'password' => Hash::make('admin123'),
            'role' => $role, // role: c1, c2, ...
        ]);

        // Buat penilai khusus untuk kriteria dengan role 'penilai'
      //  $nimBaru = 'penilai_' . strtolower($criteria->kode) . '_' . time();

      //  Penilai::create([
       //     'nama' => 'Penilai untuk ' . $criteria->name,
      //      'nim' => $nimBaru,
       //     'password' => Hash::make('admin123'),
        //    'role' => 'penilai',
       // ]);

        return redirect()->route('criteria.index')->with('success', 'Criteria added successfully and penilai created.');
    }


    // Tampilkan form edit kriteria
    public function edit($id)
    {
        $criteria = Criteria::findOrFail($id); // Ambil data kriteria berdasarkan ID
        return view('criteria.edit', compact('criteria'));
    }

    // Simpan update kriteria
    public function update(Request $request, $id)
    {
        // Validasi data masukan (kode tetap unik, tapi kecuali untuk data dirinya sendiri)
        $validated = $request->validate([
            'kode' => 'required|max:10|unique:criterias,kode,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:Benefit,Cost',
            'weight' => 'required|integer|min:1|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $criteria = Criteria::findOrFail($id);
        $criteria->update($validated); // Update data di database

        return redirect()->route('criteria.index')->with('success', 'Criteria updated successfully.');
    }

    public function destroy($id)
    {
        $criteria = Criteria::findOrFail($id);

        // Ambil kode dari criteria (tanpa ubah huruf atau pakai strtolower jika perlu)
        $kode = strtolower($criteria->kode);

        // Hapus data penilai yang memiliki kode yang sama
        Penilai::where('role', $kode)->delete();

        // Hapus sub_criteria berdasarkan id criteria
        SubCriteria::where('criteria_id', $criteria->id)->delete();

        // Hapus criteria
        $criteria->delete();

        return redirect()->route('criteria.index')->with('success', 'Criteria, its sub-criteria, and related penilai data deleted successfully.');
    }

}
