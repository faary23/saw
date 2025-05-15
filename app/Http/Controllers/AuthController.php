<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Alternative;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        if (Auth::check()) {
            // Jika user sudah login dan id adalah 1, arahkan ke halaman dashboard
            if (Auth::user()->id == 1) {
                return redirect()->route('dashboard');
            }

            // Jika user sudah login tetapi bukan admin, arahkan ke halaman alternatif
            return redirect()->route('pemberkasanuser.index'); // Ganti dengan rute alternatif yang sesuai
        }

        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'nim' => 'required|string',
            'password' => 'required|string',
        ]);

        // Mencari alternatif berdasarkan NIM
        $alternative = Alternative::where('nim', $credentials['nim'])->first();

        // Memeriksa apakah alternatif ditemukan dan password valid
        if ($alternative && Hash::check($credentials['password'], $alternative->password)) {
            // Login jika password cocok
            Auth::login($alternative);

            // Regenerasi sesi untuk keamanan
            $request->session()->regenerate();

            // Jika ID adalah 1, arahkan ke halaman dashboard
            if (Auth::user()->id == 1) {
                return redirect()->route('dashboard')
                    ->with('success', 'Anda login sebagai admin!');
            }

            // Jika bukan admin, arahkan ke halaman alternatif
            return redirect()->route('pemberkasanuser.index')
                ->with('success', 'Anda login sebagai user!');
        }

        // Jika login gagal, kembali dengan error
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
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'nim' => 'required|integer|max:12|unique:alternatives,nim',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            // Buat user baru
            $alternative = Alternative::create([
                'nama' => $request->nama,
                'jurusan' => $request->jurusan,
                'nim' => $request->nim,
                'data_kriteria' => [],
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('login')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            // Jika ada error saat penyimpanan
            return redirect()->back()->with('error', 'Registration failed! Please try again.');
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
