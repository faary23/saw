<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AlternativeController extends Controller
{
    public function index(Request $request)
    {
        // Hanya admin (id = 1) yang boleh akses
        if (Auth::check()) {
            if (Auth::user()->id != 1) {
                return redirect()->route('login');
            }
        }
        // Fitur pencarian berdasarkan nama atau NIM
        $search = $request->input('search');
        $alternatives = Alternative::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%");
        })
            ->where('id', '!=', 1) // Jangan tampilkan user admin
            ->paginate(10); // Tampilkan 10 per halaman

        // Menambahkan data kriteria yang sudah diformat
        foreach ($alternatives as $alternative) {
            $formattedDataKriteria = [];

            // Iterasi data kriteria
            foreach ($alternative->data_kriteria as $criteriaId => $subCriteriaId) {
                // Ambil nama kriteria
                $criteria = Criteria::find($criteriaId);
                // Ambil nama subkriteria jika ada
                $subCriteria = $criteria ? $criteria->subCriterias->find($subCriteriaId) : null;

                if ($criteria && $subCriteria) {
                    $formattedDataKriteria[] = [
                        'criteria_name' => $criteria->name,
                        'sub_criteria_name' => $subCriteria->nama_sub
                    ];
                } elseif ($criteria) {
                    $formattedDataKriteria[] = [
                        'criteria_name' => $criteria->name,
                        'sub_criteria_name' => 'No subcriteria selected'
                    ];
                }
            }

            $alternative->formatted_data_kriteria = $formattedDataKriteria;
        }

        return view('alternatives.index', compact('alternatives', 'search'));
    }

     // Tampilkan form tambah alternatif baru
    public function create()
    {
        $criterias = Criteria::with('subCriterias')->get();
        return view('alternatives.create', compact('criterias'));
    }

    // Simpan alternatif baru ke database
    public function store(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'nim' => 'required|unique:alternatives,nim',
        'password' => 'nullable|string|min:8',
        'data_kriteria' => 'required|array',
    ]);

    $password = $validated['password'] ?? '12345678';

    $nilaiManual = $validated['data_kriteria'];
    $dataKriteria = [];

    foreach ($nilaiManual as $criteriaId => $nilai) {
        $subId = $this->mapNilaiToSubCriteria($criteriaId, $nilai);
        $dataKriteria[$criteriaId] = $subId;
    }

    $alternative = Alternative::create([
        'nama' => $validated['nama'],
        'nim' => $validated['nim'],
        'password' => Hash::make($password),
        'data_kriteria' => $dataKriteria,
        'nilai_manual' => $nilaiManual, // Simpan nilai manual juga jika ingin
    ]);

    return redirect()->route('alternatives.index')->with('success', 'Alternative created successfully!');
}
     // Tampilkan form edit alternatif
    public function edit(Alternative $alternative)
    {
        $criterias = Criteria::with('subCriterias')->get();
        return view('alternatives.edit', compact('alternative', 'criterias'));
    }

     // Update data alternatif yang dipilih
     public function update(Request $request, Alternative $alternative)
     {
         $validated = $request->validate([
             'nama' => 'required|string|max:255',
             'nim' => 'required|unique:alternatives,nim,' . $alternative->id,
             'password' => 'nullable|string|min:8',
             'data_kriteria' => 'required|array',
         ]);
     
         $password = $validated['password'] ? Hash::make($validated['password']) : $alternative->password;
     
         $nilaiManual = $validated['data_kriteria'];
         $dataKriteria = [];
     
         foreach ($nilaiManual as $criteriaId => $nilai) {
             $subId = $this->mapNilaiToSubCriteria($criteriaId, $nilai);
             $dataKriteria[$criteriaId] = $subId;
         }
     
         $alternative->update([
            //  'nama' => $validated['nama'],
            //  'nim' => $validated['nim'],
             'password' => $password,
             'data_kriteria' => $dataKriteria,
             'nilai_manual' => $nilaiManual,
         ]);
     
         return redirect()->route('alternatives.index')->with('success', 'Alternative updated successfully!');
     }

    // Hapus alternatif beserta data perangkingannya
    public function destroy(Alternative $alternative)
{
    // Hapus data perangkingan terkait terlebih dahulu
    $alternative->perangkingan()->delete();

    // Hapus alternatif setelahnya
    $alternative->delete();

    return redirect()->route('alternatives.index')
        ->with('success', 'Alternative deleted successfully!');
}

private function mapNilaiToSubCriteria($criteriaId, $nilai)
{
    $criteria = Criteria::find($criteriaId);
    $manualCriteria = ['attitude', 'komunikasi', 'problem solving'];

    if (in_array(strtolower($criteria->name), $manualCriteria)) {
        if ($nilai > 90 && $nilai <= 100) {
            return SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 5)->first()?->id;
        } elseif ($nilai > 80 && $nilai <= 90) {
            return SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 4)->first()?->id;
        } elseif ($nilai > 70 && $nilai <= 80) {
            return SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 3)->first()?->id;
        } elseif ($nilai > 60 && $nilai <= 70) {
            return SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 2)->first()?->id;
        } else {
            return SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 1)->first()?->id;
        }
    } else {
        // Kalau bukan kriteria manual, nilai sudah ID subkriteria langsung dari dropdown
        return $nilai;
    }
}

}
