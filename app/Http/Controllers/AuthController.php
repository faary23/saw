<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Alternative;
use App\Models\SubCriteria;
use App\Models\Penilai;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {

        if (!empty(session('role'))) {
            return redirect()->route('penilaian.index');
        } else {
            if (Auth::check()) {
                if (Auth::user()->id == 1) {
                    return redirect()->route('dashboard');
                }

                return redirect()->route('pemberkasanuser.index');
            }
        }

        // Default fallback
        return view('auth.login');
    }


    // Proses login
    public function login(Request $request)
    {
        // Validasi input
       	$credentials = $request->validate([
          'nim' => 'required|string',
          'password' => 'required|string',
          ], [
          'nim.required' => 'NIM wajib diisi.',
          'password.required' => 'Password wajib diisi.',
           ]);

        // Cek di tabel alternatives
        $alternative = Alternative::where('nim', $credentials['nim'])->first();

        if ($alternative && Hash::check($credentials['password'], $alternative->password)) {
            Auth::login($alternative);
            $request->session()->regenerate();

            if (Auth::user()->id == 1) {
                return redirect()->route('dashboard')->with('success', 'Anda login sebagai admin!');
            }

            return redirect()->route('pemberkasanuser.index')->with('success', 'Anda login sebagai user!');
        }

        $penilai = Penilai::where('nim', $credentials['nim'])->first();

        if ($penilai && Hash::check($credentials['password'], $penilai->password)) {
            $request->session()->put([
                'penilai_id' => $penilai->id,
                'penilai_nama' => $penilai->nama,
                'penilai_nim' => $penilai->nim,
                'role' => $penilai->role,
            ]);

            Auth::login($penilai);
            $request->session()->regenerate();

            return redirect()->route('penilaian.index')->with('success', 'Anda login sebagai penilai!');
        }

        return back()->withErrors([
            'nim' => 'Username atau password tidak sesuai silahkan masukkan kembali.',
        ])->onlyInput('nim');
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'nim' => ['required', 'regex:/^[0-9]+$/', 'unique:alternatives,nim'],
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $alternative = Alternative::create([
                'nama' => $request->nama,
                'jurusan' => $request->jurusan,
                'nim' => $request->nim,
                'data_kriteria' => [],
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('login')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registration failed! Error: ' . $e->getMessage());
        }
    }

    // Proses logout
    public function logout(Request $request)
    {
        // Logout user
        Auth::logout();

        // Invalidasi sesi
        $request->session()->invalidate();

        // Regenerasi token sesi
        $request->session()->regenerateToken();
        $request->session()->forget(['penilai_id', 'penilai_nama', 'penilai_nim', 'role']);

        // Redirect ke halaman login atau halaman utama
        return redirect()->route('home'); // Pastikan route 'home' sudah ada di web.php
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->with('error', 'Password lama salah');
        }

        auth()->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }
}
