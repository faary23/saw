<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class PemberkasanAdminController extends Controller
{
    // Menampilkan halaman daftar alternatif untuk admin
    public function index(Request $request)
    {
        // Cek apakah user yang login bukan admin, jika iya redirect ke login
        if (Auth::check() && Auth::user()->id != 1) {
            return redirect()->route('login');
        }
        // Ambil input pencarian dari request
        $search = $request->input('search');

        // Ambil data alternative, kecuali id = 1 (biasanya ID admin atau default)
        // Jika ada pencarian, filter berdasarkan nama atau NIM
        $alternatives = Alternative::where('id', '!=', 1)
            ->when($search, function ($query) use ($search) {
                return $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nim', 'LIKE', "%{$search}%");
            })
            ->paginate(10); // Menampilkan 10 data per halaman

        return view('pemberkasanadmin.index', compact('alternatives', 'search'));
    }

     // Menampilkan halaman edit profil admin
    public function edit()
    {
        $user = Auth::user(); // Ambil data user yang sedang login
        return view('pemberkasanadmin.edit', compact('user')); // Kirim ke view
    }
}
