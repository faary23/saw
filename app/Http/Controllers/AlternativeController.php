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
        if (Auth::check() && Auth::user()->id != 1) {
            return redirect()->route('login');
        }

        // Ambil keyword pencarian
        $search = $request->input('search');

        // Ambil data alternatif berdasarkan pencarian
        $alternatives = Alternative::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%");
        })
            ->where('id', '!=', 1) // Kecualikan admin
            ->paginate(10);

        // Format data kriteria untuk tiap alternatif
        foreach ($alternatives as $alternative) {
            $formattedDataKriteria = [];

            // Decode JSON jika data_kriteria berupa string
            $dataKriteria = $alternative->data_kriteria;
            if (is_string($dataKriteria)) {
                $dataKriteria = json_decode($dataKriteria, true);
            }

            // Pastikan data_kriteria sudah berupa array
            if (is_array($dataKriteria)) {
                foreach ($dataKriteria as $criteriaId => $subCriteriaId) {
                    $criteria = Criteria::find($criteriaId);
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
            }

            // Simpan hasil formatting ke atribut tambahan
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
