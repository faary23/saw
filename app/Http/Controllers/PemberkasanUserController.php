<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Response;

class PemberkasanUserController extends Controller
{
    // Menampilkan halaman pemberkasan khusus untuk user (bukan admin)
    public function index()
    {   
        // Jika yang login adalah admin (id == 1), tolak akses
        if (Auth::check() && Auth::user()->id == 1) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Tampilkan halaman pemberkasan untuk user
        $user = Auth::user();
        return view('pemberkasanuser.index', compact('user'))->with('success', 'Anda login sebagai user.');
    }

     // Menampilkan form edit data pemberkasan
    public function edit()
    {
        // Ambil data user dan alternatif terkait
        $user = Auth::user();
        $alternative = Alternative::where('id', $user->id)->first();

        // Tampilkan form edit pemberkasan
        return view('pemberkasanuser.edit', compact('user', 'alternative'));
    }

    // Menyimpan pembaruan file pemberkasan yang diunggah user
    public function update(Request $request)
    {
        $userId = Auth::id();
        $alternative = Alternative::where('id', $userId)->first();
    
        // Jika data alternatif tidak ditemukan
        if (!$alternative) {
            return redirect()->route('pemberkasanuser.index')->with('error', 'Data pemberkasan tidak ditemukan.');
        }
    
        // Validasi file upload
        $request->validate([
            'bukti_mahasiswa_aktif' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'ktm' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'formulir_pendaftaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);
    
        // Lokasi folder penyimpanan file
        $folderPath = "public/uploads/pemberkasan/{$alternative->id}/";
        Storage::makeDirectory($folderPath);
    
        $updatedFiles = []; // Untuk mencatat nama file yang berhasil diupdate
        // Cek dan update setiap file
        $fields = ['bukti_mahasiswa_aktif', 'ktm', 'cv', 'formulir_pendaftaran', 'foto'];
        // Proses upload dan simpan
        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($alternative->$field) {
                    Storage::delete($alternative->$field);
                }
                // Simpan file baru
                $originalName = $request->file($field)->getClientOriginalName();
                $path = $request->file($field)->storeAs($folderPath, $originalName);
                $alternative->$field = str_replace('public/', 'storage/', $path);
                $updatedFiles[] = ucfirst(str_replace('_', ' ', $field)); // Simpan nama file yang diperbarui
            }
        }
    
        $alternative->save();
    
        // Buat pesan sukses dengan daftar file yang diunggah
        $message = count($updatedFiles) > 0 
            ? 'File berhasil diperbarui: ' . implode(', ', $updatedFiles) 
            : 'Pemberkasan berhasil diperbarui!';
        
        return redirect()->route('pemberkasanuser.index')->with('success', $message);
    }    

    // Method cadangan untuk update file (tidak dipakai di atas, tapi bisa untuk refactor)
    private function updateFile($request, $alternative, $fieldName, $folderPath)
    {
        if ($request->hasFile($fieldName)) {
            if ($alternative->$fieldName) {
                Storage::delete($alternative->$fieldName);
            }
            $originalName = $request->file($fieldName)->getClientOriginalName();
            $path = $request->file($fieldName)->storeAs($folderPath, $originalName);
            $alternative->$fieldName = str_replace('public/', 'storage/', $path);
        }
    }

    public function download($file)
    {
        // Download template jika file tersedia
        $filepath = public_path('template/' . $file);
        return file_exists($filepath) 
            ? Response::download($filepath) 
            : redirect()->back()->with('error', 'Gagal Terunduh');
    }
}
