<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CriteriaController extends Controller
{
    // Menampilkan semua kriteria (hanya bisa diakses admin)
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->id != 1) {
                return redirect()->route('login'); // Akses selain admin diarahkan ke login
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
        // Validasi data masukan
        $validated = $request->validate([
            'kode' => 'required|unique:criterias,kode|max:10',
            'name' => 'required|string|max:255',
            'type' => 'required|in:Benefit,Cost',
            'weight' => 'required|integer|min:1|max:100',
            'keterangan' => 'nullable|string|max:255', // Menambahkan validasi keterangan
        ]);

        // Simpan ke database
        Criteria::create($validated);
        return redirect()->route('criteria.index')->with('success', 'Criteria added successfully.');
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

     // Hapus kriteria berdasarkan ID
    public function destroy($id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->delete(); // Hapus dari database

        return redirect()->route('criteria.index')->with('success', 'Criteria deleted successfully.');
    }
}
