<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        // Hanya admin (id = 1) yang boleh akses
        if (empty(session('role'))) {
            return redirect()->route('login');
        }
        // dd(session()->all());
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
                        'kode' => $criteria->kode,
                        'sub_criteria_name' => $subCriteria->nama_sub
                    ];
                } elseif ($criteria) {
                    $formattedDataKriteria[] = [
                        'criteria_name' => $criteria->name,
                        'kode' => $criteria->kode,
                        'sub_criteria_name' => 'No subcriteria selected'
                    ];
                }
            }

            $alternative->formatted_data_kriteria = $formattedDataKriteria;
        }

        return view('penilaian.index', compact('alternatives', 'search'));
    }


    public function edit(Alternative $alternative)
    {
        $criterias = Criteria::with('subCriterias')->get();

        return view('penilaian.edit', [
            'alternative' => $alternative,
            'criterias' => $criterias,
            'isEdit' => !empty($alternative->data_kriteria)
        ]);
    }

    public function update(Request $request, Alternative $alternative)
    {
        $data = $request->validate([
            'data_kriteria' => 'required|array',
        ]);

        $existing = $alternative->data_kriteria ?? [];

        // Ambil semua kriteria sekaligus agar bisa dicek berdasarkan ID
        $criterias = \App\Models\Criteria::with('subCriterias')->get()->keyBy('id');

        foreach ($data['data_kriteria'] as $criteriaId => $value) {
            $criteriaName = strtolower($criterias[$criteriaId]->name ?? '');

            if (in_array($criteriaName, ['attitude', 'komunikasi', 'problem solving']) && is_numeric($value)) {
                $subCriteriaId = $this->convertNilaiToSubCriteriaId($criteriaId, $value);
                if ($subCriteriaId !== null) {
                    $existing[$criteriaId] = $subCriteriaId;
                }
            } else {
                // Simpan langsung kalau bukan 3 kriteria tersebut
                $existing[$criteriaId] = $value;
            }
        }

        $alternative->data_kriteria = $existing;
        $alternative->save();

        return redirect()->route('penilaian.index', $alternative->id)
            ->with('success', 'Penilaian berhasil diperbarui.');
    }

    private function convertNilaiToSubCriteriaId($criteriaId, $nilai)
    {
        if ($nilai > 90 && $nilai <= 100) {
            return \App\Models\SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 5)->first()?->id;
        } elseif ($nilai > 80 && $nilai <= 90) {
            return \App\Models\SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 4)->first()?->id;
        } elseif ($nilai > 70 && $nilai <= 80) {
            return \App\Models\SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 3)->first()?->id;
        } elseif ($nilai > 60 && $nilai <= 70) {
            return \App\Models\SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 2)->first()?->id;
        } else {
            return \App\Models\SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 1)->first()?->id;
        }
    }


    // private function mapNilaiToSubCriteria($criteriaId, $nilai)
    // {
    //     $criteria = Criteria::find($criteriaId);
    //     $manualCriteria = ['attitude', 'komunikasi', 'problem solving'];

    //     if (in_array(strtolower($criteria->name), $manualCriteria)) {
    //         if ($nilai > 90 && $nilai <= 100) {
    //             return SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 5)->first()?->id;
    //         } elseif ($nilai > 80 && $nilai <= 90) {
    //             return SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 4)->first()?->id;
    //         } elseif ($nilai > 70 && $nilai <= 80) {
    //             return SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 3)->first()?->id;
    //         } elseif ($nilai > 60 && $nilai <= 70) {
    //             return SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 2)->first()?->id;
    //         } else {
    //             return SubCriteria::where('criteria_id', $criteriaId)->where('nilai', 1)->first()?->id;
    //         }
    //     } else {
    //         // Kalau bukan kriteria manual, nilai sudah ID subkriteria langsung dari dropdown
    //         return $nilai;
    //     }
    // }
}
