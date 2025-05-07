<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Perangkingan;
use App\Models\NormalizedValue;
use App\Models\WeightedValue;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    // Menampilkan halaman ranking, hanya untuk admin
    public function index()
    {
        if (Auth::check() && Auth::user()->id != 1) {
            return redirect()->route('login');
        }

        $criterias = Criteria::all(); // Ambil semua kriteria
        $alternatives = Alternative::where('id', '!=', 1) // Ambil alternatif kecuali ID 1 (admin)
            ->whereHas('perangkingan') // Pastikan ada data perangkingan terkait
            ->with('perangkingan') // Termasuk data perangkingan dalam query
            ->get()
            ->sortBy(fn($alternative) => $alternative->perangkingan->rank ?? PHP_INT_MAX);  // Urutkan berdasarkan ranking

        return view('ranking.index', compact('alternatives', 'criterias'));
    }

    // Menampilkan halaman keputusan akhir (terima/tolak)
    // public function keputusan()
    // {
    //     if (Auth::check() && Auth::user()->id != 1) {
    //         return redirect()->route('login');
    //     }

    //     $criterias = Criteria::all(); // Ambil semua kriteria
    //     $alternatives = Alternative::where('id', '!=', 1) // Ambil alternatif kecuali ID 1 (admin)
    //         ->whereHas('perangkingan', fn($query) => $query->where('status', 1)) // Hanya yang diterima (status 1)
    //         ->with('perangkingan') // Termasuk data perangkingan dalam query
    //         ->get()
    //         ->sortBy(fn($alternative) => $alternative->perangkingan->rank ?? PHP_INT_MAX); // Urutkan berdasarkan ranking

    //     return view('keputusanakhir.index', compact('alternatives', 'criterias'));
    // }

    // Menghitung ranking berdasarkan metode SAW (Simple Additive Weighting)
    public function rank()
    {
        // Kosongkan tabel sebelumnya
        Perangkingan::truncate();
        NormalizedValue::truncate();
        WeightedValue::truncate();

        $criterias = Criteria::with('subCriterias')->get();

        $alternatives = Alternative::where('id', '!=', 1)
            ->get()
            ->filter(function ($alt) use ($criterias) {
                foreach ($criterias as $criteria) {
                    if (empty($alt->data_kriteria[$criteria->id])) {
                        return false; // Skip jika ada kriteria yang belum terisi
                    }
                }
                return true;
            })
            ->values(); // Reset index agar tidak lompat

        $rankings = [];
        $criteriaMaxValues = [];
        $criteriaMinValues = [];

        // Hitung nilai maksimum dan minimum untuk setiap kriteria
        foreach ($criterias as $criteria) {
            $values = $alternatives->map(fn($alt) => $alt->data_kriteria[$criteria->id] ?? 0)
                ->filter()
                ->map(fn($subId) => optional($criteria->subCriterias->find($subId))->nilai ?? 0)
                ->toArray();

            $criteriaMaxValues[$criteria->id] = !empty($values) ? max($values) : 1;
            $criteriaMinValues[$criteria->id] = !empty($values) ? min($values) : 1;
        }

        // Normalisasi dan Pembobotan
        foreach ($alternatives as $alternative) {
            $totalScore = 0;

            foreach ($criterias as $criteria) {
                $subCriteriaId = $alternative->data_kriteria[$criteria->id] ?? null;
                $subCriteria = $criteria->subCriterias->find($subCriteriaId);

                if ($subCriteria) {
                    $subValue = $subCriteria->nilai;
                    $normalizedValue = 0;

                    if ($criteria->type === 'Benefit') {
                        $maxValue = $criteriaMaxValues[$criteria->id] ?: 1;
                        $normalizedValue = number_format($subValue / $maxValue, 4, '.', '');
                    } elseif ($criteria->type === 'Cost') {
                        $minValue = $criteriaMinValues[$criteria->id] ?: 1;
                        $normalizedValue = number_format($minValue / $subValue, 4, '.', '');
                    }

                    $weightedValue = number_format($normalizedValue * $criteria->weight, 4, '.', '');

                    // Simpan ke database
                    NormalizedValue::create([
                        'alternative_id' => $alternative->id,
                        'criteria_id' => $criteria->id,
                        'sub_criteria_id' => $subCriteria->id,
                        'sub_criteria_value' => number_format($subValue, 2, '.', ''),
                        'max_value' => number_format($criteriaMaxValues[$criteria->id], 2, '.', ''),
                        'normalized_value' => $normalizedValue,
                    ]);

                    WeightedValue::create([
                        'alternative_id' => $alternative->id,
                        'criteria_id' => $criteria->id,
                        'normalized_value' => $normalizedValue,
                        'weight' => number_format($criteria->weight, 2, '.', ''),
                        'weighted_value' => $weightedValue,
                    ]);

                    $totalScore += $weightedValue;
                }
            }

            $rankings[] = [
                'alternative_id' => $alternative->id,
                'total_score' => number_format($totalScore, 4, '.', ''),
            ];
        }

        // Urutkan berdasarkan skor total (tertinggi ke terendah)
        usort($rankings, fn($a, $b) => $b['total_score'] <=> $a['total_score']);

        // Simpan ranking ke database
        $acceptedCount = session('accepted_count', 0);

        foreach ($rankings as $index => $ranking) {
            $status = ($index < $acceptedCount) ? 1 : 0; // 1 = diterima, 0 = tidak diterima

            Perangkingan::create([
                'alternative_id' => $ranking['alternative_id'],
                'rank' => $index + 1,
                'total_score' => $ranking['total_score'],
                'status' => $status,
            ]);
        }
        return redirect()->route('ranking.index')->with('success', 'Ranking berhasil diperbarui!');
    }

    // Menambahkan keterangan penolakan untuk alternatif
    // public function addKeterangan(Request $request, $alternativeId)
    // {
    //     $request->validate(['keterangan' => 'required|string|max:255']); // Validasi keterangan

    //     $ranking = Perangkingan::where('alternative_id', $alternativeId)->first();

    //     if ($ranking) {
    //         // Update keterangan dan status menjadi ditolak (status 2)
    //         $ranking->update(['keterangan' => $request->input('keterangan'), 'status' => 2]);
    //     }

    //     return redirect()->route('ranking.index')->with('success', 'Keterangan telah ditambahkan!');
    // }

    // Menandai calon anggota sebagai diterima
