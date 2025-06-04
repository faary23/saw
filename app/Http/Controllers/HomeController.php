<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $criterias = Criteria::all();
        $acceptedCount = 10; // sesuaikan sesuai kebutuhan
        session(['accepted_count' => $acceptedCount]);

        $search = $request->input('search');

        // Ambil semua alternatif yang punya relasi perangkingan dan id != 1
        $alternatives = Alternative::where('id', '!=', 1)
            ->whereHas('perangkingan')
            ->with(['perangkingan', 'weightedValues'])
            ->get()
            ->filter(function ($item) use ($search) {
                if (!$search) return true;
                return stripos($item->nama, $search) !== false ||
                       stripos($item->jurusan, $search) !== false;
            })
            ->sortBy(fn($item) => $item->perangkingan->rank ?? PHP_INT_MAX)
            ->values();

        // Manual pagination (karena data sudah collection)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $alternatives->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedAlternatives = new LengthAwarePaginator(
            $currentPageItems,
            $alternatives->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('home.index', [
            'alternatives' => $paginatedAlternatives,
            'criterias' => $criterias
        ]);
    }
}
