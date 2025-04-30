<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua kriteria untuk ditampilkan di tabel
        $criterias = Criteria::all();

        // Jumlah peserta yang diterima
        $acceptedCount = 10; // Ganti sesuai kebutuhan

        session(['accepted_count' => $acceptedCount]);

        // Ambil data alternatif yang memiliki status = 1 dan id != 1
        $alternatives = Alternative::where('id', '!=', 1)
            // ->whereHas('perangkingan', function ($query) {
            //     $query->where('status', 1);
            // })
            // ->with('perangkingan') // Hanya perlu memuat relasi perangkingan
            // ->get()
            // ->sortBy(function ($alternative) {
            //     return $alternative->perangkingan->rank ?? PHP_INT_MAX;
            // });
            ->whereHas('perangkingan')
            ->with(['perangkingan', 'weightedValues'])
            ->get()
            ->sortBy(fn($alt) => $alt->perangkingan->rank ?? PHP_INT_MAX);
            
        return view('home.index', compact('alternatives', 'criterias'));
    }
}
