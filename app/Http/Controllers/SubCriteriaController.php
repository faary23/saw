<?php

namespace App\Http\Controllers;

use App\Models\SubCriteria;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubCriteriaController extends Controller
{
    // Menampilkan daftar sub kriteria dengan fitur pencarian dan pagination
    public function index(Request $request)
    {
        // Cek apakah pengguna sudah login dan bukan admin (id 1)
        if (Auth::check()) {
            if (Auth::user()->id != 1) {
                return redirect()->route('login'); // Arahkan ke halaman login jika bukan admin
            }
        }
        // Ambil nilai pencarian dari request
        $search = $request->input('search');
        // Ambil data sub kriteria dengan relasi ke kriteria, dan filter berdasarkan pencarian
        $subCriterias = SubCriteria::with('criteria')
            ->when($search, function ($query, $search) {
                return $query->where('nama_sub', 'like', "%$search%") // Filter berdasarkan nama sub kriteria
                    ->orWhereHas('criteria', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%"); // Filter berdasarkan nama kriteria
                    });
            })
            ->orderBy('created_at', 'desc')// Urutkan berdasarkan tanggal pembuatan sub kriteria
            ->paginate(5); // Pagination untuk 5 sub kriteria per halaman

        // Kembalikan tampilan dengan data sub kriteria dan nilai pencarian
        return view('sub_criterias.index', compact('subCriterias', 'search'));
    }

     // Menampilkan form untuk menambah sub kriteria
    public function create()
    {
        // Ambil semua kriteria yang ada untuk ditampilkan di form
        $criterias = Criteria::all();
        return view('sub_criterias.create', compact('criterias'));
    }

     // Menyimpan sub kriteria baru
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'criteria_id' => 'required|exists:criterias,id', // Pastikan criteria_id ada dalam tabel criterias
            'nama_sub' => 'required|string|max:255', // Nama sub kriteria harus string dan maksimal 255 karakter
            'nilai' => 'required|integer', // Nilai sub kriteria harus berupa integer
        ]);

         // Simpan data sub kriteria ke dalam tabel sub_criterias
        SubCriteria::create([
            'criteria_id' => $request->criteria_id,
            'nama_sub' => $request->nama_sub,
            'nilai' => $request->nilai,
        ]);

        // Arahkan kembali ke daftar sub kriteria dengan pesan sukses
        return redirect()->route('sub_criterias.index')->with('success', 'Sub Criteria created successfully.');
    }

    // Menampilkan form untuk mengedit sub kriteria
    public function edit($id)
    {
         // Ambil data sub kriteria yang akan diedit berdasarkan ID
        $subCriteria = SubCriteria::findOrFail($id);
        // Ambil semua kriteria yang ada untuk ditampilkan di form
        $criterias = Criteria::all();
        return view('sub_criterias.edit', compact('subCriteria', 'criterias'));
    }

    // Mengupdate data sub kriteria
    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'criteria_id' => 'required|exists:criterias,id', // Pastikan criteria_id ada dalam tabel criterias
            'nama_sub' => 'required|string|max:255',  // Nama sub kriteria harus string dan maksimal 255 karakter
            'nilai' => 'required|integer', // Nilai sub kriteria harus berupa integer
        ]);

        // Ambil data sub kriteria yang akan diupdate berdasarkan ID
        $subCriteria = SubCriteria::findOrFail($id);
        // Update data sub kriteria dengan input yang baru
        $subCriteria->update([
            'criteria_id' => $request->criteria_id,
            'nama_sub' => $request->nama_sub,
            'nilai' => $request->nilai,
        ]);

         // Arahkan kembali ke daftar sub kriteria dengan pesan sukses
        return redirect()->route('sub_criterias.index')->with('success', 'Sub Criteria updated successfully.');
    }

    // Menghapus sub kriteria berdasarkan ID
    public function destroy($id)
    {
        // Ambil data sub kriteria yang akan dihapus berdasarkan ID
        $subCriteria = SubCriteria::findOrFail($id);
        // Hapus data sub kriteria
        $subCriteria->delete();
        // Arahkan kembali ke daftar sub kriteria dengan pesan sukses
        return redirect()->route('sub_criterias.index')->with('success', 'Sub Criteria deleted successfully.');
    }
}
