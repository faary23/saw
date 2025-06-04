<?php

namespace App\Http\Controllers;

use App\Models\Penilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenilaiController extends Controller
{
     public function index()
    {
        $penilai = Penilai::all();
        return view('penilai.index', compact('penilai'));
    }

    public function create()
    {
        return view('penilai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required|unique:penilai',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        Penilai::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('penilai.index')->with('success', 'Penilai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $penilai = Penilai::findOrFail($id);
        return view('penilai.edit', compact('penilai'));
    }

    public function update(Request $request, $id)
    {
        $penilai = Penilai::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'nim' => 'required|unique:penilai,nim,' . $id,
            'role' => 'required',
        ]);

        $penilai->nama = $request->nama;
        $penilai->nim = $request->nim;
        if ($request->password) {
            $penilai->password = Hash::make($request->password);
        }
        $penilai->role = $request->role;
        $penilai->save();

        return redirect()->route('penilai.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Penilai::destroy($id);
        return redirect()->route('penilai.index')->with('success', 'Data berhasil dihapus.');
    }
}
