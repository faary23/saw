<?php

namespace App\Http\Controllers;

use App\Models\SubCriteria;
use App\Models\Criteria;
use App\Models\NormalizedValue;
use App\Models\Perangkingan;
use App\Models\WeightedValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerhitunganSawController extends Controller
{
    public function index()
    {
        // Cegah akses jika user dengan ID 1 (biasanya admin) mencoba membuka halaman ini
        if (Auth::check() && Auth::user()->id == 1) {
            return redirect()->route('login');
        }
        // Ambil semua kriteria
        $criterias = Criteria::all();
        
        // Ambil data alternatif (user yang login)
        $userAlternative = Auth::user();
        $criteriaValues = $userAlternative->data_kriteria ?? [];

        // Ambil nilai subkriteria dari data user
        $subCriteriaValues = SubCriteria::whereIn('id', $criteriaValues)
            ->get(['id', 'nilai']);

        // Ambil dan format nilai bobot (weighted value) user
        $weightvalue = WeightedValue::where('alternative_id', $userAlternative->id)
            ->get(['alternative_id', 'weighted_value'])
            ->map(function ($item) {
                $item->weighted_value = number_format($item->weighted_value, 2, '.', '');
                return $item;
            });
        // Ambil data perangkingan user
        $rangking = Perangkingan::where('alternative_id', $userAlternative->id)
        ->first(['alternative_id', 'rank', 'total_score', 'keterangan', 'status']);

        // Ambil dan format nilai normalisasi user
        $normalisasi = NormalizedValue::where('alternative_id', $userAlternative->id)
            ->get(['alternative_id', 'normalized_value'])
            ->map(function ($item) {
                $item->normalized_value = number_format($item->normalized_value, 2, '.', '');
                return $item;
            });

        // Ambil data subkriteria beserta relasinya ke kriteria
        $subCriterias = SubCriteria::with('criteria')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

         // Tampilkan view dengan data yang diperlukan
        return view('perhitungansaw.index', compact('subCriterias', 'criterias', 'userAlternative', 'criteriaValues', 'subCriteriaValues', 'normalisasi', 'weightvalue', 'rangking'));
    }
}