//     public function addDiterima(Request $request, $alternativeId)
// {
//     $ranking = Perangkingan::where('alternative_id', $alternativeId)->first();

    //     if ($ranking) {
//         // Tandai sebagai diterima
//         $ranking->update(['status' => 1]);
//     }

    //     // Urutkan ulang ranking untuk yang status = 1
//     $accepted = Perangkingan::where('status', 1)
//         ->orderByDesc('total_score')
//         ->get();

    //     foreach ($accepted as $index => $item) {
//         $item->update(['rank' => $index + 1]);
//     }

    //     return redirect()->route('ranking.index')->with('success', 'Calon anggota telah diterima!');
// }


    // Menandai calon anggota sebagai ditolak
//     public function addTolak(Request $request, $alternativeId)
// {
//     $request->validate(['keterangan' => 'required|string|max:255']);

    //     $ranking = Perangkingan::where('alternative_id', $alternativeId)->first();

    //     if ($ranking) {
//         // Set status ditolak (3), set rank menjadi 0 dan simpan keterangan
//         $ranking->update([
//             'keterangan' => $request->input('keterangan'),
//             'status' => 3,
//             'rank' => 0 // Set rank menjadi 0 saat ditolak
//         ]);
//     }

    //     // Setelah menolak, update ulang ranking untuk yang masih diterima (status 1)
//     $accepted = Perangkingan::where('status', 1)
//         ->orderByDesc('total_score')
//         ->get();

    //     foreach ($accepted as $index => $item) {
//         $item->update(['rank' => $index + 1]);
//     }

    //     return redirect()->route('ranking.index')->with('success', 'Calon anggota telah ditolak!');
// }

    // Mengekspor hasil ranking awal ke dalam format PDF
    public function exportPDFawal()
    {
        // Ambil semua kriteria
        $criterias = Criteria::with('subCriterias')->get();

        // Ambil semua alternatif yang memiliki data perangkingan dan nilai berbobot
        $alternatives = Alternative::where('id', '!=', 1)
            ->whereHas('perangkingan') // Pastikan ada data perangkingan
            ->with(['perangkingan', 'weightedValues']) // Ambil data perangkingan dan weightedValues terkait
            ->get()
            ->sortBy(fn($alternative) => $alternative->perangkingan->rank ?? PHP_INT_MAX);

        // Ekspor PDF menggunakan view dengan semua data yang sudah disiapkan
        $pdf = PDF::loadView('rankingpdfawal', compact('alternatives', 'criterias'))
            ->setPaper('a4', 'portrait');

        // Menggunakan stream() agar PDF ditampilkan di browser
        return $pdf->stream('Hasil Ranking Awal.pdf');
    }


    // Mengekspor hasil keputusan akhir ke dalam format PDF
    // public function exportPDFakhir()
    // {
    //     $alternatives = Alternative::where('id', '!=', 1)
    //         ->whereHas('perangkingan', fn($query) => $query->where('status', 1)) // Hanya yang diterima
    //         ->with('perangkingan')
    //         ->get()
    //         ->sortBy(fn($alternative) => $alternative->perangkingan->rank ?? PHP_INT_MAX);

    //     $pdf = PDF::loadView('rankingpdfakhir', compact('alternatives'))
    //         ->setPaper('a4', 'portrait');

    //     return $pdf->stream('Hasil Keputusan Akhir.pdf');
    // }

    public function setAcceptedCount(Request $request)
    {
        // Validasi input
        $request->validate(['accepted_count' => 'required|integer|min:1']);

        // Simpan nilai accepted_count ke session
        session(['accepted_count' => $request->input('accepted_count')]);

        // Panggil fungsi rank() setelah menyimpan data ke session
        return $this->rank();
    }


}
