<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        // Ambil semua data jurusan tanpa pagination
        $jurusans = Jurusan::all();

        return view('jurusan.index', compact('jurusans'));
    }

    public function create()
    {
        return view('jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:jurusan,kode|max:100',
            'nama' => 'required|string|max:255',
        ]);


        Jurusan::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return view('jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $request->validate([
            'kode' => 'required|max:10|unique:jurusan,kode,' . $jurusan->id,
            'nama' => 'required|string|max:255',
        ]);

        $jurusan->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}
