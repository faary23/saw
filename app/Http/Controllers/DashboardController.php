<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Perangkingan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->id != 1) {
                return redirect()->route('login');
            }
        }
        // Hitung jumlah calon pendaftar alternatif
        $totalAlternatives = Alternative::where('id', '!=', 1)->count();

        // Hitung jumlah berkas yang sudah upload
        $totalBerkas = Alternative::whereNotNull('foto')->where('foto', '!=', '')->count();

        // Hitung jumlah yang telah dirangking (alternatif yang memiliki nilai perangkingan)
         $totalRanked = Perangkingan::whereNotNull('total_score')->where('total_score','!=','' )->count();

        // Hitung jumlah yang diterima (status = 1)
        $totalAccepted = Perangkingan::where('status', 1)->count();

        // Kirim data ke view
        return view('dashboard.index', compact('totalAlternatives', 'totalBerkas','totalRanked', 'totalAccepted'));
    }
}
